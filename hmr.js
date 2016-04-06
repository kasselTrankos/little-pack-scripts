var gulp = require('gulp'),
  path = require('path'),
  colors = require('colors'),
  socketio = require('socket.io');
var PORT = process.env.PORT || 3002;
function replaceAll(str, target, replacement) {
  return str.split(target).join(replacement);
}
function reconcilePathForRequireJS(file) {

  var filepath = replaceAll(path.normalize(String(path.resolve(file.path))), '\\', '/');
  var folderz = String(filepath).split('/');
  var folds = [];

  var add = false;
  var prev = null;
  folderz.forEach(function (folder, index) {
    if (add === true) {
      folds.push(folder);
    }
    if (folder === 'app' && prev === 'src') {
      add = true;
    }
    prev = folder;
  });
  return folds.join('/');
}

gulp.task('watch:hot-reload-front-end', function () {
  var io = socketio.listen(PORT, function (err, msg, msg2) {
    if (err) {
      console.error(err);
    }
    else if (msg) {
      console.log(msg);
    }
  });
  io.on('connection', function (socket) {
    console.log(colors.yellow('Gulp hot reload: a developer client connected'));
    socket.on('disconnect', function () {
      console.log(colors.yellow('Gulp hot reload: a developer client disconnected'));
    });
  });
  gulp.watch('./src/app/**/*.js').on('change', function (file) {
    var reconciledPath = reconcilePathForRequireJS(file);
    reconciledPath = reconciledPath.substring(0, reconciledPath.length - 3);
    console.log(' wich file is changed', colors.red.italic(reconciledPath));
    setTimeout(function () {
      io.sockets.emit('hmr-js', reconciledPath);
    }, 100);

  });
});

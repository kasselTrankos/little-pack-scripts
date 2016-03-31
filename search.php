<?php
echo "bienvenido, vamos a ver que string buscar:\n";
$stdin = fopen('php://stdin', 'r');
$str = fgets($stdin);


if (strlen($str)>0) {
  echo $str."\n";
  folderize();
}else{
  echo "Nada, Bye.\n";
  exit;
}

function folderize(){
  $f = getcwd();
  $files = opendir($f);
  while (false !== ($entrada = readdir($files))) {
      echo "$entrada\n";
  }
  echo $f."\n";
}
?>

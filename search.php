<?php
require_once 'Colors.php';

$showLines = false;
foreach($argv as $a){
  if($a==='lines' || $a==='-l') $showLines=true;

}
echo "bienvenido, vamos a ver que string buscar:\n";
$stdin = fopen('php://stdin', 'r');
$str = fgets($stdin);


if (strlen($str)>0) {
  echoe($str, $showLines);
}else{
  echo "Nada, Bye.\n";
  exit;
}

function folderize($f, $str, $founded){

  if(is_dir($f)){

      $files = opendir($f);
      while (false !== ($file = readdir($files))) {
        $ff = $f.'/'.$file;
        if(is_dir($ff) && $file!='.' && $file!='..'){
          return folderize($ff, $str, $founded);
        }else if($file!='.' && $file!='..'){
          $contents = file_get_contents($ff);
          $pattern = preg_quote($str, '/');
          $pattern = "/^.*$pattern.*\$/m";
          if(preg_match_all($pattern, $contents, $matches)){
            array_push($founded, $ff);
          }
        }
      }
  }
  return $founded;
}
function echoe($str, $showLines){
  $colors = new Colors();
  $str = str_replace(array("\n\r", "\n", "\r"), '', $str);


  $founded = folderize(getcwd(), $str, []);
  if(count($founded)>0){
    for($i=0; $i<count($founded); $i++){
      $lines = getLineWithString($founded[$i], $str);
      $veces = (count($lines)>1)?" veces ":" vez ";
      $echo =  "encontrado ";
      $echo .= $colors->getColoredString(count($lines), "yellow");
      $echo .= $veces;
      $echo .="\"";
      $echo .=$colors->getColoredString($str, "light_blue");
      $echo .="\"";
      $echo .=" en  \"";
      $echo .= $colors->getColoredString($founded[$i], "light_green");
      $echo .="\" \n";
      echo $echo;

      if($showLines){

        for($t=0; $t<count($lines); $t++){
          $lin = "\t linea: ";
          $lin .= $colors->getColoredString($lines[$t][1], "yellow");
          $lin .=" ---> ";
          $lin .=$colors->getColoredString($lines[$t][0], "light_cyan");
          $lin .=" <--- \n";
          echo $lin;
        }
      }



    }

  }else{
    echo "$str nada no esta ;)\n";
  }

}
function getLineWithString($fileName, $str) {
    $lines = file($fileName);
    $i = 1;
    $founded=[];
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            $founded[] = [str_replace(array("\n\r", "\n", "\r"), '', $line), $i];
        }
        $i++;
    }
    return $founded;
}
?>

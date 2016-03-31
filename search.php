<?php
require_once 'Colors.php';
$colors = new Colors();
$showLines = false;
foreach($argv as $a){
  if($a==='lines' || $a==='-l') $showLines=true;

}
echo "bienvenido, vamos a ver que string buscar:\n";
$stdin = fopen('php://stdin', 'r');
$str = fgets($stdin);


if (strlen($str)>0) {
  folderize($str, $showLines);
}else{
  echo "Nada, Bye.\n";
  exit;
}

function folderize($str, $showLines){
  $str = str_replace(array("\n\r", "\n", "\r"), '', $str);
  $f = getcwd();
  $files = opendir($f);
  while (false !== ($file = readdir($files))) {
      $contents = file_get_contents($file);
      $pattern = preg_quote($str, '/');
      $pattern = "/^.*$pattern.*\$/m";
      if(preg_match_all($pattern, $contents, $matches)){
        $founded[] = $file;
      }
  }
  if(isset($founded)){
    for($i=0; $i<count($founded); $i++){
      $lines = getLineWithString($founded[$i], $str);
      $veces = (count($lines)>0)?"veces":"vez";
      echo "encontrado ";
      echo $colors->getColoredString(count($lines)." ".$veces, "yellow");
      echo $colors->getColoredString("\"$str\" en  \"$founded[$i]\" \n", "red");

      if($showLines){

        for($t=0; $t<count($lines); $t++){
          echo "linea:".$lines[$t][1]." --> ".$lines[$t][0]." <--- \n";
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

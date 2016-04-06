<?php
require_once('Folders.php');
require_once ('jparser-1-0-0/jparser-libs/jparser.php');
$folders = new Folders(getcwd(), '/^.*\.js$/im', true);
echo "bienvenido, listado de .js selecciona uno de ellos:\n";
$list = $folders->getFounded();
foreach($list as $key=>$val){
  echo "(".$key.") ".$list[$key][0]."\n";
}
$stdin = fopen('php://stdin', 'r');

$str = fgets($stdin);

$source = file_get_contents($list[+$str][1]);
$tokens = j_token_get_all( $source );

$Prog = JParser::parse_string( $source );
echo $Prog->dump( new JLex );;
?>

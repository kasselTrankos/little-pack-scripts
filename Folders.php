<?php
class Folders{
  private $founded;
  public function getFounded(){
    return $this->founded;
  }
  public function __construct($f, $str){
    $this->folderize($f, $str);
  }
  private function folderize($f, $str){

    if(is_dir($f)){

        $files = opendir($f);
        while (false !== ($file = readdir($files))) {
          $ff = $f.'/'.$file;
          if(is_dir($ff) && $file!='.' && $file!='..'){
            $this->folderize($ff, $str);
          }else if($file!='.' && $file!='..'){

            $contents = file_get_contents($ff);
            $pattern = preg_quote($str, '/');
            $pattern = "/^.*$pattern.*\$/m";
            if(preg_match_all($pattern, $contents, $matches)){
              $this->founded[] = $ff;
            }
          }
        }
    }
  }
}
?>

<?php
class Folders{
  private $founded;
  public function getFounded(){
    return $this->founded;
  }
  public function __construct($f, $str, $reg){
    $this->folderize($f, $str, $reg);
  }
  private function folderize($f, $str, $reg=false){

    if(is_dir($f)){

        $files = opendir($f);
        while (false !== ($file = readdir($files))) {
          $ff = $f.'/'.$file;
          if(is_dir($ff) && $file!='.' && $file!='..'){
            $this->folderize($ff, $str, $reg);
          }else if($file!='.' && $file!='..'){

            $contents = file_get_contents($ff);
            if(!$reg){
              $pattern = preg_quote($str, '/');
              $pattern = "/^.*$pattern.*\$/m";
              if(preg_match_all($pattern, $contents, $matches)){
                $this->founded[] = $ff;
              }
            }else{
              if(preg_match_all($str, $file, $matches)) $this->founded[]=array($file ,$ff);
            }


          }
        }
    }
  }
}
?>

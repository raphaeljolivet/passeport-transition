<?php

class Template {
  private $_scriptPath;
  public $properties;

  public function __construct($template_root){
      $this->properties = array();
      $this->_scriptPath = $template_root;
  }

  public function render($filename){
   ob_start();
   $fullfile = $this->_scriptPath. '/' . $filename;
   if(file_exists($fullfile)){
     include($fullfile);
    } else {
      throw new Exception("File not found : " . $fullfile);
    }
    return ob_get_clean();
  }
  public function __set($k, $v){
      $this->properties[$k] = $v;
  }
  public function __get($k){
      return $this->properties[$k];
  }
}

?>
<?php

class App
{
  protected $controller = 'Home';
  protected $method = 'index';
  protected $params = [];
  public function __construct()
  {
    /* 
      [0] -> pasti controller, klo nd ad pake default`
      [1] -> pasti method, klo nd ad pke default 
      [2, 3, ...] -> pasti params, klo nd ada pke default
    */

    $url = $this->parseURL();
    if (file_exists('../app/controllers/' . $url[0] . '.php')) {
      $this->controller = $url[0];
      unset($url[0]);
    }

    // controller
    require_once '../app/controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller;

    // method
    if (isset($url[1])) {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      }
    }

    // params
    if (!empty($url)) {
      $this->params = array_values($url); // ambil nilai paramas
    }

    // jlnkn controller & method, serta kirimkan params jk ada
    call_user_func_array([$this->controller, $this->method], $this->params); // jlnkn controller, dan method, serta send uparams;
  }

  // mengambil url, di .htaccess juga
  public function parseURL()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
}
<?php
  class Pages extends Controller {
      public function __construct(){
      }

      public function index(){
        $data = [
          'title' => 'Welcome to Camagru',
          'description' => 'this small web application allowing you to make basic photo editing using your webcam and some predefined images.',
          'enjoy' => 'Enjoy it !'
        ];
        $this->view('pages/index', $data);
      }
      public function error(){
        
        $this->view('pages/error');
      }  
  }
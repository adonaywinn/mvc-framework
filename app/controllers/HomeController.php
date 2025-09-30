<?php

class HomeController extends Controller {

    public function index() {
        $usuario = new Usuario();
        $viewData = $usuario->getData();

        $this->view('home/home', $viewData);
        
    }
    public function teste() {
        echo "Hello World = metodo teste";


    }

}
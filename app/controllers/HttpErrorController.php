<?php

class HttpErrorController extends Controller {
    
    public function index() {
        http_response_code(404);
        $this->view('errors/404');
    }

    public function notFound() {

        http_response_code(404);
        $this->view('errors/404');

    }

    public function methodNotAllowed() {
        http_response_code(405);        
        $this->view('errors/405');
    }

    public function internalServerError() {
        http_response_code(500);
        $this->view('errors/500');
    }

    public function forbidden() {
        http_response_code(403);
        $this->view('errors/403');
    }
    

}

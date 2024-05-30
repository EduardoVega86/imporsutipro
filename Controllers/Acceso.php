<?php

class Acceso extends Controller
{
    public function index()
    {
        $this->views->render($this, "index");
    }
    public function login(){
        $this->views->render($this, "index");
    }
    public function registro(){
        $this->views->render($this, "registro");
    }    
 }

<?php

require_once __DIR__ . '/../core/Controller.php';

class LandingController extends Controller
{
    public function index()
    {
        $this->render('landing/index');
    }
}

<?php

namespace Controllers;

require_once(__DIR__ . '../../vendor/autoload.php');
require_once(__DIR__ . '../../bootstrap/app.php');

class HomepageController
{
    public function index()
    {
        return View('homepage.index');
    }

}

<?php

namespace App\Controller;

use PDO;
use Slim\Views\Twig;

class PageController
{
   protected $db;
   protected $view;

   public function __construct(PDO $db, Twig $view)
   {
      $this->db = $db;
      $this->view = $view;
   }

   public function index($req, $res)
   {
      return $this->view->render($res, 'home.twig');
   }

   public function profile($req, $res)
   {
      return $this->view->render($res, 'profile.twig');
   }
}
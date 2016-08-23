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

   public function login($req, $res)
   {
      return $this->view->render($res, 'login.twig');
   }

   public function register($req, $res)
   {
      return $this->view->render($res, 'register.twig');
   }

   public function forget($req, $res)
   {
      return $this->view->render($res, 'forget.twig');
   }

   public function changePassword($req, $res)
   {
      return $this->view->render($res, 'change-password.twig');
   }

   public function update($req, $res)
   {
      return $this->view->render($res, 'update.twig');
   }

   public function share($req, $res)
   {
      return $this->view->render($res, 'share.twig');
   }

   public function needActive($req, $res)
   {
      return $this->view->render($res, 'need-active.twig');
   }
}
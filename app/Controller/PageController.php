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
      $shares = $this->db->query('select * from shares order by created_at desc')->fetchAll();
      return $this->view->render($res, 'home.twig', compact('shares'));
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

   public function reset($req, $res, $args)
   {
      $token = $args['token'];
      $query = $this->db->prepare('select reset_token, reset_exp from users where reset_token = ?');
      $query->execute([$token]);
      $exp = strtotime($query->fetch()->reset_exp);

      if ($query->rowCount() && $exp > time()) {
         return $this->view->render($res, 'reset-password.twig', ['reset_token' => $token]);
      }

      flash('errors', ['message' => '重設密碼連結已失效']);
      return $res->withRedirect('/');
   }
}
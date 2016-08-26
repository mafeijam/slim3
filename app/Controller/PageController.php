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
      $shares = $this->db->query('select shares.*,
                                  users.username, users.email,
                                  categories.name as cat_name
                                  from shares
                                  inner join users
                                  on users.id = shares.user_id
                                  inner join categories
                                  on categories.id = shares.cat_id
                                  order by shares.created_at desc')
                          ->fetchAll();

      $hots = $this->db->query('select * from shares order by views desc limit 1')->fetchAll();

      return $this->view->render($res, 'home.twig', compact('shares', 'hots'));
   }

   public function shareShow($req, $res, $args)
   {
      $id = $args['id'];

      $this->db->prepare('update shares set views = views + 1 where id = ?')->execute([$id]);

      $query = $this->db->prepare('select shares.*,
                                   users.id as uid, users.username, users.email, users.description,
                                   categories.name as cat_name
                                   from shares
                                   inner join users
                                   on users.id = shares.user_id
                                   inner join categories
                                   on categories.id = shares.cat_id
                                   where shares.id = ?');

      $query->execute([$id]);
      $share = $query->fetch();

      if (!$share) {
        $res = $res->withStatus(404);
        return $this->view->render($res, 'share-not-found.twig');
      }

      $query = $this->db->prepare('select * from shares where user_id = ? and id != ? order by created_at desc limit 3');
      $query->execute([$share->uid, $share->id]);

      $others = $query->fetchAll();

      return $this->view->render($res, 'share-show.twig', compact('share', 'others'));
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
      $categories = $this->db->query('select * from categories')->fetchAll();

      return $this->view->render($res, 'share.twig', compact('categories'));
   }

   public function reset($req, $res, $args)
   {
      $token = $args['token'];
      $query = $this->db->prepare('select reset_token, reset_exp from users where reset_token = ?');
      $query->execute([$token]);

      if ($query->rowCount() && strtotime($query->fetch()->reset_exp) > time()) {
         return $this->view->render($res, 'reset-password.twig', ['reset_token' => $token]);
      }

      return $this->view->render($res, 'reset-error.twig');
   }
}
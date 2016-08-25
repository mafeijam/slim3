<?php

namespace App\Middleware;

use Slim\Views\Twig;

class Permission
{
   protected $view;
   protected $permission;

   public function __construct(Twig $view, $permission)
   {
      $this->view = $view;
      $this->permission = $permission;
   }

   public function __invoke($req, $res, $next)
   {
      return $this->canShare() ?
         $next($req, $res) :
         $this->view->render($res, 'need-active.twig');
   }

   protected function canShare()
   {
      return $this->permission == 1;
   }
}
<?php

namespace App\Middleware;

use Slim\Views\Twig;

class TwigGlobalVar
{
   protected $twig;

   public function __construct(Twig $twig)
   {
      $this->twig = $twig;
   }

   public function __invoke($req, $res, $next)
   {
      $env = $this->twig->getEnvironment();

      $env->addGlobal('csrf_field', $this->getTokenField($req));

      $this->setFlashMessage($env, ['success', 'errors', 'old']);

      return $next($req, $res);
   }

   protected function getTokenField($req)
   {
      $token = $req->getAttribute('csrf_token');
      return'<input type="hidden" name="csrf_token" value="'. $token. '">';
   }

   protected function setFlashMessage($env, array $keys)
   {
      foreach ($keys as $key) {
         if (isset($_SESSION[$key])) {
            $env->addGlobal($key, $_SESSION[$key]);
            unset($_SESSION[$key]);
         }
      }
   }
}
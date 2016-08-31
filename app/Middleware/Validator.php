<?php

namespace App\Middleware;

use Slim\Views\Twig;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
   protected $rules = [];
   protected $customErrors = null;
   protected $to;
   protected $errors = [];

   public function __construct(array $rules, $customErrors = null, $to = null)
   {
      $this->rules = $rules;
      $this->customErrors = $customErrors;
      $this->to = $to ?: back();
   }

   public function __invoke($req, $res, $next)
   {
      foreach ($this->rules as $key => $rule) {
         try {
            $rule->assert($req->getParam($key));
         } catch (NestedValidationException $e) {
            if ($this->customErrors) {
              $e->findMessages($this->customErrors);
            }
            $this->errors[$key] = $e->getMessages();
         }
      }

      return count($this->errors) ?
         $this->abort($req, $res) :
         $next($req, $res);
   }

   protected function abort($req, $res)
   {
      $_SESSION['errors'] = array_merge($this->errors, ['message' => '操作失敗']);
      $_SESSION['old'] = $req->getParams();
      return $res->withRedirect($this->to);
   }
}
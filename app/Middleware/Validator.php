<?php

namespace App\Middleware;

use Slim\Views\Twig;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
   protected $rules = [];
   protected $customErrors = null;
   protected $to;
   protected $authorize;
   protected $errors = [];

   public function __construct(array $rules, $customErrors = null, $to, $authorize = null)
   {
      $this->rules = $rules;
      $this->customErrors = $customErrors;
      $this->to = $to;
      $this->authorize = $authorize;
   }

   public function __invoke($req, $res, $next)
   {
      if ($this->authorize($req, $res) == false) {
         return $this->abort($req, $res);
      }

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

   protected function authorize($req, $res)
   {
      if ($this->authorize) {
         return $this->authorize == $req->getParam('id');
      }

      return true;
   }
}
<?php

use Respect\Validation\Validator as v;

v::with('App\\Validation\\Rules');

$container['customErrors'] = function($c) {
   return [
      'notEmpty'     => '{{name}} 必填',
      'email'        => '不是有效電郵',
      'noWhitespace' => '{{name}} 不能有空白',
      'length'       => '{{name}} 必須最少 {{minValue}} 個字',
      'equals'       => '密碼不匹配'
   ];
};

$container['forgetValidator'] = function($c) {
   $rules = [
      'email' => v::notEmpty()->email()->available($c['db'], 'users', 'email')->setName('電郵'),
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty', 'email']);

   return new App\Middleware\Validator($rules, $customErrors, 'forget');
};

$container['loginValidator'] = function($c) {
   $rules = [
      'username' => v::notEmpty()->setName('使用者名稱'),
      'password' => v::notEmpty()->setName('密碼'),
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty']);

   return new App\Middleware\Validator($rules, $customErrors, 'login');
};

$container['registerValidator'] = function($c) {
   $rules = [
      'username'     => v::notEmpty()->noWhitespace()->unique($c['db'], 'users', 'username')->setName('使用者名稱'),
      'password'     => v::notEmpty()->noWhitespace()->length(6, 255)->setName('密碼'),
      'password_cfm' => v::equals($c['request']->getParam('password')),
      'email'        => v::notEmpty()->email()->unique($c['db'], 'users', 'email')->setName('電郵')
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty', 'noWhitespace', 'length', 'equals', 'email']);

   return new App\Middleware\Validator($rules, $customErrors, 'register');
};

$container['changePasswordValidator'] = function($c) {
   $rules = [
      'password'     => v::notEmpty()->setName('舊密碼'),
      'new_password' => v::notEmpty()->noWhitespace()->length(6, 255)->setName('新密碼'),
      'password_cfm' => v::equals($c['request']->getParam('new_password')),
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty', 'noWhitespace', 'length', 'equals']);

   return new App\Middleware\Validator($rules, $customErrors, 'change-password');
};

$container['resetPasswordValidator'] = function($c) {
   $rules = [
      'password'     => v::notEmpty()->noWhitespace()->length(6, 255)->setName('新密碼'),
      'password_cfm' => v::equals($c['request']->getParam('password')),
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty', 'noWhitespace', 'length', 'equals']);

   $token = $c['request']->getParam('reset_token');

   return new App\Middleware\Validator($rules, $customErrors, 'reset/'.$token);
};

$container['updateValidator'] = function($c) {
   $rules = [
      'email'   => v::notEmpty()->email()->unique($c['db'], 'users', 'email', $c['auth']->user()->id)->setName('電郵'),
      'website' => v::optional(v::website()->setName('個人網站'))
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty', 'email']);

   return new App\Middleware\Validator($rules, $customErrors, 'update-profile');
};

$container['shareValidator'] = function($c) {
   $rules = [
      'category' => v::notEmpty()->setName('分類'),
      'title'    => v::notEmpty()->setName('標題'),
      'body'     => v::notEmpty()->setName('內容')
   ];

   $customErrors = array_get($c['customErrors'], ['notEmpty']);

   return new App\Middleware\Validator($rules, $customErrors, 'share');
};
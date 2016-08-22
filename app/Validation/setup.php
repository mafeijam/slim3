<?php

use Respect\Validation\Validator as v;

v::with('App\\Validation\\Rules');

/*$container['customErrors'] = function($c) {
   return [
      'notEmpty' => '{{name}} 必填',
      'email'    => '不是有效電郵',
      'noWhitespace' => '{{name}} 不能有空白',
      'length'       => '{{name}} 必須最少 {{minValue}} 個字',
      'equals'       => '密碼不匹配'
   ];
};*/

$container['forgetValidator'] = function($c) {
   $rules = [
      'email' => v::notEmpty()->email()->setName('電郵'),
   ];

   $customErrors = [
      'notEmpty' => '{{name}} 必填',
      'email'    => '不是有效電郵',
   ];

   return new App\Middleware\Validator($rules, $customErrors, 'forget');
};

$container['loginValidator'] = function($c) {
   $rules = [
      'username' => v::notEmpty()->noWhitespace()->setName('使用者名稱'),
      'password' => v::notEmpty()->noWhitespace()->setName('密碼'),
   ];

   $customErrors = [
      'notEmpty'     => '{{name}} 必填',
      'noWhitespace' => '{{name}} 不能有空白',
   ];

   return new App\Middleware\Validator($rules, $customErrors, 'login');
};

$container['registerValidator'] = function($c) {
   $rules = [
      'username'     => v::notEmpty()->noWhitespace()->unique($c['db'], 'users', 'username')->setName('使用者名稱'),
      'password'     => v::notEmpty()->noWhitespace()->length(6, 255)->setName('密碼'),
      'password_cfm' => v::equals($c['request']->getParam('password')),
      'email'        => v::notEmpty()->email()->unique($c['db'], 'users', 'email')->setName('電郵')
   ];

   $customErrors = [
      'notEmpty'     => '{{name}} 必填',
      'noWhitespace' => '{{name}} 不能有空白',
      'length'       => '{{name}} 必須最少 {{minValue}} 個字',
      'email'        => '不是有效電郵',
      'equals'       => '密碼不匹配'
   ];

   return new App\Middleware\Validator($rules, $customErrors, 'register');
};

$container['changePasswordValidator'] = function($c) {
   $rules = [
      'password'     => v::notEmpty()->noWhitespace()->length(6, 255)->setName('舊密碼'),
      'new_password' => v::notEmpty()->noWhitespace()->length(6, 255)->setName('新密碼'),
      'password_cfm' => v::equals($c['request']->getParam('new_password')),
   ];

   $customErrors = [
      'notEmpty'     => '{{name}} 必填',
      'noWhitespace' => '{{name}} 不能有空白',
      'length'       => '{{name}} 必須最少 {{minValue}} 個字',
      'equals'       => '密碼不匹配'
   ];

   return new App\Middleware\Validator($rules, $customErrors, 'changepassword', $c['auth']->user()->id);
};

$container['updateValidator'] = function($c) {
   $rules = [
      'email' => v::notEmpty()->email()->unique($c['db'], 'users', 'email', $c['auth']->user()->id)->setName('電郵'),
      'website' => v::optional(v::website()->setName('個人網站'))
   ];

   $customErrors = [
      'email' => '不是有效電郵'
   ];

   return new App\Middleware\Validator($rules, $customErrors, 'update', $c['auth']->user()->id);
};
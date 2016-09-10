<?php

$app->get('/', 'ShareController:index');

$app->get('/active/{code}', 'AuthController:active');

$app->group('/', function(){
   $this->get('login', 'PageController:login');
   $this->post('login', 'AuthController:login')->add('loginValidator');
   $this->get('register', 'PageController:register');
   $this->post('register', 'UserController:create')->add('registerValidator');
   $this->get('forget', 'PasswordController:getForget');
   $this->post('forget', 'PasswordController:postForget')->add('forgetValidator');
   $this->get('reset/{token}', 'PasswordController:getReset');
   $this->post('reset', 'PasswordController:postReset')->add('resetPasswordValidator');
})->add('guest');


$app->group('/', function(){
   $this->get('logout', 'AuthController:logout');
   $this->get('profile', 'UserController:index');
   $this->get('change-password', 'PasswordController:index');
   $this->post('change-password', 'PasswordController:update')->add('changePasswordValidator');
   $this->get('update-profile','PageController:update');
   $this->post('update-profile','UserController:update')->add('updateValidator');
   $this->post('reactive', 'AuthController:reactive');
   $this->get('share', 'ShareController:create')->add('isActive');
   $this->post('share', 'ShareController:save')->add('shareValidator');
   $this->get('share/{id}/like', 'ShareController:toggleLike')->add('ajaxOnly');
   $this->post('comments', 'ShareController:comments')->add('commentsValidator');
})->add('guard');

$app->group('/admin', function(){
   $this->get('/dashboard', 'AdminController:index');
})->add('adminOnly')->add('guard');

$app->get('/share/{id}[/{title}]', 'ShareController:show');

//$app->get('/[{path:.*}]', 'AuthController:index');
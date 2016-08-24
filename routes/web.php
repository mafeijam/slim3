<?php

$app->get('/', 'PageController:index');

$app->get('/active/{code}', 'AuthController:active');
$app->get('/need-active', 'PageController:needActive')->add('notActiveOnly');

$app->group('', function(){
   $this->get('/login', 'PageController:login');
   $this->post('/login', 'AuthController:login')->add('loginValidator');
   $this->get('/register', 'PageController:register');
   $this->post('/register', 'AuthController:register')->add('registerValidator');
   $this->get('/forget', 'PageController:forget');
   $this->post('/forget', 'AuthController:forget')->add('forgetValidator');
   $this->get('/reset/{token}', 'PageController:reset');
   $this->post('/reset', 'AuthController:reset')->add('resetPasswordValidator');
})->add('guest');


$app->group('', function(){
   $this->get('/logout', 'AuthController:logout');
   $this->get('/profile', 'PageController:profile');
   $this->get('/changepassword', 'PageController:changePassword');
   $this->post('/changepassword', 'AuthController:changePassword')->add('changePasswordValidator');
   $this->get('/update','PageController:update');
   $this->post('/update','AuthController:update')->add('updateValidator');
   $this->post('/reactive', 'AuthController:reactive');

   $this->get('/share', 'PageController:share')->add('permission');
})->add('guard');



//$app->get('/[{path:.*}]', 'AuthController:index');
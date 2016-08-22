<?php

$app->get('/', 'PageController:index');

$app->get('/active/{code}', 'AuthController:active');

$app->group('', function(){
   $this->get('/login', 'AuthController:getLogin');
   $this->post('/login', 'AuthController:postLogin')->add('loginValidator');

   $this->get('/register', 'AuthController:getRegister');
   $this->post('/register', 'AuthController:postRegister')->add('registerValidator');

   $this->get('/forget', 'AuthController:getForget');
   $this->post('/forget', 'AuthController:postForget')->add('forgetValidator');
})->add('guest');


$app->group('', function(){
   $this->get('/logout', 'AuthController:logout');
   $this->get('/profile', 'PageController:profile');
   $this->get('/changepassword', 'AuthController:getChangePassword');
   $this->post('/changepassword', 'AuthController:postChangePassword')->add('changePasswordValidator');
   $this->get('/update','AuthController:getUpdate');
   $this->post('/update','AuthController:postUpdate')->add('updateValidator');
   $this->post('/reactive', 'AuthController:reactive');
})->add('guard');



//$app->get('/[{path:.*}]', 'AuthController:index');
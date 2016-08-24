<?php

$container['phpMailer'] = function($c) {
   extract($c->get('mailtrap'));
   $mailer = new PHPMailer;
   $mailer->isSMTP();
   $mailer->Host = 'mailtrap.io';
   $mailer->SMTPAuth = true;
   $mailer->Port = 2525;
   $mailer->Username = $username;
   $mailer->Password = $password;
   $mailer->setFrom('me@slim3.dev', 'me');
   $mailer->isHTML(true);
   $mailer->CharSet = 'UTF-8';
   return $mailer;
};

$container['twigMail'] = function($c) {
   $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../view/email');
   return new Twig_Environment($loader);
};

$container['mailer'] = function($c) {
   return new App\Mail\Mailer($c['phpMailer'], $c['twigMail']);
};
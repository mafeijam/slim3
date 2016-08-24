<?php

namespace App\Mail;

use PHPMailer;
use Twig_Environment;

class Mailer
{
   protected $mailer;
   protected $twig;

   public function __construct(PHPMailer $mailer, Twig_Environment $twig)
   {
      $this->mailer = $mailer;
      $this->twig = $twig;
   }

   public function to($address, $name)
   {
      $this->mailer->addAddress($address, $name);
      return $this;
   }

   public function send($body, array $data = [])
   {
      $this->mailer->Body = $this->twig->render($body.'.twig', $data);
      return $this->mailer->send();
   }
}
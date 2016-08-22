<?php

namespace App\Mail;

use PHPMailer;

class Mailer
{
   protected $mailer;

   public function __construct(PHPMailer $mailer)
   {
      $this->mailer = $mailer;
   }

   public function to($address, $name)
   {
      $this->mailer->addAddress($address, $name);
      return $this;
   }

   public function send($body, array $data = [])
   {
      $mailer = $this->mailer;
      extract($data);
      ob_start();
      require __DIR__ . "/../../view/email/$body.php";
      $mailer->Body = ob_get_clean();
      if ($mailer->send() == false) {
         trigger_error('電郵未能發送');
      }
   }
}
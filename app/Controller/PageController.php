<?php

namespace App\Controller;

class PageController
{
   public function login($req, $res)
   {
      return view($res, 'login');
   }

   public function register($req, $res)
   {
      return view($res, 'register');
   }

   public function update($req, $res)
   {
      return view($res, 'update-profile');
   }
}
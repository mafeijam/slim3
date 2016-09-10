<?php

namespace App\Controller;

class AdminController
{
   public function index($req, $res)
   {
      return view($res, 'admin.home');
   }
}
<?php

namespace App;

use Slim\App as Slim;

class App extends Slim
{
   protected static $instance;

   public function __construct($container)
   {
      parent::__construct($container);
      static::$instance = $this;
   }

   public static function getInstance()
   {
      return static::$instance->getContainer();
   }
}
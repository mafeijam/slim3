<?php

namespace App;

use ReflectionMethod;

class FoundHandler
{
   protected $container;

   public function __construct($container)
   {
      $this->container = $container;
   }

   public function __invoke($callable, $req, $res, $args)
   {
      foreach ($args as $k => $v) {
         $req = $req->withAttribute($k, $v);
      }

      if (is_array($callable)) {
         list($controller, $method) = $callable;
         $reflector = new ReflectionMethod($controller, $method);
         $resolver = $this->container->get('callableResolver');
         $dependencies = $resolver->getDependencies($reflector->getParameters());
         array_unshift($dependencies, $req, $res, $args);
         return call_user_func_array($callable, $dependencies);
      }

      return call_user_func($callable, $req, $res, $args);
   }
}
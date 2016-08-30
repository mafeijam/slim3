<?php

namespace App;

use ReflectionClass;
use ReflectionParameter;

class Resolver
{
   protected $container;
   protected $namespace;

   public function __construct($container, $namespace = 'App\Controller\\')
   {
      $this->container = $container;
      $this->namespace = $namespace;
   }

   public function resolve($callable)
   {
      if (!is_callable($callable) && $this->container->has($callable)) {
         return $this->container->get($callable);
      }

      if (is_string($callable) && strripos($callable, ':')) {
         list($controller, $method) = explode(':', $callable);
         return [$this->make($this->namespace.$controller), $method];
      }

      return $callable;
   }

   public function make($class)
   {
      $reflector = new ReflectionClass($class);

      $constructor = $reflector->getConstructor();
      if (is_null($constructor)) {
         return new $class;
      }

      $parameters = $constructor->getParameters();
      if (empty($parameters)) {
         return new $class;
      }

      return $reflector->newInstanceArgs($this->getDependencies($parameters));
   }

   public function getDependencies(ReflectionParameter $parameters)
   {
      $dependencies = [];

      foreach ($parameters as $parameter) {
         if ($class = $parameter->getClass()) {
            $dependencies[] = $this->make($class->name);
         }
      }

      return $dependencies;
   }
}
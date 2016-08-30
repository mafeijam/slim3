<?php

return [
   'callableResolver' => function($c) {
      return new App\Resolver($c);
   },

   'foundHandler' => function($c) {
      return new App\FoundHandler($c);
   }
];
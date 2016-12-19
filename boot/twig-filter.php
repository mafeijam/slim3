<?php

return [
   'diffForHumans' => new Twig_SimpleFilter('diffForHumans', function ($string) {
      return (new Carbon\Carbon($string))->diffForHumans();
   }),

   'slug' => new Twig_SimpleFilter('slug', function ($string) {
      return trim(str_replace(' ', '-', $string));
   })
];
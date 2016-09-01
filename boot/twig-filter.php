<?php

return [
   'diffForHumans' => new Twig_SimpleFilter('diffForHumans', function ($string) {
      return Carbon\Carbon::createFromTimestamp(strtotime($string))->diffForHumans();
   }),

   'slug' => new Twig_SimpleFilter('slug', function ($string) {
      return trim(str_replace(' ', '-', $string));
   })
];
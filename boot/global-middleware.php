<?php

use App\Middleware\Back;
use App\Middleware\CsrfCheck;
use App\Middleware\PjaxHeader;
use App\Middleware\PjaxFullReload;
use App\Middleware\TwigGlobalVar;

$app->add(new TwigGlobalVar($container['view']));
$app->add(new CsrfCheck(['/api/*']));
$app->add(new PjaxHeader);
$app->add(new PjaxFullReload);
$app->add(new Back);
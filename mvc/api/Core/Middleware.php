<?php
namespace Core;

use \Closure;

interface Middleware {

    public function handle($request, Closure $next);

}
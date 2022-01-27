<?php

namespace Src\Controllers\Interfaces;

interface Middleware {

	public static function middleware(RouteCollector $router): RouteCollector;

}
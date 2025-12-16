<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

    /**
     * @return Nette\Routing\Router
     */
	public static function createRouter(): Nette\Routing\Router
	{
		$router = new RouteList;
		$router[] = new Route('import', 'Homepage:import');
		$router[] = new Route('b2b', 'Homepage:b2b');
		$router[] = new Route('2025', 'Homepage:2025');
		$router[] = new Route('<presenter>/<action>', 'Homepage:default');
		return $router;
	}
}

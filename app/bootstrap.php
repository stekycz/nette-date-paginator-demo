<?php

use Nette\Config\Configurator;
use Nette\Forms\Container;
use Nette\Application\Routers\Route;

// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';

// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode(Configurator::AUTO);
$configurator->enableDebugger(APP_DIR . '/../log', 'martin.stekl@gmail.com');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(APP_DIR . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(APP_DIR . '/config/config.neon');
$container = $configurator->createContainer();

// Setup router
$container->router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new JanTvrdik\Components\DatePicker($label);
});

// Configure and run the application!
$container->application->run();

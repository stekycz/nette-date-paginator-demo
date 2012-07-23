<?php
/**
 * Soubor pro načtení všech souborů PHPUnit testů. Používá se jen pro spouštění
 * testů v IDE PHPStorm.
 *
 * @author Martin Štekl <martin.stekl@gmail.com>
 * @since 2012-07-23
 */

use Nette\Config\Configurator;
use Nette\Forms\Container;
use Nette\Application\Routers\Route;

define('TEST_DIR', __DIR__);
define('WWW_DIR', TEST_DIR . '/../www');
define('APP_DIR', TEST_DIR . '/../app');
define('LIBS_DIR', TEST_DIR . '/../libs');

// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';

// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode(Configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log', 'martin.stekl@gmail.com');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

// Setup router
$container->router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = NULL) {
	return $container[$name] = new JanTvrdik\Components\DatePicker($label);
});

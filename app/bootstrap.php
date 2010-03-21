<?php

/**
 * My Application bootstrap file.
 *
 * @copyright  Copyright (c) 2009 John Doe
 * @package    MyApplication
 */



// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';
//require dirname(__FILE__) . '/../../../Nette/loader.php';

if(preg_match('/^(127.|::1)/',$_SERVER["REMOTE_ADDR"])) {
	Environment::setName(Environment::DEVELOPMENT);
}else{
	Environment::setName(Environment::PRODUCTION);
}

// Step 2: Configure environment
// 2a) load configuration from config.ini file
Environment::loadConfig();

// 2b) enable Nette\Debug for better exception and error visualisation
Debug::enable();

// 2c) setup sessions
$session = Environment::getSession();
$session->setSavePath(APP_DIR . '/sessions/');



// Step 3: Configure application
// 3a) get and setup a front controller
$application = Environment::getApplication();
$application->errorPresenter = 'Error';
if(Environment::isProduction()){
	$application->catchExceptions = TRUE;
}



// Step 4: Setup application router
$router = $application->getRouter();

$router[] = new Route('index.php', array(
	'presenter' => 'Homepage',
	'action' => 'default',
), Route::ONE_WAY);

$router[] = new Route('<presenter>/<action>/<id>', array(
	'presenter' => 'Homepage',
	'action' => 'default',
	'id' => NULL,
));

// Step 4.1: Setup MultipleFileUpload
MultipleFileUpload::register();

// When you want to use MFU dibi driver
//if(class_exists("Dibi",true)) {
//
//	Dibi::connect(array(
//		"driver"   => "postgre",
//		"host"     => "127.0.0.1",
//		"dbname"   => "MFU",
//		"schema"   => "public",
//		"user"     => "postgres",
//		"pass"     => "toor",
//		"charset"  => "UTF-8"
//	));
//
//	MultipleFileUpload::$queuesModel = new MFUQueuesDibi();
//}

// Step 5: Run the application!
$application->run();

<?php
/**
 * F3 DEMO TODO App
 */

/** @var Base $f3 */
$f3 = require('lib/base.php');
// set autoload path
$f3->set('AUTOLOAD','app/');

// force displaying errors
ini_set('display_errors', 1);
error_reporting(-1);

$f3->set('DEBUG', 2);

// create DB connection
\Registry::set('DB', new \DB\SQL('mysql:host=localhost;port=3306;dbname=fatfree', 'fatfree', ''));

// define some routes
$f3->route('GET /', '\Controller\Task->get');
$f3->route('GET /list', '\Controller\Task->get');
$f3->route('POST /save', '\Controller\Task->post');
$f3->route('GET /delete/@id', '\Controller\Task->delete');
$f3->route('GET /check/@id [ajax]', '\Controller\Task->check');
$f3->route('GET /uncheck/@id [ajax]', '\Controller\Task->uncheck');


// install required DB table
$f3->route('GET /install',function()
{
	$db = \Registry::get('DB');
	// SQL Schema Plugin, https://github.com/ikkez/F3-Sugar/tree/master-v3/SchemaBuilder
	$schema = new \DB\SQL\Schema($db);
	$tables = $schema->getTables();
	if(!in_array('tasks',$tables)) {
		$table = $schema->createTable('tasks');
		$table->addColumn('text')->type_text();
		$table->addColumn('cr_data')->type_timestamp(true);
		$table->addColumn('finished')->type_bool()->defaults(0);
		$table->build();
		echo "finished";
	} else {
		echo "already installed";
	}
});


$f3->run();
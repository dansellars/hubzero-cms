<?php
/**
 * @package		Joomla.Administrator
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Set flag that this is a parent file
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', __DIR__);
	require_once dirname(JPATH_BASE).'/core/bootstrap/administrator/defines.php';
}

require_once JPATH_ROOT.'/core/bootstrap/administrator/framework.php';

// Mark afterLoad in the profiler.
JPROFILE ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('administrator');

// Initialise the application.
$app->initialise(array(
	'language' => $app->getUserState('application.lang')
));

// Mark afterIntialise in the profiler.
JPROFILE ? $_PROFILER->mark('afterInitialise') : null;

// Route the application.
$app->route();

// Mark afterRoute in the profiler.
JPROFILE ? $_PROFILER->mark('afterRoute') : null;

// Dispatch the application.
$app->dispatch();

// Mark afterDispatch in the profiler.
JPROFILE ? $_PROFILER->mark('afterDispatch') : null;

// Render the application.
$app->render();

// Mark afterRender in the profiler.
JPROFILE ? $_PROFILER->mark('afterRender') : null;

// Return the response.
echo $app;

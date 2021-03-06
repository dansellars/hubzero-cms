<?php

use Hubzero\Content\Migration\Base;

// No direct access
defined('_HZEXEC_') or die();

/**
 * Migration script for adding Usage - Tools plugin
 **/
class Migration20170831000000PlgUsageTools extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		$this->addPluginEntry('usage', 'tools');
	}

	/**
	 * Down
	 **/
	public function down()
	{
		$this->deletePluginEntry('usage', 'tools');
	}
}

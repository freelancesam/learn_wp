<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_longer_url extends CI_Migration {
	public function up()
	{
		$this->dbforge->modify_column(
			'locations',
			array(
				'website' => array(
					'type' => 'VARCHAR(300)',
					)
				)
			);
	}

	public function down()
	{
	}
}

<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Model_LC_HC_MVC extends _HC_ORM
{
	protected $table = 'locations';
	protected $default_order_by = array(
		'name'	=> 'ASC',
		);
	protected $search_in = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country');

	public function get_places()
	{
		$this
			->distinct()
			->fetch_fields( 'CONCAT_WS(":", TRIM(country), TRIM(state), TRIM(city) ) AS location' )
			->order_by( 'location', 'ASC' )
			;
		return $this->fetch_array();
	}

	public function get_notgeocoded()
	{
		$this
			->where( 'latitude', '=', NULL )
			;
		return $this->fetch_array();
	}
}
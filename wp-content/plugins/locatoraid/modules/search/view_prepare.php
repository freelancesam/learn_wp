<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_View_Prepare_LC_HC_MVC extends _HC_MVC
{
	public function execute( $results = array(), $search = '', $search_coordinates = array() )
	{
		$p = $this->app->make('/locations/presenter');

		for( $ii = 0; $ii < count($results); $ii++ ){
			$p->set_data( $results[$ii] );
			$results[$ii] = $p->present_front($search, $search_coordinates);

			if( array_key_exists('distance', $results[$ii]) ){
				$results[$ii]['distance_raw'] = $results[$ii]['distance'];
				$results[$ii]['distance'] = $p->run('present-distance');
			}
		}

		$results = $this->app
			->after( $this, $results )
			;

		return $results;
	}
}
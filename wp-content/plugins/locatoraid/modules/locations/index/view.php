<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Index_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $entries, $total_count, $page = 1, $search = '', $per_page = 5 )
	{
		$header = $this->header();
		$sort = $this->sort();

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->row( $e );
		}

		$out = $this->make('/html/view/list')
			->set_gutter(1)
			;

		$submenu = $this->make('/html/view/list-inline')
			->set_scale('sm')
			;

		if( $total_count > $per_page ){
			$pager_link = $this->make('/html/view/link')
				// ->add_attr('class', 'hcj2-ajax-loader')
				;
			$pager = $this->make('/html/view/pager')
				->set_link_template( $pager_link )
				->set_total_count( $total_count )
				->set_current_page( $page )
				->set_per_page($per_page)
				;

			$submenu
				->add( $pager )
				;
		}

		$search_view = $this->make('/modelsearch/view');
		$submenu
			->add( $search_view->run('render', $search) )
			;

		$out
			->add( $submenu )
			;

		if( $rows ){
			// $table = $this->make('/html/view/sorted-table')
				// ->set_header($header)
				// ->set_rows($rows)
				// ->set_sort($sort)
				// ;
			$table = $this->make('/html/view/table-responsive')
				->set_header($header)
				->set_rows($rows)
				->set_sort($sort)

				->add_attr('class', 'hc-border')
				;
			$out
				->add( $table )
				;
		}
		elseif( $search ){
			$msg = HCM::__('No Matches');
			$out
				->add( $msg )
				;
		}

		return $out;
	}

	public function header()
	{
		$return = array(
			'title' 	=> HCM::__('Title'),
			'address' 	=> HCM::__('Address'),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function sort()
	{
		$return = array(
			'title'	=> 1,
			);
		return $return;
	}

	public function row( $e )
	{
		$return = array();
		if( ! $e ){
			return $return;
		}

		$p = $this->app->make('/locations/presenter')
			->set_data( $e )
			;

		$title_view = $p->run('present-title');

		$title_view = $this->make('/html/view/link')
			->to('/locations/' . $e['id'] . '/edit')
			->add( $title_view )
		// imitate wordpress
			->add_attr('class', 'hc-bold')
			->add_attr('class', 'hc-fs4')
			->add_attr('class', 'hc-decoration-none')
			;

		$return['title'] = $title_view;

		$return['id']		= $e['id'];
		$id_view = $this->make('/html/view/element')->tag('span')
			->add_attr('class', 'hc-fs2')
			->add_attr('class', 'hc-muted-2')
			->add( $e['id'] )
			;
		$return['id_view']	= $id_view->run('render');

		$address_view = $p->run('present-address');
		$return['address'] = $address_view;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $e )
			;

		return $return;
	}
}
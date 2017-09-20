<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends Admin_controller
{
	function __construct()
	{
		parent::__construct();
		$this->per_page = 20;
		$this->load->library( 'hc_time' );
		$this->load->model( 'Log_model' );
		$this->shortcuts = array(
			'today', 'yesterday', 'thismonth', 'lastmonth', 'all'
			);
	}

	function shortcut()
	{
		$shortcut = $this->input->post('shortcut');
		if(! in_array($shortcut, $this->shortcuts))
		{
			$shortcut = $this->shortcuts[0];
		}
		ci_redirect( 'pro/admin/stats/' . $shortcut );
		exit;
	}

	function index()
	{
		$shortcut = '';
		$args = func_get_args();
		if( count($args) == 2 )
		{
			$from_date = $args[0];
			$to_date = $args[1];
		}
		elseif( count($args) == 1 )
		{
			$shortcut = $args[0];
		}
		else
		{
			$shortcut = 'today';
		}

		if( $shortcut && (! in_array($shortcut, $this->shortcuts)) )
		{
			$shortcut = $this->shortcuts[0];
		}
		
		if( $shortcut )
		{
			switch( $shortcut )
			{
				case 'today':
					$this->hc_time->setNow();
					$from_date = $this->hc_time->formatDate_Db();
					$to_date = $from_date;
					break;
				case 'yesterday':
					$this->hc_time->setNow();
					$this->hc_time->modify( '-1 day' );
					$from_date = $this->hc_time->formatDate_Db();
					$to_date = $from_date;
					break;
				case 'thismonth':
					$this->hc_time->setNow();
					$this->hc_time->setStartMonth();
					$from_date = $this->hc_time->formatDate_Db();
					$this->hc_time->setEndMonth();
					$to_date = $this->hc_time->formatDate_Db();
					break;
				case 'lastmonth':
					$this->hc_time->setNow();
					$this->hc_time->modify( '-1 month' );
					$this->hc_time->setStartMonth();
					$from_date = $this->hc_time->formatDate_Db();
					$this->hc_time->setEndMonth();
					$to_date = $this->hc_time->formatDate_Db();
					break;
				case 'all':
					$from = $this->Log_model->earliest();
					if( $from )
						$this->hc_time->setTimestamp( $from );
					else
						$this->hc_time->setNow();
					$from_date = $this->hc_time->formatDate_Db();

					$this->hc_time->setNow();
					$to_date = $this->hc_time->formatDate_Db();
					break;
			}
		}

		$now = time();
		$this->hc_time->setDateDb( $from_date );
		$from = $this->hc_time->getTimestamp();

		$this->hc_time->setDateDb( $to_date );
		$this->hc_time->getEndDay();
		$to = $this->hc_time->getTimestamp() - 1;
		if( $to > $now )
			$to = $now;

		$this->data['from'] = $from;
		$this->data['to'] = $to;

		$this->data['entries'] = $this->Log_model->get( $from, $to );
		$this->data['shortcuts'] = $this->shortcuts;
		$this->data['shortcut'] = $shortcut;

		$this->data['include'] = 'admin/stats';
		$this->load->view( $this->template, $this->data);
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */
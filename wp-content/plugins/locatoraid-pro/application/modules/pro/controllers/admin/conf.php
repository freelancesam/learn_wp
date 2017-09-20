<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conf extends Admin_controller
{
	private $params = array();

	function __construct()
	{
		parent::__construct();

		$this->params = array(
			'form' => array(
				'form_misc1',
				'form_misc2',
				'form_misc3',
				'form_misc4',
				'form_misc5',
				'form_misc6',
				'form_misc7',
				'form_misc8',
				'form_misc9',
				'form_misc10',
/*
 				'form_misc1_hide',
				'form_misc2_hide',
				'form_misc3_hide',
				'form_misc4_hide',
				'form_misc5_hide',
				'form_misc6_hide',
				'form_misc7_hide',
				'form_misc8_hide',
				'form_misc9_hide',
				'form_misc10_hide',
*/
				'form_products',
				'form_website',
				),
			);

		$this->data['defaults'] = array();
		reset( $this->params );
		foreach( $this->params as $pk => $pa ){
			reset( $pa );
			foreach( $pa as $p ){
				$this->data['defaults'][$p] = $this->app_conf->get($p);
				}
			}
	}

	function index( $what = 'form' )
	{
		if( $this->form_validation->run('conf-' . $what) == false ){
		// display the form
			$this->data['include'] = 'admin/conf/' . $what;
			$this->load->view( $this->template, $this->data );
			}
		else {
		// update
			reset( $this->params[$what] );
			foreach( $this->params[$what] as $p ){
				$v = $this->input->post( $p );
				$this->app_conf->set( $p, $v );
				}

		// redirect back
			$msg = lang('common_update') . ': ' . lang('common_ok');
			$this->session->set_flashdata( 'message', $msg );
			ci_redirect( 'pro/admin/conf/' . $what );
			}
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */
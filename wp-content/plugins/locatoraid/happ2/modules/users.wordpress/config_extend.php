<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$alias['/users/model'] = 'model/user';

$after['/users/index/view/layout->menubar'] = function( $app, $return )
{
	$return['settings'] = $app->make('/html/view/link')
		->to('/conf', array('tab' => 'wordpress-users'))
		->add( $app->make('/html/view/icon')->icon('cog') )
		->add( HCM::__('Settings') )
		;

	if( current_user_can('create_users') ){
		$link = admin_url( 'user-new.php' );
		$return['add'] = $app->make('/html/view/link')
			->to($link)
			->add( $app->make('/html/view/icon')->icon('plus') )
			->add( HCM::__('Add New') )
			;
	}

	return $return;
};

$after['/users/index/view->header'] = function( $app, $return )
{
	$return['wp_user'] = 
		$app->make('/html/view/icon')->icon('wordpress') . __('Username') . ' / ' . __('Role')
		;

	unset($return['email']);
	unset($return['roles']);

	$return['roles'] = HCM::__('Plugin Role');

	return $return;
};

$after['/users/index/view->row'] = function( $app, $return, $e )
{
	$p = $app->make('/users/presenter');
	$p->set_data( $e );

	$wp_roles = $e['_wp_userdata']['roles'];

	$wp_roles_obj = new WP_Roles();
	$wordpress_roles_names = $wp_roles_obj->get_names();

	$wp_roles_view = array();
	reset( $wp_roles );
	foreach( $wp_roles as $wp_role ){
		$wp_role_name = isset($wordpress_roles_names[$wp_role]) ? $wordpress_roles_names[$wp_role] : $wp_role;
		$wp_roles_view[] = $wp_role_name;
	}

	$wp_roles_view = join(', ', $wp_roles_view);
	$wp_roles_view = $app->make('/html/view/element')->tag('span')
		->add( $wp_roles_view )
		->add_attr('class', 'hc-muted2')
		->add_attr('class', 'hc-fs2')
		;

	$return['wp_user'] = $app->make('/html/view/list')
		->add( $e['username'] )
		->add( $wp_roles_view )
		;

	return $return;
};

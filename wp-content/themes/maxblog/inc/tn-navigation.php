<?php

//mega menu
class TN_Walker extends Walker_Nav_Menu
{

	function start_el(&$output, $object, $depth = 0, $args = array(), $id = 0)
	{
		parent::start_el($output, $object, $depth, $args);
		$tn_cate_menu = $object->tnmegamenu;
		$tn_current_classes = $object->classes;
		$posts_per_page = 0;
		$tn_has_children = '';

		if (in_array('menu-item-has-children', $tn_current_classes)) {
			$tn_has_children = 'menu-with-sub';
		}

        if (($object->menu_item_parent == '0') & ($object->tnmegamenu == '1')) {
            if ($tn_has_children == 'menu-with-sub') {
                $posts_per_page = 3;
            } else {
                $posts_per_page = 4;
            }
        };

		if (($tn_cate_menu == 1) && ($object->menu_item_parent == '0')) {
			if ($object->object == "category") {
				$output .= '<div class="tn-mega-menu">';
				$array_query = array();
				$array_query['category_id'] = $object->object_id;
				$array_query['posts_per_page'] = $posts_per_page;
				$array_query['meta_key'] = '_thumbnail_id';
				$query_data = tn_custom_query($array_query);
				if($tn_has_children == 'menu-with-sub'){
					$open_wrap = '<div class="tn-sub-post-wrap has-sub-menu row">';
					$open_element='<div class="col-xs-4">';

				} else {
					$open_wrap = '<div class="tn-sub-post-wrap row">';
					$open_element='<div class="col-xs-3">';
				}
				$output .= $open_wrap;
				foreach ($query_data->posts as $post) {
					$output .=$open_element;
					$output .= tn_blockNavigation($post);
					$output .='</div>';
				}
				$output .= '</div><!--#sub post wrap-->';
			}

			if ($object->object == "custom") {
				$output .= '<div class="tn-mega-menu-col">';
			}
		}

		if ( ( $tn_cate_menu == 0 )  && ( $object->menu_item_parent == '0')&& ( in_array('menu-item-has-children', $tn_current_classes) ) ) {
			$output .= '<div class="tn-dropdown-menu">';
		}


		if (($tn_has_children == NULL) && ($object->tnmegamenu == '1')) {
			$output .= '</div><!--#tn mega menu -->';
		};
	}


	//start of the sub menu wrap
	function start_lvl(&$output, $depth = 0, $args = array())
	{

		if ($depth > 2) {
			return;
		}
		if ($depth == 1) {
			$output .= '<ul class="tn-sub-menu">';
		}
		if ($depth == 0) {
			$output .= '<div class="tn-sub-menu-wrap"><ul class="tn-sub-menu clearfix">';
		}
	}

	function end_lvl(&$output, $depth = 0, $args = array())
	{

		if ($depth > 2) {
			return;
		}
		if ($depth == 0) {
			$output .= '</ul></div></div>';
		}
		if ($depth == 1) {
			$output .= '</ul>';
		}
	}
}

//admin menu setting
class tn_walker_backend extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {}
	function end_lvl( &$output, $depth = 0, $args = array() ) {}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();
		$item_id = esc_attr( $item->ID );
		if (empty($item->tnmegamenu[0])) {
			$tn_item_megamenu = NULL;
		} else {
			$tn_item_megamenu = esc_attr ($item->tnmegamenu[0]);
		}
		$removed_args = array( 'action','customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab',  '_wpnonce', );

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = $original_object->post_title;
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			$title = sprintf( __( '%s (Invalid)' , 'tn'), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			$title = sprintf( __('%s (Pending)' , 'tn'), $item->title);
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';

		?>
	<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
		<dl class="menu-item-bar">
			<dt class="menu-item-handle">
				<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo esc_attr($submenu_text); ?>><?php _e( 'sub item' , 'tn'); ?></span></span>
                    <span class="item-controls">
                        <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                        <span class="item-order hide-if-js">
                            <a href="<?php
                            echo wp_nonce_url(
	                            add_query_arg(
		                            array(
			                            'action' => 'move-up-menu-item',
			                            'menu-item' => $item_id,
		                            ),
		                            remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                            ),
	                            'move-menu_item'
                            );
                            ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'tn'); ?>">&#8593;</abbr></a>
                            |
                            <a href="<?php
                            echo wp_nonce_url(
	                            add_query_arg(
		                            array(
			                            'action' => 'move-down-menu-item',
			                            'menu-item' => $item_id,
		                            ),
		                            remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                            ),
	                            'move-menu_item'
                            );
                            ?>" class="item-move-down"><abbr title="<?php _e('Move down', 'tn'); ?>">&#8595;</abbr></a>
                        </span>
                        <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php _e('Edit Menu Item','tn'); ?>" href="<?php
                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                        ?>"><?php _e( 'Edit Menu Item' , 'tn'); ?></a>
                    </span>
			</dt>
		</dl>

		<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
			<?php if( 'custom' == $item->type ) : ?>
				<p class="field-url description description-wide">
					<label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'URL' , 'tn'); ?><br />
						<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_url( $item->url ); ?>" />
					</label>
				</p>
			<?php endif; ?>
			<p class="description description-thin">
				<label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Navigation Label' , 'tn'); ?><br />
					<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
				</label>
			</p>
			<p class="description description-thin">
				<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Title Attribute' , 'tn' ); ?><br />
					<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
				</label>
			</p>
			<p class="field-link-target description">
				<label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
					<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
					<?php _e( 'Open link in a new window/tab' , 'tn'); ?>
				</label>
			</p>
			<p class="field-css-classes description description-thin">
				<label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'CSS Classes (optional)' , 'tn'); ?><br />
					<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
				</label>
			</p>
			<p class="field-xfn description description-thin">
				<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Link Relationship (XFN)' , 'tn'); ?><br />
					<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
				</label>
			</p>
			<p class="field-tnmegamenu description description-thin" style="margin-top:10px">
				<?php if ($depth == 0 && (($item->object == 'category') || ($item->object == 'custom'))) { ?>
					<label for="edit-menu-item-tnmegamenu-<?php echo esc_attr($item_id); ?>"><?php _e('Mega Menu: ','tn'); ?></label>
					<input type="checkbox" id="edit-menu-item-tnmegamenu-<?php echo esc_attr($item_id); ?>" name="menu-item-tnmegamenu[<?php echo esc_attr($item_id); ?>]" value="1" <?php checked( $tn_item_megamenu,1 ); ?> />
				<?php } ?>
			</p>
			<p class="field-description description description-wide">
				<label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Description' , 'tn'); ?><br />
					<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]">
						<?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
					<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.' , 'tn'); ?></span>
				</label>
			</p>
			<p class="field-move hide-if-no-js description description-wide">
				<label>
					<span><?php _e( 'Move' , 'tn'); ?></span>
					<a href="#" class="menus-move-up"><?php _e( 'Up one' , 'tn'); ?></a>
					<a href="#" class="menus-move-down"><?php _e( 'Down one' , 'tn'); ?></a>
					<a href="#" class="menus-move-left"></a>
					<a href="#" class="menus-move-right"></a>
					<a href="#" class="menus-move-top"><?php _e( 'To the top' , 'tn'); ?></a>
				</label>
			</p>

			<div class="menu-item-actions description-wide submitbox">
				<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
					<p class="link-to-original">
						<?php printf( __('Original: %s' , 'tn'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
					</p>
				<?php endif; ?>
				<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
				echo wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'delete-menu-item',
							'menu-item' => $item_id,
						),
						admin_url( 'nav-menus.php' )
					),
					'delete-menu_item_' . esc_attr($item_id)
				); ?>"><?php _e( 'Remove' , 'tn'); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
				?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php _e('Cancel' , 'tn'); ?></a>
			</div>

			<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
			<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
			<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
			<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
			<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
			<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
		</div><!-- .menu-item-settings-->
		<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}


if ( ! function_exists( 'tn_megamenu_walker' ) ) {
	function tn_megamenu_walker($walker) {
		if ( $walker === 'Walker_Nav_Menu_Edit' ) {
			$walker = 'tn_walker_backend';
		}
		return $walker;
	}
}
add_filter( 'wp_edit_nav_menu_walker', 'tn_megamenu_walker');

if ( ! function_exists( 'tn_megamenu_walker_save' ) ) {
	function tn_megamenu_walker_save($menu_id, $menu_item_db_id) {

		if  (isset($_POST['menu-item-tnmegamenu'][$menu_item_db_id])) {
			update_post_meta( $menu_item_db_id, '_menu_item_tnmegamenu', $_POST['menu-item-tnmegamenu'][$menu_item_db_id]);
		} else {
			update_post_meta( $menu_item_db_id, '_menu_item_tnmegamenu', '0');
		}
	}
}
add_action( 'wp_update_nav_menu_item', 'tn_megamenu_walker_save', 10, 2 );

if ( ! function_exists( 'tn_megamenu_walker_loader' ) ) {
	function tn_megamenu_walker_loader($menu_item) {
		$menu_item->tnmegamenu = get_post_meta($menu_item->ID, '_menu_item_tnmegamenu', true);
		return $menu_item;
	}
}
add_filter( 'wp_setup_nav_menu_item', 'tn_megamenu_walker_loader' );

if(!function_exists('tn_category_nav_class')){
    function tn_category_nav_class( $classes, $item ){
        if( 'category' == $item->object ){
            $classes[] = 'tn-menu-category-' . $item->object_id;
        }
        return $classes;
    }
}

add_filter( 'nav_menu_css_class', 'tn_category_nav_class', 10, 2 );
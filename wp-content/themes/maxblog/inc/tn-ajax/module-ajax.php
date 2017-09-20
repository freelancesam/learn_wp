<?php
add_action('wp_ajax_nopriv_tn_ajax_module', 'tn_ajax_module');
add_action('wp_ajax_tn_ajax_module', 'tn_ajax_module');
if (!function_exists('tn_ajax_module')) {
    function tn_ajax_module()
    {

        //get POST
        if (!empty($_POST['tn_query'])) {
            $tn_query = ($_POST['tn_query']);
        } else {
            $tn_query = '';
        }

        if (!empty($_POST['tn_module_id'])) {
            $tn_module_id = $_POST['tn_module_id'];
        } else {
            $tn_module_id = '';
        }

        if (!empty($_POST['tn_module_name'])) {
            $tn_module_name = $_POST['tn_module_name'];
        } else {
            $tn_module_name = '';
        }

        if (!empty($_POST['tn_current_page'])) {
            $tn_current_page = $_POST['tn_current_page'];
        } else {
            $tn_current_page = 1;
        }

        if (!empty($_POST['tn_options'])) {
            $options = $_POST['tn_options'];
        } else {
            $options = '';
        }

        //get custom query
        $query_data = tn_custom_query($tn_query, $tn_current_page);

        //load module as string

        $str = tn_read_module($query_data->posts, $tn_module_name, $options);

        //hide next prev button
        $hide_prev = false;
        $hide_next = false;
        if ($tn_current_page == 1) {
            $hide_prev = true;
        }

        if ($tn_current_page >= $query_data->max_num_pages) {
            $hide_next = true;
        }
        $data = array(
            'data_response' => $str,
            'module_id' => $tn_module_id,
            'hide_prev' => $hide_prev,
            'hide_next' => $hide_next
        );
        //response data
        die(json_encode($data));
    }
}

//ajax search
add_action('wp_ajax_nopriv_tn_ajax_search', 'tn_ajax_search');
add_action('wp_ajax_tn_ajax_search', 'tn_ajax_search');
if (!function_exists('tn_ajax_search')) {
    function tn_ajax_search()
    {
        $str = '';
        if (!empty($_POST['s'])) {
            $s = sanitize_text_field(strip_tags($_POST['s']));
        } else {
            $s = '';
        }

        $search_data = tn_query_search($s)->posts;
        $options['style'] = 'style1';
        if (!empty($search_data)) {
            if (count($search_data) > 5) $search_data = array_splice($search_data, 5);
            $str .= tn_modulePost($search_data, $options);
        } else $str .= __('<div class="ajax-not-found">not found</div>', 'tn');
        $data = array(
            'content' => $str,
        );
        die(json_encode($data));
    }
}

//read module
if (!function_exists('tn_read_module')) {
    function tn_read_module($posts, $module_name, $options)
    {
        $str = '';
        switch ($module_name) {
            case 'module1' :
                $str = tn_module1($posts, $options);
                break;
            case 'module2' :
                $str = tn_module2($posts,$options);
                break;
            case 'module3' :
                $str = tn_module3($posts,$options);
                break;
            case 'module4' :
               $str = tn_module4($posts,$options);
                break;
            case 'module6' :
                $str = tn_module6($posts,$options);
            break;
            case 'module7' :
                $str = tn_module7($posts,$options);
                break;
            case 'module8' :
                $str = tn_module8($posts,$options);
                break;
            case 'moduleP' :
                $str = tn_modulePost($posts,$options);
                break;
            case 'moduleI' :
                $str = tn_moduleImage($posts,$options);
                break;
        }

        return $str;
    }
}

add_action('wp_enqueue_scripts', create_function('', 'wp_enqueue_script("tn-module-ajax", get_template_directory_uri() . "/inc/tn-ajax/js/module-ajax.js", array("jquery"), false, true);'));
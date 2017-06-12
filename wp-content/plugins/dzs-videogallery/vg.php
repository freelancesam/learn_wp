<?php
/*
  Plugin Name: DZS Video Gallery
  Plugin URI: http://digitalzoomstudio.net/
  Description: Creates and manages cool video galleries. Has a admin panel and tons of options and skins.
  Version: 9.20
  Author: Digital Zoom Studio
  Author URI: http://digitalzoomstudio.net/ 
 */



include_once(dirname(__FILE__).'/dzs_functions.php');
if(!class_exists('DZSVideoGallery')){
    include_once(dirname(__FILE__).'/class-dzsvg.php');
}


define("DZSVG_VERSION", "9.20");

$dzsvg = new DZSVideoGallery();


require_once('widget.php');


if(isset($_GET['dzsvg_show_generator_export_slider']) && $_GET['dzsvg_show_generator_export_slider']=='on'){
    echo $dzsvg->show_generator_export_slider();
    die();
}
<?php

class DZSVideoGallery {

    public $thepath;
    public $base_path;
    public $base_url;
    public $slider_index = 0;
    public $sliders_index = 0;
    public $index_players = 0;
    public $cats_index = 0;
    public $the_shortcode = 'videogallery';
    public $capability_user = 'read';
    public $capability_admin = 'manage_options';
    public $dbitemsname = 'zsvg_items';
    public $dbvpconfigsname = 'zsvg_vpconfigs';
    public $dboptionsname = 'zsvg_options';
    public $dbdcname = 'zsvg_options_dc';
    public $dbs = array();
    public $dbdbsname = 'zsvg_dbs';
    public $currDb = '';
    public $currSlider = '';
    public $mainitems;

    public $arr_api_errors = array();

    public $mainoptions;
    public $mainoptions_dc;
    public $mainoptions_dc_aurora;
    public $mainvpconfigs;
    public $mainoptions_default;
    public $pluginmode = "plugin";
    public $alwaysembed = "on";
    public $httpprotocol = 'https';
    public $adminpagename = 'dzsvg_menu';
    public $adminpagename_configs = 'dzsvg-vpc';
    public $adminpagename_designercenter = 'dzsvg-dc';
    public $adminpagename_mainoptions = 'dzsvg-mo';
    public $adminpagename_autoupdater = 'dzsvg-autoupdater';
    public $dbname_dc_aurora = 'dzsvg_options_dc';
    private $usecaching = true;
    private $addons_dzsvp_activated = false;

    public $is_preview = false; // -- put this to true from the main file if in preview mode

    public $analytics_views = array(); // -- video title, views, date, country
    public $analytics_minutes = array(); // -- video title, seconds, date, country
    public $analytics_users = array(); // -- user id , video title, views, seconds
    public $analytics_ip_country_db = array(); // -- ip , country

    public $plugin_justactivated = false; // -- shows if the plugin has just been activated



    function __construct() {
        if ($this->pluginmode == 'theme') {
            $this->thepath = THEME_URL.'plugins/dzs-videogallery/';
        } else {
            $this->thepath = plugins_url('',__FILE__).'/';
        }



        $this->base_path = dirname(__FILE__).'/';
        $this->base_url = $this->thepath;

        $currDb = '';
        if (isset($_GET['dbname'])) {
            $this->currDb = $_GET['dbname'];
            $currDb = $_GET['dbname'];
        }


        if (isset($_GET['currslider'])) {
            $this->currSlider = $_GET['currslider'];
        } else {
            $this->currSlider = 0;
        }




        $this->dbs = get_option($this->dbdbsname);
        //$this->dbs = '';
        if ($this->dbs == '') {
            $this->dbs = array('main');
            update_option($this->dbdbsname,$this->dbs);
        }
        if (is_array($this->dbs) && !in_array($currDb,$this->dbs) && $currDb != 'main' && $currDb != '') {
            array_push($this->dbs,$currDb);
            update_option($this->dbdbsname,$this->dbs);
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($currDb != 'main' && $currDb != '') {
            $this->dbitemsname.='-'.$currDb;
        }

        $this->mainitems = get_option($this->dbitemsname);
        if ($this->mainitems == '') {
            $mainitems_default_ser = file_get_contents(dirname(__FILE__).'/sampledata/sample_items.txt');
            $this->mainitems = unserialize($mainitems_default_ser);
            update_option($this->dbitemsname,$this->mainitems);
        }

        $this->mainvpconfigs = get_option($this->dbvpconfigsname);
        //cho 'ceva'.is_array($this->mainvpconfigs);
        if ($this->mainvpconfigs == '' || (is_array($this->mainvpconfigs) && count($this->mainvpconfigs) == 0)) {
            //echo 'ceva';
            $this->mainvpconfigs = array();
            $aux = file_get_contents(dirname(__FILE__).'/sampledata/sample_vpconfigs.txt');
            $this->mainvpconfigs = unserialize($aux);
            //print_r($this->mainvpconfigs);
            //$this->mainitems = array();
            update_option($this->dbvpconfigsname,$this->mainvpconfigs);
        }
        $vpconfigsstr = '';
        foreach ($this->mainvpconfigs as $vpconfig) {
            //print_r($vpconfig);
            $vpconfigsstr .='<option value="'.$vpconfig['settings']['id'].'">'.$vpconfig['settings']['id'].'</option>';
        }




        $this->mainoptions = get_option($this->dboptionsname);

        $this->mainoptions_default =  array(
            'usewordpressuploader' => 'on',
            'embed_masonry' => 'on',
            'is_safebinding' => 'on',
            'disable_api_caching' => 'off',
            'disable_fontawesome' => 'off',
            'debug_mode' => 'off',
            'youtube_api_key' => 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4',
            'vimeo_api_user_id' => '',
            'vimeo_api_client_id' => '',
            'vimeo_api_client_secret' => '',
            'vimeo_api_access_token' => '',
            'vimeo_api_access_token_secret' => '',
            'always_embed' => 'off',
            'extra_css' => '',
            'use_external_uploaddir' => 'off',
            'admin_close_otheritems' => 'on',
            'admin_enable_for_users' => 'off',
            'force_file_get_contents' => 'off',
            'vimeo_thumb_quality' => 'low',
            'include_featured_gallery_meta' => 'off',
            'replace_jwplayer' => 'off',
            'replace_wpvideo' => 'off',
            'enable_video_showcase' => 'off',
            'enable_auto_backup' => 'on',
            'tinymce_enable_preview_shortcodes' => 'on',
            'settings_trigger_resize' => 'off',
            'settings_limit_notice_dismissed' => 'off',
            'translate_skipad' => 'Skip Ad',
            'dzsvg_purchase_code' => '',
            'dzsvg_purchase_code_binded' => 'off',
            'dzsvp_video_config' => 'default',
            'dzsvp_enable_likes' => 'on',
            'dzsvp_enable_ratings' => 'off',
            'dzsvp_enable_viewcount' => 'off',
            'dzsvp_enable_likescount' => 'off',
            'dzsvp_enable_ratingscount' => 'off',
            'dzsvp_enable_visitorupload' => 'off',
            'dzsvp_tab_share_content' => '<span class="share-icon-active"><iframe src="//www.facebook.com/plugins/like.php?href={{currurl}}&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=569360426428348" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></span>
<span class="share-icon-active"><div class="g-plusone" data-size="medium"></div></span>
<span class="share-icon-active"><a href="https://twitter.com/share" class="twitter-share-button" data-via="ZoomItFlash">Tweet</a></span><h5>Embed</h5><div class="dzsvp-code">{{embedcode}}</div>
<script type="text/javascript">
  (function() {
    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
    po.src = "https://apis.google.com/js/platform.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
  })();
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>',
            'dzsvp_enable_tab_playlist' => 'on',
            'dzsvp_enable_facebooklogin' => 'off',
            'dzsvp_facebook_loginappid' => '',
            'dzsvp_facebook_loginsecret' => '',
            'dzsvp_page_upload' => '',
            'dzsvp_categories_rewrite' => 'video_categories',
            'dzsvp_tags_rewrite' => 'video_tags',
            'analytics_enable' => 'off',
            'analytics_enable_location' => 'off',
            'analytics_enable_user_track' => 'off',
            'analytics_galleries' => '',
        );


        //==== default opts / inject into db
        if ($this->mainoptions == '') {
            $this->mainoptions = $this->mainoptions_default;
            update_option($this->dboptionsname,$this->mainoptions);
        }

//        print_r($defaultOpts); print_r($this->mainoptions);
        $this->mainoptions = array_merge($this->mainoptions_default,$this->mainoptions);
        //print_r($this->mainoptions);
        //===translation stuff
        load_plugin_textdomain('dzsvg',false,basename(dirname(__FILE__)).'/languages');





        $def_options_dc = array(
            'background' => '#111111',
            'controls_background' => '#333333',
            'scrub_background' => '#333333',
            'scrub_buffer' => '#555555',
            'controls_color' => '#aaaaaa',
            'controls_hover_color' => '#dddddd',
            'controls_highlight_color' => '#db4343',
            'thumbs_bg' => '#333333',
            'thumbs_active_bg' => '#777777',
            'thumbs_text_color' => '#eeeeee',
            'timetext_curr_color' => '#ffffff',
            'thumbnail_image_width' => '',
            'thumbnail_image_height' => '',
        );
        $this->mainoptions_dc = get_option($this->dbdcname);

        //==== default opts / inject into db
        if ($this->mainoptions_dc == '') {
            $this->mainoptions_dc = $def_options_dc;
            update_option($this->dbdcname,$this->mainoptions_dc);
        }

        $def_options_dc = array(
            'background' => '#111111',
            'controls_background' => '#333333',
            'scrub_background' => '#333333',
            'scrub_buffer' => '#555555',
            'scrub_progress' => '#fdd500',
            'controls_color' => '#aaaaaa',
            'controls_hover_color' => '#dddddd',
            'controls_highlight_color' => '#db4343',
        );
        $this->mainoptions_dc_aurora = get_option($this->dbname_dc_aurora);

        //==== default opts / inject into db
        if ($this->mainoptions_dc_aurora == '') {
            $this->mainoptions_dc_aurora = array();
        }
        $this->mainoptions_dc_aurora = array_merge($def_options_dc, $this->mainoptions_dc_aurora);



        $this->post_options();



        if (isset($_POST['deleteslider'])) {
            //print_r($this->mainitems);
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
                unset($this->mainitems[$_POST['deleteslider']]);
                $this->mainitems = array_values($this->mainitems);
                $this->currSlider = 0;
                //print_r($this->mainitems);
                update_option($this->dbitemsname,$this->mainitems);
            }


            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs) {
                unset($this->mainvpconfigs[$_POST['deleteslider']]);
                $this->mainvpconfigs = array_values($this->mainvpconfigs);
                $this->currSlider = 0;
                //print_r($this->mainitems);
                update_option($this->dbvpconfigsname,$this->mainvpconfigs);
            }
        }

        if (isset($_POST['dzsvg_duplicateslider'])) {
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
                $aux = ($this->mainitems[$_POST['dzsvg_duplicateslider']]);
                array_push($this->mainitems,$aux);
                $this->mainitems = array_values($this->mainitems);
                $this->currSlider = count($this->mainitems) - 1;
                update_option($this->dbitemsname,$this->mainitems);
            }
        }

        //echo get_admin_url('', 'options-general.php?page=' . $this->adminpagename) . dzs_curr_url();
        //echo $newurl;

        $uploadbtnstring = '<button class="button-secondary action upload_file">Upload</button>';



        if ($this->mainoptions['usewordpressuploader'] != 'on') {
            $uploadbtnstring = '<div class="dzs-upload">
<form name="upload" action="#" method="POST" enctype="multipart/form-data">
<input type="button" value="Upload" class="btn_upl"/>
<input type="file" name="file_field" class="file_field"/>
<input type="submit" class="btn_submit"/>
</form>
</div>
<div class="feedback"></div>';
        }

        ///==== important: settings must have the class mainsetting
        $this->sliderstructure = '<div class="slider-con" style="display:none;">
        <div class="setting type_all">
            <div class="setting-label">'.__('Select Feed Mode','dzsvg').'</div>
                <div class="main-feed-chooser select-hidden-metastyle">
                <select class="textinput mainsetting" name="0-settings-feedfrom">
                    <option value="normal">'.__('Normal','dzsvg').'</option>
                    <option value="ytuserchannel">'.__('Youtube User Channel','dzsvg').'</option>
                    <option value="ytplaylist">'.__('YouTube Playlist','dzsvg').'</option>
                    <option value="ytkeywords">'.__('YouTube Keywords','dzsvg').'</option>
                    <option value="vmuserchannel">'.__('Vimeo User Channel','dzsvg').'</option>
                    <option value="vmchannel">'.__('Vimeo Channel','dzsvg').'</option>
                    <option value="vmalbum">'.__('Vimeo Album','dzsvg').'</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Normal','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed from custom items you set below.','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Youtube User Channel','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__(' Feed videos from your YouTube User Channel.','dzsvg').'
                   
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('YouTube Playlist','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from the YouTube Playlist you create on their site. Just input the Playlist ID below.','dzsvg').'
                    
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('YouTube Keywords','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos by searching for keywords ie. <strong>funny cat</strong>','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo User Channel','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from your Vimeo User channel.','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo Channel','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from a Vimeo Channel.','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo Album','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from a Vimeo Album.','dzsvg').'
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-con">
        <h4>'.__('General Options','dzsvg').'</h4>
        <div class="setting type_all">
            <div class="setting-label">'.__('ID','dzsvg').'</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">'.__('Choose an unique id. Do not use spaces, do not use special characters.','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Force Height','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-height" value="300"/>
        </div>
        <div class="setting styleme">
            <div class="setting-label">'.__('Display Mode','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-displaymode">
                <option>normal</option>
                <option>wall</option>
                <option>rotator</option>
                <option>rotator3d</option>
                <option>alternatemenu</option>
                <option>alternatewall</option>
            </select>
            <div class="sidenote">'.__('<strong>alternatewall</strong> and <strong>alternatemenu</strong> are deprecated.','dzsvg').'</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">'.__('Video Gallery Skin','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_html5vg">
                <option>skin_default</option>
                <option>skin_navtransparent</option>
                <option>skin_pro</option>
                <option>skin_boxy</option>
                <option>skin_custom</option>
            </select>
            <div class="sidenote">'.__('Skin Custom can be modified via Designer Center.','dzsvg').'</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Video Player Configuration','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-vpconfig">
                <option value="default">'.__('default','dzsvg').'</option>
                '.$vpconfigsstr.'
            </select>
            <div class="sidenote" style="">'.__('setup these inside the <strong>Video Player Configs</strong> admin','dzsvg').'</div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Navigation Style','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-nav_type">
                <option>thumbs</option>
                <option>thumbsandarrows</option>
                <option>scroller</option>
                <option>outer</option>
                <option>none</option>
            </select>
            <div class="sidenote">'.__('Choose a navigation style for the normal display mode.','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Menu Position','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-menuposition">
                <option>right</option>
                <option>bottom</option>
                <option>left</option>
                <option>top</option>
                <option>none</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Autoplay','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Autoplay Next','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplaynext">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Cue First Video','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-cueFirstVideo">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
            </select>
            <div class="sidenote">'.__('Choose if the video should load at start or it should activate on click ( if a <strong>Cover Image</strong> is set ).','dzsvg').'</div>
            
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Randomize / Shuffle Elements','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-randomize">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Order','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-order">
                <option value="ASC">'.__('ascending','dzsvg').'</option>
                <option value="DESC">'.__('descending','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Play Order','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-playorder">
                <option value="ASC">'.__('normal','dzsvg').'</option>
                <option value="DESC">'.__('reverse','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('set to reverse for example to play the latest episode in a series first ... or for RTL configurations','dzsvg').'</div>
        </div>
        

        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Underneath Description','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-enableunderneathdescription">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('add a title and description holder underneath the gallery','dzsvg').'</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Search Field','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_search_field">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('enable a search field inside the gallery','dzsvg').'</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Linking','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_enable_linking">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('enable the possibility for the gallery to change the current link depending on the video played, this makes it easy to go to a current video based only on link','dzsvg').'</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">'.__('Autoplay Ad','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay_ad">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('autoplay the ad before a video or not - note that if the video autoplay then the ad will autoplay too before','dzsvg').'</div>
        </div>


        <hr/>
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Social Options','dzsvg').'</div>
<div class="toggle-content">

        <div class="setting type_all">
            <div class="setting-label">'.__('Share Button','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-sharebutton">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting_label">'.__('Facebook Link','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-facebooklink" value=""/>
            <div class="sidenote" style="">'.__('input here a full link to your facebook page ie. <strong><a href="https://www.facebook.com/digitalzoomstudio">https://www.facebook.com/digitalzoomstudio</a></strong> or input "<strong>{{share}}</strong>" and the button will share the current playing video','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting_label">'.__('Twitter Link','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-twitterlink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">'.__('Google Plus Link','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-googlepluslink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">'.__('Extra Social HTML','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-social_extracode" value=""/>
            <div class="sidenote" style="">'.__('you can have here some extra social icons','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Embed Button','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-embedbutton">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
        </div>
</div>
</div>
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Design Options','dzsvg').'</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">'.__('Force Width','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-width" value="100%"/>
            <div class="sidenote">'.__('Leave "100%" for responsive mode. ','dzsvg').'</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">'.__('Resize Video Proportionally','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-set_responsive_ratio_to_detect">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="sidenote">'.__('Settings this to "on" will make an attempt to remove the black bars plus resizing the video proportionally for mobiles.','dzsvg').'</div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Force Video Height','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-forcevideoheight" value=""/>
        <div class="sidenote">'.__('Leave this blank if you want the video to autoresize. .','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Design Menu Item Width','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmiw" value="275"/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Design Menu Item Height','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmih" value="76"/>
            <div class="sidenote" style="">'.__('these also control the width and height for wall items ( for auto height leave blank here, on wall mode )','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Design Menu Item Space','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmis" value="0"/>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Thumbnail Extra Classes','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-thumb_extraclass" value=""/>
            <div class="sidenote" style="">'.__('add a special class to the thumbnail like <strong>thumb-round</strong> for making the thumbnails rounded','dzsvg').'</div>
        </div>


        <div class="setting">
            <div class="setting_label">'.__('Cover Image','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-coverImage" value=""/>'.$uploadbtnstring.'
                <div class="sidenote">A image that appears while the video is cued / not played</div>
        </div>

        <div class="setting">
            <div class="setting_label">'.__('Logo','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logo" value=""/>'.$uploadbtnstring.'
        </div>
        <div class="setting">
            <div class="setting_label">'.__('Logo Link','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logoLink" value=""/>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Navigation Space','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-nav_space" value="0"/>
            <div class="sidenote" style="">'.__('space between navigation and video container','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Disable Menu Title','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_title">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Disable Video Title','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_video_title">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Disable Menu Description','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_menu_description">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Easing on Menu','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_navigationuseeasing">
                <option>off</option>
                <option>on</option>
            </select>
                <div class="sidenote" style="">'.__('for navigation type <strong>thumbs</strong> - use a easing on mouse tracking ','dzsvg').'</div>
        </div>
        

        <div class="setting type_all">
            <div class="setting-label">'.__('Laptop Skin','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-laptopskin">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
                <div class="sidenote" style="">'.__('apply a laptop container to the gallery','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Transition','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-html5transition">
                <option>slideup</option>
                <option>fade</option>
            </select>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Right to Left','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-rtl">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
            <div class="sidenote" style="">'.__('enable RTL','dzsvg').'</div>
        </div>



        <div class="setting">
            <div class="setting_label">'.__('Extra Classes','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-extra_classes" value=""/>
            <div class="sidenote" style="">'.__('some extra css classes that you can use to stylize this gallery','dzsvg').'</div>
        </div>



        <div class="setting">
            <div class="setting-label">'.__('Background','dzsvg').'</div>
            <input type="text" class="textinput mainsetting with-colorpicker" name="0-settings-bgcolor" value="#111111"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Shadow','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-shadow">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
            </select>
        </div>


<br>
        <h5>'.__('Mode Wall Settings').'</h5>

        <div class="setting type_all">
            <div class="setting-label">'.__('Layout for Mode Wall','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-mode_wall_layout">
                <option value="none">'.__('None','dzsvg').'</option>
                <option value="layout-3-cols-15-margin">'.__('3 columns','dzsvg').'</option>
                <option value="layout-4-cols-10-margin">'.__('4 columns','dzsvg').'</option>
            </select>
                <div class="sidenote" style="">'.__('the layout for the wall mode. using none will use the Design Menu Item Width and Design Menu Item Height for the item dimensions','dzsvg').'</div>
        </div>


        <br>
</div>
</div>


<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Description Options','dzsvg').'</div>
<div class="toggle-content">
        <div class="sidenote" style="font-size:14px;">'.__('some options regarding YouTube feed mode - playlist / user channel / ','dzsvg').'</div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Max Description Length','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-maxlen_desc" value="100"/>
            <div class="sidenote" style="">'.__('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ' ,'dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Strip HTML Tags','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-striptags">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
                </select>
            <div class="sidenote" style="">'.__('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text ' ,'dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Repair HTML Markup','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-try_to_close_unclosed_tags">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
                </select>
            <div class="sidenote" style="">'.__('video descriptions will be retrieved as html rich content, some may be broken after shortage. attempt to repair this by setting this to <strong>on</strong>' ,'dzsvg').'</div>
        </div>';




        $lab = '0-settings-desc_different_settings_for_aside';
//                                echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
        $this->sliderstructure.='<div class="setting">
                                    <h4 class="setting-label">'. __('Aside Navigation has Different Settings?','dzsapp').'</h4>
                                    <div class="dzscheckbox skin-nova">
                                        '.DZSHelpers::generate_input_checkbox($lab,array('id' => $lab,'class' => 'mainsetting', 'val' => 'on','seekval' => '')).'
                                        <label for="'.$lab.'"></label>
                                    </div>
                                    <div class="sidenote">'.__('allow creating new accounts').'</div>
                                </div>';








        $this->sliderstructure.='



<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">'.__('Max Description Length','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-desc_aside_maxlen_desc" value="100"/>
            <div class="sidenote" style="">'.__('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ' ,'dzsvg').'</div>
</div>
<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">'.__('Strip HTML Tags','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-desc_aside_striptags">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
                </select>
            <div class="sidenote" style="">'.__('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text ' ,'dzsvg').'</div>
</div>
<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">'.__('Repair HTML Markup','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-desc_aside_try_to_close_unclosed_tags">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
                </select>
            <div class="sidenote" style="">'.__('video descriptions will be retrieved as html rich content, some may be broken after shortage. attempt to repair this by setting this to <strong>on</strong>' ,'dzsvg').'</div>
</div>





</div>
</div>

<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('RTMP Options','dzsvg').'</div>
<div class="toggle-content">
        <div class="sidenote" style="font-size:14px;">'.__('if you have a rtmp server and want to stream, this is the solution','dzsvg').'</div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Stream Server','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-rtmp_streamserver" value=""/>
        </div>
</div>
</div>
        

<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Outer Parts','dzsvg').'</div>
<div class="toggle-content">
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Second Con','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_secondcon">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
                </select>
                <div class="sidenote" style="">'.__('enable linking to a slider with titles and descriptions as seen in the demos. to insert the container in your page use this shortcode [dzsvg_secondcon id="theidofthegallery" extraclasses=""]','dzsvg').'</div>
            
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Outer Navigation','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_outernav">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
                </select>
                <div class="sidenote" style="">'.__('enable linking to a outside navigation [dzsvg_outernav id="theidofthegallery" skin="oasis" extraclasses="" layout="layout-one-third" thumbs_per_page="9" ]','dzsvg').'</div>
            
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Outer Navigation, Show Video Author','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_outernav_video_author">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
                </select>
                <div class="sidenote" style="">'.__('show the video author for YouTube channels and playlists','dzsvg').'</div>
            
        </div>


</div>
</div>




<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Misc Options','dzsvg').'</div>
<div class="toggle-content">

        <div class="setting type_all">
            <div class="setting-label">'.__('Ids Point to Source','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-ids_point_to_source">
                <option value="off">'.__('off','dzsvg').'</option>
                <option value="on">'.__('on','dzsvg').'</option>
                </select>
                <div class="sidenote" style="">'.__('the id of the video players can point to the source file used','dzsvg').'</div>

        </div>



</div>
</div>




        
        </div><!--end settings con-->
        <div class="modes-con">
        
        <div class="setting mode_ytuserchannel">
            <div class="setting_label">'.__('YouTube User','dzsvg').'</div>
            <input type="text" class="short textinput mainsetting" name="0-settings-youtubefeed_user" value=""/>
        </div>
	<div class="setting mode_ytplaylist">
            <div class="setting_label">'.__('YouTube Playlist','dzsvg').'
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">'.__('You need to set the playlist ID there not the playlist Name. For example for this playlist http:'.'/'.''.'/'.'www.youtube.com/my_playlists?p=PL08BACDB761A0C52A the id is 08BACDB761A0C52A. Remember that if you have the characters PL at the beggining of the ID they should not be included here.','dzsvg').'</div>
                </div>
</div>
                
                <input type="text" class="short textinput mainsetting" name="0-settings-ytplaylist_source" value=""/>
        </div>
	<div class="setting mode_ytkeywords">
            <div class="setting_label">'.__('YouTube Keywords','dzsvg').'
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">'.__('','dzsvg').'</div>
                </div>
                </div>

                <input type="text" class="short textinput mainsetting" name="0-settings-ytkeywords_source" value=""/>
        </div>
        <div class="setting type_all mode_ytuserchannel mode_ytplaylist mode_ytkeywords">
            <div class="setting-label">'.__('YouTube Max Videos','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-youtubefeed_maxvideos" value="50"/>
            <div class="sidenote">'.__('input a limit of videos here ( can be a maximum of 50 ) if you have more then 50 videos in your stream, just input "<strong>all</strong>" in this field ( without quotes ) ','dzsvg').'</div>
        </div>
        <div class="setting type_all mode_vmuserchannel">
            <div class="setting_label">'.__('Vimeo User ID','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_user" value=""/>
            <div class="sidenote">'.__('be sure this to be your user id . For example mine is user5137664 even if my name is DIgitalZoomStudio – https://vimeo.com/user5137664 - you get that by checking your profile link.','dzsvg').'</div>
        </div>
        
        <div class="setting type_all mode_vmchannel">
            <div class="setting_label">'.__('Vimeo Channel ID','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_channel" value=""/>
            <div class="sidenote">'.__('be sure all videos are allowed to be embedded . Channel example for  – https://vimeo.com/channels/636900 - is <strong>636900</strong>.','dzsvg').'</div>
        </div>
        
        <div class="setting type_all mode_vmalbum">
            <div class="setting_label">'.__('Vimeo Album ID','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_vmalbum" value=""/>
            <div class="sidenote">'.__('be sure all videos are allowed to be embedded . Channel example for  – https://vimeo.com/album/2633720 - is <strong>2633720</strong>.','dzsvg').'</div>
        </div>


        <div class="setting type_all mode_vmuserchannel mode_vmchannel mode_vmalbum">
            <div class="setting-label">'.__('Vimeo Max Videos','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_maxvideos" value="25"/>
            <div class="sidenote">'.__('input a limit of videos here - note that if you have not set a Vimeo API oAuth login <a href="admin.php?page='.$this->adminpagename_mainoptions.'">here</a>, the limit will be 20 videos, regardless','dzsvg').'</div>
        </div>
        
</div>
        <div class="master-items-con mode_normal">
        <div class="items-con "></div>
        <a href="#" class="add-item"></a>
        </div><!--end master-items-con-->
        <div class="clear"></div>
        </div>';
        $this->itemstructure = '<div class="item-con">
            <div class="item-delete">x</div>
            <div class="item-duplicate"></div>
        <div class="item-preview" style="">
        </div>
        <div class="item-settings-con">
        <div class="setting type_all">
            <h4 class="non-underline"><span class="underline">'.__('Type','dzsvg').'*</span>&nbsp;&nbsp;&nbsp;<span class="sidenote">select one from below</span></h4> 
            
            <div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
                <select class="textinput item-type" data-label="type" name="0-0-type">
            <option>youtube</option>
            <option>video</option>
            <option>vimeo</option>
            <option>audio</option>
            <option>image</option>
            <option>link</option>
            <option>rtmp</option>
            <option>dash</option>
            <option>inline</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option">
                    <div class="an-title">
                    '.__('YouTube','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Input in the <strong>Source</strong> field below the youtube video ID. You can find the id contained in the link to 
                    the video - http://www.youtube.com/watch?v=<strong>ZdETx2j6bdQ</strong> ( for example )','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Video','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Stream videos your own hosted videos. You just have to include two formats of the video you are streaming. In the <strong>Source</strong>
                    field you need to include the path to your mp4 formatted video. And in the OGG field there should be the ogg / ogv path, this is not mandatory, 
                    but recommended.','dzsvg').' <a href="'.$this->thepath.'readme/index.html#handbrake" target="_blank" class="">Documentation here</a>.
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo Video','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Insert in the <strong>Source</strong> field the ID of the Vimeo video you want to stream. You can identify the ID easy from the link of the video,
                     for example, here see the bolded part','dzsvg').' - http://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Audio File','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('You need a MP3 format of your audio file and an OGG format. You put their paths in the Source and Html5 Ogg Format fields','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Image File','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Just put in the <strong>Source</strong> field the path to your image.','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('A link','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Link where the visitor should go when clicking the menu item.','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('RTMP File','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('For advanced users - if you have a rtmp server - input the server in the <strong>Stream Server</strong> from the left and input here in the <strong>Source</strong> the location of the file on the server..','dzsvg').'
                    </div>
                    </div>

                    <div class="an-option">
                    <div class="an-title">
                    '.__('Dash Mpeg Stream','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Input the link to the manifest file in the <strong>Source</strong> field. To use dash, ofcourse, you need some kind of streaming server like Wowza Streaming Server ','dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Inline Content','dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Insert in the <strong>Source</strong> field custom content ( ie. embed from a custom site like dailymotion).','dzsvg').'
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Source','dzsvg').'*
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">'.__('Below you will enter your video address. If it is a video from YouTube or Vimeo you just need to enter 
                the id of the video in the "Video:" field. The ID is the bolded part http://www.youtube.com/watch?v=<strong>j_w4Bi0sq_w</strong>. 
                If it is a local video you just need to write its location there or upload it through the Upload button ( .mp4 / .flv format ).','dzsvg').'
                    </div>
                </div>
            </div>
<textarea class="textinput main-source type_all" data-label="source" name="0-0-source" style="width:320px; height:29px;">Hv7Jxi_wMq4</textarea>'.$uploadbtnstring.'
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">HTML5 OGG '.__('Format','dzsvg').'</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-html5sourceogg" value=""/>'.$uploadbtnstring.'
            <div class="sidenote">'.__('Optional ogg / ogv file','dzsvg').' / '.__('Only for the Video or Audio type','dzsvg').'</div>
        </div>


        <div class="setting type_link">
            <div class="setting-label">'.__('Link Target','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-link_target">
                <option value="_self">'.__('Open Same Window','dzsvg').'</option>
                <option value="_blank">'.__('Open New Window','dzsvg').'</option>
            </select>
        </div>
        <br>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Appearance Settings','dzsvg').'</div>
<div class="toggle-content">
        <div class="setting type_all floatleft220 ">
            <div class="setting-label">'.__('Thumbnail','dzsvg').'</div>
            <input type="text" class="textinput main-thumb" name="0-0-thethumb"/>'.$uploadbtnstring.'<br/>
                <button class="refresh-main-thumb button-secondary">'.__('Refresh Thumbnail','dzsvg').'</button>
                <div class="sidenote">'.__('Refresh the thumbnail if its a vimeo or youtube video','dzsvg').'</div>
        </div>
        <div class="setting type_all floatleft220 br1">
            <div class="setting-label">'.__('Menu Title','dzsvg').'</div>
            <input type="text" class="textinput" name="0-0-title"/>
        </div>
        <div class="setting type_all floatleft220">
            <div class="setting-label">'.__('Video Description','dzsvg').':</div>
            <textarea class="textinput" name="0-0-description"></textarea>
        </div>
        <div class="setting type_all floatleft220 br1">
            <div class="setting-label">'.__('Menu Description','dzsvg').'</div>
            <textarea class="textinput" name="0-0-menuDescription"></textarea>
        </div>
        <div class="clear"></div>

        <div class="setting type_all">
            <div class="setting-label">'.__('Preview Image','dzsvg').'</div>
            <input class="textinput upload-prev" name="0-0-audioimage" value=""/>'.$uploadbtnstring.'
            <div class="sidenote">'.__('will be used as the background image for audio type too','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Tags','dzsvg').'</div>
            <input class="textinput tageditor-prev" name="0-0-tags" value=""/><button class="button-secondary btn-tageditor">Tag Editor</button>
            <div class="sidenote">'.__('use the tag editor to generate tags at given times of the video','dzsvg').'</div>
        </div>
        

        <div class="setting type_all">
            <div class="setting-label">'.__('Subtitle File','dzsvg').'</div>
            <input class="textinput upload-prev" name="0-0-subtitle_file" value=""/>'.$uploadbtnstring.'
            <div class="sidenote">'.__('you can upload a srt file for optional captioning on the video - recommeded you rename the .srt file to .html format if you want to use the wordpress uploader ( security issues ) ','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Play From','dzsvg').'</div>
            <input class="textinput upload-prev" name="0-0-playfrom" value=""/>
            <div class="sidenote">'.__('you can input a number ( seconds ) for the initial play status. or just input "last" for the video to come of where it has last been left','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Responsive Ratio','dzsvg').'</div>
            <input class="textinput upload-prev" name="0-0-responsive_ratio" value=""/>
            <div class="sidenote">'.__('set a responsive ratio height/ratio 0.5 means that the player height will resize to 0.5 of the gallery width / or just set it to "detect" and it will autocalculate the ratios if it is a self hosted mp4','dzsvg').'</div>
        </div>
</div>
</div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Advertising Settings','dzsvg').'</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">'.__('Ad  Source','dzsvg').'</div>
            <div class="sidenote">'.__('If it is a video ad, input here the mp4 / m4v path ( or upload the video ) <br/>If it is a youtube ad, input here the youtube video id<br/>If it is a image ad, input here the image path ( or upload the image ) <br/>If it is a inline ad, input here the html content ( can load iframes too )
            format in the same folder','dzsvg').'</div>
            <input class="textinput upload-prev" name="0-0-adsource" value=""/>'.$uploadbtnstring.'
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Ad  Type','dzsvg').'</div>
            <select class="textinput item-type styleme type_all" name="0-0-adtype">
            <option>video</option>
            <option>youtube</option>
            <option>image</option>
            <option>inline</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Ad  Link','dzsvg').'</div>
            <input class="textinput" name="0-0-adlink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Skip Ad Button Delay','dzsvg').'</div>
            <input class="textinput" name="0-0-adskip_delay" value=""/>
            <div class="sidenote">'.__('You can have a skip ad button appear after a set number of seconds. ','dzsvg').'</div>
        </div>
        <div class="clear"></div>
</div>
</div>
</div><!--end item-settings-con-->
</div>';



        $this->videoplayerconfig = '<div class="slider-con" style="display:none;">
        
        <div class="settings-con">
        <h4>'.__('General Options','dzsvg').'</h4>
        <div class="setting type_all">
            <div class="setting-label">'.__('Config ID','dzsvg').'</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">'.__('Choose an unique id.','dzsvg').'</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">'.__('Video Player Skin','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_html5vp">
                <option>skin_aurora</option>
                <option>skin_default</option>
                <option>skin_white</option>
                <option>skin_pro</option>
                <option>skin_bigplay</option>
                <option>skin_reborn</option>
                <option>skin_avanti</option>
                <option>skin_custom</option>
                <option>skin_custom_aurora</option>
            </select>
            <div class="sidenote">'.__('Skin Custom can be modified via Designer Center.','dzsvg').'</div>
        </div>
        <hr/>
        <div class="setting styleme">
            <div class="setting-label">'.__('Video Overlay','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_video_overlay">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">'.__('an overlay over the video that you can press for pause / unpause','dzsvg').'</div>
        </div>
        

        <div class="setting styleme">
            <div class="setting-label">'.__('Disable Mouse Out Behaviour','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_disable_mouse_out">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">'.__('some skins hide the controls on mouse out, you can disable this.','dzsvg').'</div>
        </div>


        <div class="setting styleme">
            <div class="setting-label">'.__('Use the Custom Skin on iOS','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_ios_usecustomskin">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">'.__('overwrites the default ios ( ipad and iphone ) skin with the skin you chose in the Video Player Configuration','dzsvg').'</div>
        </div>

        <div class="setting ">
            <div class="setting-label">'.__('Send Google Analytics Event for Play','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-ga_enable_send_play">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">'.__('send the play event to google analytics to record gallery plays on your site / you need the google analytics wordpress plugin','dzsvg').'</div>
        </div>

        <div class="setting ">
            <div class="setting-label">'.__('Video End Displays the Last Frame','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_video_end_reset_time">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">'.__('available for the self hosted video type','dzsvg').'</div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Normal Controls Opacity','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityon" value="1"/>
            <div class="sidenote">'.__('Choose an opacity from 0 to 1','dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Roll Out Controls Opacity','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityout" value="1"/>
            <div class="sidenote">'.__('Choose an opacity from 0 to 1 for when the mouse is not on the video player','dzsvg').'</div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">'.__('Default Volume','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-defaultvolume" value=""/>
        </div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('YouTube Options','dzsvg').'</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">'.__('SD Quality','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_sdquality">
                <option>small</option>
                <option>medium</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('HD Quality','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_hdquality">
                <option>hd720</option>
                <option>hd1080</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Default Quality','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_defaultquality">
                <option value="hd">'.__('HD','dzsvg').'</option>
                <option value="sd">'.__('SD','dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Custom Skin','dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-yt_customskin">
                <option value="on">'.__('on','dzsvg').'</option>
                <option value="off">'.__('off','dzsvg').'</option>
            </select>
            <div class="sidenote">'.__('Choose if the custom skin you set in the Video Player Skin is how YouTube videos should show ( on )
                 or if the default YouTube skin should show ( off )','dzsvg').'</div>
        </div>
</div>
</div>
        

<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">'.__('Vimeo Options','dzsvg').'</div>
<div class="toggle-content">
        
                <div class="setting">
                    <div class="label">'.__('Vimeo Player Byline','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_byline" value="0"/>
                    <div class="sidenote">'.__('','dzsvg').'</div>
                </div>
                <div class="setting">
                    <div class="label">'.__('Vimeo Player Portrait','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_portrait" value="0"/>
                    <div class="sidenote">'.__('','dzsvg').'</div>
                </div>
                <div class="setting">
                    <div class="label">'.__('Vimeo Player Color','dzsvg').'</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_color" value=""/>
                    <div class="sidenote">'.__('input the color of controls in this format RRGGBB, ie. <strong>ffffff</strong> for white ','dzsvg').'</div>
                </div>
</div>
</div>
        
        </div><!--end settings con-->
        </div>';
        //print_r($this->mainitems);

        $this->check_posts();





        add_shortcode($this->the_shortcode,array($this,'show_shortcode'));
        add_shortcode('dzs_'.$this->the_shortcode,array($this,'show_shortcode'));
        add_shortcode('dzs_videoshowcase',array($this,'show_shortcode_showcase'));
        add_shortcode('videogallerycategories',array($this,'show_shortcode_cats'));
        add_shortcode('videogallerylightbox',array($this,'show_shortcode_lightbox'));
        add_shortcode('videogallerylinks',array($this,'show_shortcode_links'));
        add_shortcode('dzsvg_secondcon',array($this,'show_shortcode_secondcon'));
        add_shortcode('dzsvg_outernav',array($this,'show_shortcode_outernav'));


        add_shortcode('vimeo',array($this,'vimeo_func'));
        add_shortcode('youtube',array($this,'youtube_func'));
        add_shortcode('dzs_youtube',array($this,'youtube_func'));
        add_shortcode('dzs_video',array($this,'video_func'));

        if ($this->mainoptions['replace_wpvideo'] == 'on') {
            add_shortcode('video',array($this,'video_func'));
        }
        if ($this->mainoptions['replace_jwplayer'] == 'on') {
            add_shortcode('jwplayer',array($this,'video_func'));
        }
        if ($this->mainoptions['include_featured_gallery_meta'] == 'on') {
            include_once dirname(__FILE__).'/class_parts/extras_featured.php';
        }


        add_filter('attachment_fields_to_edit',array($this,'filter_attachment_fields_to_edit'),10,2);

        add_action('init',array($this,'handle_init'));
        add_action('wp_ajax_dzsvg_ajax',array($this,'post_save'));
        add_action('wp_ajax_dzsvg_import_ytplaylist',array($this,'post_importytplaylist'));
        add_action('wp_ajax_dzsvg_import_ytuser',array($this,'post_importytuser'));
        add_action('wp_ajax_dzsvg_import_vimeouser',array($this,'post_importvimeouser'));
        add_action('wp_ajax_dzsvg_get_db_gals',array($this,'post_get_db_gals'));
        add_action('wp_ajax_get_vimeothumb',array($this,'ajax_get_vimeothumb'));
        add_action('wp_ajax_dzsvg_import_galleries',array($this,'ajax_import_galleries'));



        add_action('wp_ajax_dzsvg_save_vpc',array($this,'post_save_vpc'));

        add_action('wp_ajax_dzsvg_ajax_mo',array($this,'post_save_mo'));
        add_action('wp_ajax_dzsvg_ajax_options_dc',array($this,'post_save_options_dc'));
        add_action('wp_ajax_dzsvg_ajax_options_dc_aurora',array($this,'post_save_options_dc_aurora'));



        add_action('admin_menu',array($this,'handle_admin_menu'));
        add_action('admin_head',array($this,'handle_admin_head'));


        add_action('wp_head',array($this,'handle_wp_head'));
        add_action('wp_footer',array($this,'handle_footer'));



        if ($this->mainoptions['enable_video_showcase'] == 'on') {
            add_filter('the_content', array($this, 'filter_the_content'));
        }
//        add_action('save_post', array($this, 'admin_meta_save'));


        if ($this->mainoptions['analytics_enable']=='on') {
            add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
            include_once("class_parts/analytics.php");
        }


        if ($this->mainoptions['tinymce_enable_preview_shortcodes'] == 'on') {
//            add_filter('mce_external_plugins',array(&$this,'add_tcustom_tinymce_plugin'));
//            add_filter('tiny_mce_before_init',array(&$this,'myformatTinyMCE'));



            add_action( 'print_media_templates', array( $this, 'handle_print_media_templates' ) );
            add_action('admin_print_footer_scripts',array($this, 'handle_admin_print_footer_scripts'));
        }

        if ($this->pluginmode != 'theme') {
            add_action('admin_init',array($this,'admin_init'));
            add_action('save_post',array($this,'admin_meta_save'));
        }


        register_activation_hook( __FILE__, array($this, 'handle_plugin_activate') );
        register_deactivation_hook( __FILE__, array($this, 'handle_plugin_deactivate'));
    }


    public function handle_plugin_activate(){
        $this->plugin_justactivated = "on";
//        echo 'ceva';

//        error_log('activation_hook');
        flush_rewrite_rules();


    }
    public function handle_plugin_deactivate(){

        flush_rewrite_rules();
    }

    function wp_dashboard_setup(){

        wp_add_dashboard_widget(
            'dzsbg_dashboard_analytics', // Widget slug.
            'Video Galery DZS Analytics', // Title.
            'dzsvg_analytics_dashboard_content'

        );
    }



    function check_posts(){

        // --- check posts
        if(isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder']=='on'){
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__).'/tinymce/popupiframe.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }
        if(isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder']=='on'){
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__).'/tinymce/popupiframe_showcase.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }
        if(isset($_GET['action'])){
//            dzsprx_shortcode_builder();

            if($_GET['action']=='ajax_dzsvg_submit_view'){
                $date = date('Y-m-d');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);

                $country = '';

                if($this->mainoptions['analytics_enable_location']=='on'){

//                    print_r($_SERVER);

                    if($_SERVER['REMOTE_ADDR']){

//                        $aux = wp_file


                        $request = wp_remote_get('http://ipinfo.io/'.$_SERVER['REMOTE_ADDR'].'/json');
                        $response = wp_remote_retrieve_body( $request );
                        $aux_arr = json_decode($response);
//                        print_r($aux_arr);

                        if($aux_arr){
                            $country = $aux_arr->country;
                        }
                    }
                }


                $this->analytics_get();

                if($this->analytics_views==false){
                    $this->analytics_views = array();
                }

                $sw=false;
                if(is_array($this->analytics_views)) {
                    foreach ($this->analytics_views as $lab => $aview) {
                        if ($aview['video_title'] == $_POST['video_title'] && $aview['date'] == $date && $aview['country'] == $country) {

                            $this->analytics_views[$lab]['views']++;

                            $sw = true;
                        }
                    }
                }

                if(!$sw){
                    array_push($this->analytics_views, array(
                        'video_title' => $_POST['video_title'],
                        'views' => 1,
                        'date' => $date,
                        'country' => $country,
                    ));

                    // && $aview['user_id'] == get_current_user_id()
                    //
                    //'user_id' => get_current_user_id(),
                }else{
//                    echo 'success'
                }

                update_option('dzsvg_analytics_views', $this->analytics_views);




                // -- user track
                if($this->mainoptions['analytics_enable_user_track']=='on'){
                    $userid = get_current_user_id();

                    if($_POST['dzsvg_curr_user']){
                        $userid = $_POST['dzsvg_curr_user'];
                    }

                    $sw = false;

                    foreach($this->analytics_users as $lab => $au){

                        if($au['user_id']==$userid && $au['video_title']==$_POST['video_title']){
                            $this->analytics_users[$lab]['views']++;
                            $sw = true;
                        }
                    }


                    if(!$sw){
                        array_push($this->analytics_users, array(
                            'views'=>1,
                            'seconds'=>0,
                            'video_title' => $_POST['video_title'],
                            'user_id' => $userid,
                        ));
                    }


//                    echo $userid; print_r($this->analytics_users);
                    update_option('dzsvg_analytics_users', $this->analytics_users);
                }

                die();


            }

            if($_GET['action']=='ajax_dzsvg_submit_contor_10_secs'){
                $date = date('Y-m-d');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);

                $this->analytics_get();

                if($this->analytics_minutes==false){
                    $this->analytics_minutes = array();
                }

                $sw=false;
                if(is_array($this->analytics_minutes)) {
                    foreach ($this->analytics_minutes as $lab => $aview) {
                        if ($aview['video_title'] == $_POST['video_title'] && $aview['date'] == $date && $aview['country_code'] == '') {

                            $this->analytics_minutes[$lab]['seconds']+=10;

                            $sw = true;
                        }
                    }
                }

                if(!$sw){
                    array_push($this->analytics_minutes, array(
                        'video_title' => $_POST['video_title'],
                        'seconds' => 10,
                        'date' => $date,
                        'country_code' => '',
                    ));

                    // && $aview['user_id'] == get_current_user_id()
                    //
                    //'user_id' => get_current_user_id(),
                }else{
//                    echo 'success'
                }

                update_option('dzsvg_analytics_minutes', $this->analytics_minutes);



                if($this->mainoptions['analytics_enable_user_track']=='on'){
                    $userid = get_current_user_id();

                    if($_POST['dzsvg_curr_user']){
                        $userid = $_POST['dzsvg_curr_user'];
                    }

                    $sw = false;

                    foreach($this->analytics_users as $lab => $au){

                        if($au['user_id']==$userid && $au['video_title']==$_POST['video_title']){
                            $this->analytics_users[$lab]['seconds']+=10;
                            $sw = true;
                        }
                    }


                    if(!$sw){
                        array_push($this->analytics_users, array(
                            'views'=>1,
                            'seconds'=>10,
                            'video_title' => $_POST['video_title'],
                            'user_id' => $userid,
                        ));
                    }


//                    echo $userid; print_r($this->analytics_users);
                    update_option('dzsvg_analytics_users', $this->analytics_users);
                }

                die();


            }

        }
    }

    function analytics_get(){
        $this->analytics_views = get_option('dzsvg_analytics_views');
        $this->analytics_minutes = get_option('dzsvg_analytics_minutes');


        if($this->mainoptions['analytics_enable_user_track']=='on'){
            $this->analytics_users = get_option('dzsvg_analytics_users');


            if($this->analytics_users==false){
                $this->analytics_users = array();
            }
        }


    }

    //include the tinymce javascript plugin
    function add_tcustom_tinymce_plugin($plugin_array) {
//        $plugin_array['ve_dzs_video'] = $this->thepath.'tinymce/visualeditor/editor_plugin.js';
        return $plugin_array;
    }

    //include the css file to style the graphic that replaces the shortcode
    function myformatTinyMCE($options) {

        $ext = 'iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src|id|class|title|style],video[source],source[*]';

        if (isset($options['extended_valid_elements']))
            $options['extended_valid_elements'] .= ','.$ext;
        else
            $options['extended_valid_elements'] = $ext;


        $options['media_strict'] = 'false';

//    print_r($options);

        $options['content_css'] .= ",".$this->thepath.'tinymce/visualeditor/editor-style.css';

        return $options;
    }

    function handle_wp_head() {
        echo '<script>';
        echo 'window.dzsvg_settings= {dzsvg_site_url: "'.site_url().'/",version: "'.DZSVG_VERSION.'"}; window.dzsvg_site_url="'.site_url().'";';
        echo 'window.dzsvg_swfpath="'.$this->thepath.'preview.swf";';
        if(isset($this->mainoptions['translate_skipad']) && $this->mainoptions['translate_skipad']!='Skip Ad'){
            echo 'window.dzsvg_translate_skipad = "'.$this->mainoptions['translate_skipad'].'";';
        }
        if(isset($this->mainoptions['analytics_enable_user_track']) && $this->mainoptions['analytics_enable_user_track']=='on'){
            echo 'window.dzsvg_curr_user = "'.get_current_user_id().'";';
        }
        echo '</script>';

        if ($this->mainoptions['extra_css']) {
            echo '<style>';
            echo $this->mainoptions['extra_css'];
            echo '</style>';
        }

        if(isset($_GET['dzsvg_startitem_dzs-video0'])&& ($_GET['dzsvg_startitem_dzs-video0'] || $_GET['dzsvg_startitem_dzs-video0']==='0') ){

            global $post;


//            print_r($post);

            $po_co = $post->post_content;

            $output_array = array();
            preg_match("/\[(?:dzs_){0,1}videogallery.*?id=\"(.*?)\"/sm", $po_co, $output_array);

//            print_r($output_array);

            if(count($output_array)>0){

                if(isset($output_array[1])){
                    $its = $this->show_shortcode(array(
                      'id'=>  $output_array[1],
                      'return_mode'=>  'items',
                    ));

//                    print_r($its);

                    if(isset($its[$_GET['dzsvg_startitem_dzs-video0']])){
                        $it = $its[$_GET['dzsvg_startitem_dzs-video0']];

//                        print_r($it);


                        if(isset($it['title'])){
                            echo '<meta property="og:url" content="'.get_permalink($post->ID).'?dzsvg_startitem_dzs-video0='.$_GET['dzsvg_startitem_dzs-video0'].'" />';
                        }

                        if(isset($it['title'])){

                            echo '<meta property="og:title" content="'.$it['title'].'" />';
                        }
                        if(isset($it['description'])){

                            echo '<meta property="og:description" content="'.strip_tags($it['description']).'" />';
                        }

                        if(isset($it['thethumb'])){
                            echo '<meta property="og:image" content="'.$it['thethumb'].'" />';
                            echo '<meta property="twitter:image" content="'.$it['thethumb'].'" />';
                        }
                    }
                }
            }
            
        }
    }

    function handle_admin_head() {

        //global $post; print_r($post);
        //echo 'ceva23';
        ///siteurl : "'.site_url().'",
        $aux = remove_query_arg('deleteslider',admin_url("admin.php?page=".$this->adminpagename));
        $params = array('currslider' => '_currslider_');

        if(isset($_GET['dbname']) && $_GET['dbname']){

            $params['dbname'] = $_GET['dbname'];
        }


        $newurl = (add_query_arg($params,$aux));

        $params = array('deleteslider' => '_currslider_');
        $delurl = (add_query_arg($params,$aux));
        echo '<script>var dzsvg_settings = { thepath: "'.$this->thepath.'",the_url: "'.$this->thepath.'",version: "'.DZSVG_VERSION.'", is_safebinding: "'.$this->mainoptions['is_safebinding'].'", admin_close_otheritems:"'.$this->mainoptions['admin_close_otheritems'].'",wpurl : "'.site_url().'",translate_add_videogallery: "'.__("Add Video Gallery").'",translate_add_videoshowcase: "'.__("Add Video Showcase").'" ';

        //echo 'hmm';
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename && ( (isset($this->mainitems[$this->currSlider]) && $this->mainitems[$this->currSlider] == '') || isset($this->mainitems[$this->currSlider]) == false )) {
            echo ', addslider:"on"';
        }
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs && (isset($this->mainvpconfigs[$this->currSlider])==false || $this->mainvpconfigs[$this->currSlider] == '')) {
            echo ', addslider:"on"';
        }
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions &&  ( ( (isset($_GET['dzsvg_shortcode_builder'])) && $_GET['dzsvg_shortcode_builder']=='on' )  || ( (isset($_GET['dzsvg_shortcode_showcase_builder'])) && $_GET['dzsvg_shortcode_showcase_builder']=='on' ) )  && isset($_GET['sel'] ) ) {
            echo ', startSetup:"'.$_GET['sel'],'"';
        }
        echo ', urldelslider:"'.$delurl.'", urlcurrslider:"'.$newurl.'", currSlider:"'.$this->currSlider.'", currdb:"'.$this->currDb.'", zsvg_dc_poster_url_path:"'.$this->thepath.'deploy/designer/index.php"'
            . ',settings_limit_notice_dismissed: "'.$this->mainoptions['settings_limit_notice_dismissed'].'",shortcode_generator_url: "'.admin_url('admin.php?page='.$this->adminpagename_mainoptions) . '&dzsvg_shortcode_builder=on'.'",shortcode_showcase_generator_url: "'.admin_url('admin.php?page='.$this->adminpagename_mainoptions) . '&dzsvg_shortcode_showcase_builder=on'.'"};';
        echo '  </script>';




        if($this->mainoptions['enable_auto_backup']=='on'){
            $last_backup = get_option('dzsvg_last_backup');

            if($last_backup){

                $timestamp = time();
                if(abs($timestamp - $last_backup)> (3600*24) ){

                    $this->do_backup();
                }

            }else{
                $this->do_backup();
            }
        }




    }

    function do_backup(){

        $timestamp = time();

//        echo 'time - '.$timestamp;

        $data = get_option($this->dbitemsname);

        if(is_array($data)){
            $data = serialize($data);
        }

//        echo ' data - '.$data;
//        file_put_contents('backups/backup_'.$timestamp,$data);
        file_put_contents($this->base_path.'backups/backup_'.$timestamp.'.txt',$data);

        update_option('dzsvg_last_backup',$timestamp);



        if (is_array($this->dbs)) {
            foreach ($this->dbs as $adb) {
                $data = get_option($this->dbitemsname.'-'.$adb);

                if(is_array($data)){
                    $data = serialize($data);
                }
                file_put_contents($this->base_path.'backups/backup_'.$adb.'_'.$timestamp.'.txt',$data);
            }
        }
    }

    function handle_footer() {

        global $post;
        if (!$post) {
            return;
        }
        //echo 'ceva';
        $wallid = get_post_meta($post->ID,'dzsvg_fullscreen',true);
        if ($wallid != '' && $wallid != 'none') {
            echo '<div class="wall-close">'.__('CLOSE GALLERY','dzsvg').'</div>';
            echo do_shortcode('[videogallery id="'.$wallid.'" fullscreen="on"]');
            ?>
            <script>
                var dzsvg_videofs = true;
                jQuery(document).ready(function($) {
                    //$('body').css('overflow', 'hidden');
                    jQuery(".wall-close").click(handle_wall_close);
                    function handle_wall_close() {
                        var _t = $(this);
                        if (dzsvg_videofs == true) {
                            _t.html('OPEN GALLERY');
                            jQuery(".gallery-is-fullscreen").fadeOut("slow");
                            dzsvg_videofs = false;
                        } else {
                            _t.html('CLOSE GALLERY');
                            jQuery(".gallery-is-fullscreen").fadeIn("slow");
                            dzsvg_videofs = true;
                        }
                    }
                })
            </script>
            <?php
        }
        ?>
        <script>window.init_zoombox_settings = {
                settings_zoom_doNotGoBeyond1X:'off'
                ,design_skin:'skin-nebula'
                ,settings_enableSwipe:'off'
                ,settings_enableSwipeOnDesktop:'off'
                ,settings_galleryMenu:'dock'
                ,settings_useImageTag:'on'
                ,settings_paddingHorizontal : '100'
                ,settings_paddingVertical : '100'
                ,settings_disablezoom:'off'
                ,settings_transition : 'fade'
                ,settings_transition_out : 'fade'
                ,settings_transition_gallery : 'slide'
                ,settings_disableSocial: 'on'
                ,settings_zoom_use_multi_dimension: 'on'
            };</script><?php
    }

    function vimeo_func($atts) {
        //[vimeo id="youtubeid"]
        $fout = '';
        $margs = array(
            'id' => '2',
            'vimeo_title' => '',
            'vimeo_byline' => '0',
            'vimeo_portrait' => '0',
            'vimeo_color' => '',
            'width' => '100%',
            'height' => '300',
            'config' => '',
            'single' => 'on',
        );

        if ($atts == false) {
            $atts = array();
        }

        $margs = array_merge($margs,$atts);

        $w = 400;
        if (isset($margs['width'])) {
            $w = $margs['width'];
        }
        $h = 300;
        if (isset($margs['height'])) {
            $h = $margs['height'];
        }

        $vpsettingsdefault = array();
        $vpsettingsdefault['settings'] = array(
            'id' => 'default',
            'skin_html5vp' => 'skin_aurora',
            'html5design_controlsopacityon' => '1',
            'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'sdquality' => 'small',
            'hdquality' => 'hd720',
            'defaultquality' => 'HD',
            'yt_customskin' => 'on',
        );
        $i = 0;
        $vpconfig_k = 0;
        $vpsettings = array();





        if ($margs['config'] != '') {
            $vpconfig_id = $margs['config'];

            for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $vpconfig_id)) {
                    $vpconfig_k = $i;
                }
            }
            $vpsettings = $this->mainvpconfigs[$vpconfig_k];
        }


        $vpsettings = array_merge($vpsettingsdefault,$vpsettings);

        //print_r($vpsettings);

        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_byline'])) {
            $margs['vimeo_byline'] = $vpsettings['settings']['vimeo_byline'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_title'])) {
            $margs['vimeo_title'] = $vpsettings['settings']['vimeo_title'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_color'])) {
            $margs['vimeo_color'] = $vpsettings['settings']['vimeo_color'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_portrait'])) {
            $margs['vimeo_portrait'] = $vpsettings['settings']['vimeo_portrait'];
        }

        //print_r($margs);


        $str_title = 'title='.$margs['vimeo_title'];
        $str_byline = '&amp;byline='.$margs['vimeo_byline'];
        $str_portrait = '&amp;portrait='.$margs['vimeo_portrait'];
        $str_color = '';
        if ($margs['vimeo_color'] != '') {
            $str_color = '&amp;color='.$margs['vimeo_color'];
        }



        $fout.='<iframe src="http://player.vimeo.com/video/'.$margs['id'].'?'.$str_title.$str_byline.$str_portrait.$str_color.'" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe>';
        return $fout;
    }

    function youtube_func($atts) {
        //[youtube id="youtubeid"]

        $fout = '';

        $margs = array(
            'width' => '100%',
            'config' => '',
            'height' => '300',
            'source' => '',
            'mediaid' => '',
            'config' => '',
            'player' => '',
            'mp4' => '',
            'sourceogg' => '',
            'autoplay' => 'off',
            'cuevideo' => 'on',
            'cover' => '',
            'type' => 'youtube',
            'cssid' => '',
            'single' => 'on',
        );

        $margs = array_merge($margs,$atts);

        if (isset($margs['id'])) {
            $margs['source'] = $margs['id'];
        }

        return $this->video_func($margs);
    }

    function video_func($atts) {
        //[dzs_video source="http://localhost/wordpress/wp-content/uploads/2015/03/test.m4v" config="minimalplayer" height="" type="video"]
        //[video source="pathto.mp4"]
        $this->slider_index++;

        $fout = '';


        $this->front_scripts();

        $margs = array(
            'width' => '100%',
            'config' => '',
            'height' => '300',
            'source' => '',
            'mediaid' => '',
            'player' => '',
            'mp4' => '',
            'sourceogg' => '',
            'autoplay' => 'off',
            'cuevideo' => 'on',
            'cover' => '',
            'type' => 'video',
            'cssid' => '',
            'single' => 'on',
        );

        
        $player_index = $this->index_players+1;

        $margs = array_merge($margs,$atts);


        if ($margs['cssid'] == '') {
            $margs['cssid'] = 'vp'.($player_index);
        }




        if ($margs['mediaid'] != '') {
            $auxpo = get_post($margs['mediaid']);
            if ($auxpo == false) {
                return '<div class="warning">Video does not exist anymore...</div>';
            }
            $margs['source'] = $auxpo->guid;
            //print_r($auxpo);
        }
        if ($margs['mp4'] != '') {
            //$auxpo = get_post($margs['mediaid']);
            $margs['source'] = $margs['mp4'];
            //print_r($auxpo);
        }
        if ($margs['player'] != '') {
            $margs['config'] = $margs['player'];
        }


        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = '';

        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_html5vp' => 'skin_aurora',
            'html5design_controlsopacityon' => '1',
            'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'youtube_sdquality' => 'small',
            'youtube_hdquality' => 'hd720',
            'youtube_defaultquality' => 'hd',
            'yt_customskin' => 'on',
            'vimeo_byline' => '0',
            'vimeo_portrait' => '0',
            'vimeo_color' => '',
            'settings_video_overlay' => 'off',
            'settings_disable_mouse_out' => 'off',
            'settings_ios_usecustomskin' => 'on',
        );
        $vpsettings = array();


        if ($margs['config'] != '') {
            $vpconfig_id = $margs['config'];
        }

        if ($vpconfig_id != '') {
            //print_r($this->mainvpconfigs);
            for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainvpconfigs[$i]['settings']['id']))
                    $vpconfig_k = $i;
            }
            $vpsettings = $this->mainvpconfigs[$vpconfig_k];


            if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
                $vpsettings['settings'] = array();
            }
        }

        if (!isset($vpsettings['settings']) || (isset($vpsettings['settings']) && !is_array($vpsettings['settings']))) {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($vpsettingsdefault,$vpsettings['settings']);


        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {
            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';
            }else{

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }

        unset($vpsettings['settings']['id']);


        $str_sourceogg = '';

        $its = array(
            0 => $margs,
        );
        $its = array_merge($its,$vpsettings);

        if ($margs['sourceogg'] != '') {

            if (strpos($margs['sourceogg'],'.webm') === false) {
                $str_sourceogg.=' data-sourceogg="'.$margs['sourceogg'].'"';
            } else {
                $str_sourceogg.=' data-sourcewebm="'.$margs['sourceogg'].'"';
            }

            $its[0]['html5sourceogg'] = $margs['sourceogg'];
        }

        $str_cover = '';

//        print_r($margs);
        if ($margs['cover'] != '') {
//            echo 'lalala';
            $its['settings']['coverImage'] = $margs['cover'];
        }

//        print_r($its);



        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom' || $vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
            $fout.='<style>';
            $fout.='.vp'.$player_index.' { background-color:'.$this->mainoptions_dc['background'].';} ';
            $fout.='.vp'.$player_index.' .background{ background-color:'.$this->mainoptions_dc['controls_background'].'!important;} ';
            $fout.='.vp'.$player_index.' .scrub-bg{ background-color:'.$this->mainoptions_dc['scrub_background'].'!important;} ';
            $fout.='.vp'.$player_index.' .scrub-buffer{ background-color:'.$this->mainoptions_dc['scrub_buffer'].'!important;} ';

            $fout.='.vp'.$player_index.' .playSimple path,.vp'.$player_index.' .pauseSimple path{ fill:'.$this->mainoptions_dc['controls_color'].'!important;}  .vp'.$player_index.' .volumeicon path{ fill: '.$this->mainoptions_dc['controls_color'].'!important; }  .vp'.$player_index.' .fscreencontrols rect, .vp'.$player_index.' .fscreencontrols polygon { fill: '.$this->mainoptions_dc['controls_color'].'!important; } .vp'.$player_index.' .hdbutton-con .hdbutton-normal{ color: '.$this->mainoptions_dc['controls_color'].'!important; }   ';

            $fout.='.vp'.$player_index.' .playSimple:hover path{ fill: '.$this->mainoptions_dc['controls_hover_color'].'!important; } .vp'.$player_index.' .pauseSimple:hover path{ fill: '.$this->mainoptions_dc['controls_hover_color'].'!important; }  .vp'.$player_index.' .volumeicon:hover path{ fill: '.$this->mainoptions_dc['controls_hover_color'].'!important; }  .hdbutton-con:hover .hdbutton-normal{ color: '.$this->mainoptions_dc['controls_hover_color'].'!important; }      .vp'.$player_index.' .fscreencontrols:hover rect, .vp'.$player_index.' .fscreencontrols:hover polygon { fill: '.$this->mainoptions_dc['controls_hover_color'].'!important; }    ';


            $fout.='.vp'.$player_index.' .volume_active{ background-color: '.$this->mainoptions_dc['controls_highlight_color'].'!important; } .vp'.$player_index.' .scrub{ background-color: '.$this->mainoptions_dc['controls_highlight_color'].'!important; } .vp'.$player_index.' .hdbutton-con .hdbutton-hover{ color: '.$this->mainoptions_dc['controls_highlight_color'].'!important; } ';
            $fout.='.vp'.$player_index.' .curr-timetext{ color: '.$this->mainoptions_dc['timetext_curr_color'].'; } ';
            $fout.='</style>';
        }



        $fout.=$this->parse_items($its,$margs).' 
<script>jQuery(document).ready(function($){ var videoplayersettings = {
autoplay : "'.$margs['autoplay'].'",
cueVideo : "'.$margs['cuevideo'].'",
controls_out_opacity : "'.$vpsettings['settings']['html5design_controlsopacityon'].'",
controls_normal_opacity : "'.$vpsettings['settings']['html5design_controlsopacityout'].'"
,settings_hideControls : "off"
,settings_video_overlay : "'.$vpsettings['settings']['settings_video_overlay'].'"
,settings_disable_mouse_out : "'.$vpsettings['settings']['settings_disable_mouse_out'].'"
,settings_ios_usecustomskin : "'.$vpsettings['settings']['settings_ios_usecustomskin'].'"
,settings_swfPath : "'.$this->thepath.'preview.swf"
,design_skin: "'.$skin_vp.'"';

        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $fout.=',controls_fscanvas_bg:"'.$this->mainoptions_dc['controls_color'].'"';
            $fout.=',controls_fscanvas_hover_bg:"'.$this->mainoptions_dc['controls_hover_color'].'"';
            $fout.=',fpc_background:"'.$this->mainoptions_dc['background'].'"';
            $fout.=',fpc_controls_background:"'.$this->mainoptions_dc['controls_background'].'"';
            $fout.=',fpc_scrub_background:"'.$this->mainoptions_dc['scrub_background'].'"';
            $fout.=',fpc_scrub_buffer:"'.$this->mainoptions_dc['scrub_buffer'].'"';
            $fout.=',fpc_controls_color:"'.$this->mainoptions_dc['controls_color'].'"';
            $fout.=',fpc_controls_hover_color:"'.$this->mainoptions_dc['controls_hover_color'].'"';
            $fout.=',fpc_controls_highlight_color:"'.$this->mainoptions_dc['controls_highlight_color'].'"';
        }

        if(isset($vpsettings['settings']['settings_video_end_reset_time']) && $vpsettings['settings']['settings_video_end_reset_time']=='off'){
            $fout.=',settings_video_end_reset_time:"off"';
        }


        $fout.='}; jQuery(".vp'.($player_index).'").vPlayer(videoplayersettings); });</script>';

        return $fout;
    }

    function log_event($arg) {
        $fil = dirname(__FILE__)."/log.txt";
        $fh = @fopen($fil,'a');
        @fwrite($fh,($arg."\n"));
        @fclose($fh);
    }

    function show_shortcode_cats($atts,$content = null) {
        $fout = '';
        $margs = array(
            'width' => '100',
            'height' => 400,
        );

        $margs = array_merge($margs,$atts);



        // ===== some sanitizing
        $str_tw = $margs['width'];
        $str_th = $margs['height'];





        if (strpos($str_tw,"%") === false) {
            $str_tw = $str_tw.'px';
        }
        if (strpos($str_th,"%") === false && $str_th != 'auto') {
            $str_th = $str_th.'px';
        }


//        echo 'ceva'.$content;
        $lb = array("\r\n","\n","\r","<br />");
        $content = str_replace($lb, '', $content);
//        echo $content.'alceva';


        $aux = do_shortcode($content);;

//        $aux = strip_tags($aux, '<p><br/>');

        $fout.='<div class="categories-videogallery" id="cats'.( ++$this->cats_index).'">';
        $fout.='<div class="the-categories-con"><span class="label-categories">'.__('categories','dzsvg').'</span></div>';
        $fout.=$aux;
        $fout.='</div>';
        $fout.='<script>jQuery(document).ready(function($){ vgcategories("#cats'.$this->cats_index.'"); });</script>';

        return $fout;
    }

    function show_shortcode_lightbox($atts,$content = null) {

        $fout = '';
        //$this->sliders_index++;

        $this->front_scripts();

        wp_enqueue_style('zoombox',$this->thepath.'assets/zoombox/zoombox.css');
        wp_enqueue_script('zoombox',$this->thepath.'assets/zoombox/zoombox.js');

        $args = array(
            'id' => 'default'
        ,'db' => ''
        ,'category' => ''
        ,'width' => ''
        ,'height' => ''
        ,'gallerywidth' => '800'
        ,'galleryheight' => '500'
        );
        $args = array_merge($args,$atts);
        $fout.='<div class="zoombox"';

        if ($args['width'] != '') {
            $fout.=' data-width="'.$args['width'].'"';
        }
        if ($args['height'] != '') {
            $fout.=' data-height="'.$args['height'].'"';
        }
        if ($args['gallerywidth'] != '') {
            $fout.=' data-bigwidth="'.$args['gallerywidth'].'"';
        }
        if ($args['galleryheight'] != '') {
            $fout.=' data-bigheight="'.$args['galleryheight'].'"';
        }

        $fout.='data-src="'.$this->thepath.'retriever.php?id='.$args['id'].'" data-type="ajax">'.$content.'</div>';
        $fout.='<script>
jQuery(document).ready(function($){
$(".zoombox").zoomBox();
});
</script>';

        return $fout;
    }
    function show_shortcode_secondcon($pargs,$content = null) {
        // -- [dzsvg_secondcon id="example-youtube-channel-outer" extraclasses="skin-balne" enable_readmore="on" ]

        $fout = '';

        $margs = array(
            'id'=>'default',
            'extraclasses'=>'',
            'enable_readmore'=>'off',
        );
        if(is_array($pargs)==false){
            $pargs=array();
        }
        $margs = array_merge($margs, $pargs);






        wp_enqueue_style('dzs.advancedscroller',$this->thepath.'assets/advancedscroller/plugin.css');
        wp_enqueue_script('dzs.advancedscroller',$this->thepath.'assets/advancedscroller/plugin.js');

        $gallery_margs = array(
            'id' => $margs['id'],
            'return_mode' => 'items',
        );

        $its = $this->show_shortcode($gallery_margs);

        $css_classid = str_replace(' ','_',$margs['id']);
        $fout.='<div class="dzsas-second-con dzsas-second-con-for-'.$css_classid.' '.$margs['extraclasses'].'">';

        if($margs['enable_readmore']=='on'){
            $fout.='<div class="read-more-con">';
            $fout.='<div class="read-more-content">';
        }


        $fout.='<div class="dzsas-second-con--clip">';
        foreach ($its as $lab => $val){
            if ($lab==='settings') {
                continue;
            }

            $desc = $val['description'];



            $maxlen = 100;
            if (isset($its['settings']['maxlen_desc']) && $its['settings']['maxlen_desc']) {
                $maxlen = $its['settings']['maxlen_desc'];
            }
            if (isset($its['settings']['desc_different_settings_for_aside']) && ($its['settings']['try_to_close_unclosed_tags'])=='on'){

                if (isset($its['settings']['desc_aside_maxlen_desc']) && $its['settings']['desc_aside_maxlen_desc']) {
                    $maxlen = $its['settings']['desc_aside_maxlen_desc'];
                }
            }


//            echo 'maxlen - '.$maxlen;

            $striptags = false;

            if (isset($its['settings']['striptags']) && $its['settings']['striptags']==='on') {
                $striptags = true;
            }

            $try_to_close_unclosed_tags = true;


//                $striptags=true;
            if($striptags){
                $try_to_close_unclosed_tags = false;
            }
            if (isset($its['settings']['try_to_close_unclosed_tags']) && $its['settings']['try_to_close_unclosed_tags']==='off') {
                $try_to_close_unclosed_tags=false;
            }
            if (isset($its['settings']['desc_different_settings_for_aside']) && ($its['settings']['try_to_close_unclosed_tags'])=='on' && isset($its['settings']['desc_aside_try_to_close_unclosed_tags']) && $its['settings']['desc_aside_try_to_close_unclosed_tags']==='off') {
                $try_to_close_unclosed_tags=false;
            }


//            echo 'description - '.$che['description'];

            if (isset($val['description']) && $val['description']) {
                $desc =''.dzs_get_excerpt(0,
                        array(
                            'content' => $val['description'],
                            'maxlen' => $maxlen,
                            'try_to_close_unclosed_tags'=>$try_to_close_unclosed_tags,
                            'striptags'=>$striptags,
                        )
                    );
//                echo ' final desc -- '. $desc;
            }


            $fout.='<div class="item">
<h4>'.$val['title'].'</h4>
<p>'.$desc.'</p>
</div>';

//                print_r($val);

        }


        if($margs['enable_readmore']=='on') {
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '<div class="read-more-label"> <i class="fa fa-angle-down"></i> <span>' . __("DETAILS") . '</span></div>';


        }else{

        }
        $fout.='</div></div>';


        return $fout;


//        print_r($its);
    }

    function sanitize_description($desc, $pargs=array()){

        $fout = $desc;

        $margs = array(
            'desc_count'=>'default',
            'striptags'=>'on',
            'try_to_close_unclosed_tags'=>'on',
            'desc_readmore_markup'=>'',
        );
        if(is_array($pargs)==false){
            $pargs=array();
        }
        $margs = array_merge($margs, $pargs);





        $maxlen = 100;
        if ($margs['desc_count']) {
            $maxlen = $margs['desc_count'];
        }


//            echo 'maxlen - '.$maxlen;

        $striptags = false;

        if ($margs['striptags']=='on') {
            $striptags = true;
        }

        $try_to_close_unclosed_tags = true;


        if($striptags){
            $try_to_close_unclosed_tags = false;
        }
        if ($margs['try_to_close_unclosed_tags']=='on') {
            $try_to_close_unclosed_tags=false;
        }


//        print_r($margs);
//            echo 'description - '.$che['description'];

        if ($desc) {
            $fout =''.dzs_get_excerpt(0,
                    array(
                        'content' => $desc,
                        'maxlen' => $maxlen,
                        'try_to_close_unclosed_tags'=>$try_to_close_unclosed_tags,
                        'striptags'=>$striptags,
                        'readmore'=>'on',
                        'readmore_markup'=>$margs['desc_readmore_markup'],
                    )
                );
//                echo ' final desc -- '. $desc;
        }

        return $fout;
    }
    function show_shortcode_outernav($pargs,$content = null) {
        //[dzsvg_outernav id="theidofthegallery" skin="oasis" extraclasses="" thumbs_per_page="12" layout="layout-one-third"]
        $fout = '';

        $margs = array(
            'id'=>'default',
            'skin'=>'oasis',
            'extraclasses'=>'',
            'layout'=>'layout-one-fourth', // -- layout-one-fourth   layout-one-third   layout-width-370
            'thumbs_per_page'=>'8',
        );
        if(is_array($pargs)==false){
            $pargs=array();
        }
        $margs = array_merge($margs, $pargs);


        $gallery_margs = array(
            'id' => $margs['id'],
            'return_mode' => 'items',
        );

        $its = $this->show_shortcode($gallery_margs);

        $css_classid = str_replace(' ','_',$margs['id']);
        $fout.='<div class="videogallery--navigation-outer '.$margs['layout'].' videogallery--navigation-outer-for-'.$css_classid.' skin-'.$margs['skin'].' '.$margs['extraclasses'].'" data-vgtarget=".id_'.$css_classid.'"><div class="videogallery--navigation-outer--clip"><div class="videogallery--navigation-outer--clipmover">';

        $ix = 0;
        $maxblocksperrow = intval($margs['thumbs_per_page']);
        $nr_pages = 0;

//        print_r($its);

        foreach ($its as $lab => $val){
            if ($lab==='settings') {
                continue;
            }

//            print_r($val);

            if($ix%$maxblocksperrow===0){
                $fout.='<div class="videogallery--navigation-outer--bigblock';
                if($ix===0){
                    $fout.=' active';
                }

                $fout.='">';
            }



            $thumb = $val['thethumb'];
//            echo $thumb;
            if($thumb==''){
                if($val['type']=='youtube'){
                    $thumb = "http://img.youtube.com/vi/".$val['source']."/0.jpg";
                }
                if($val['type']=='vimeo'){
                    $id = $val['source'];

                    $target_file = "http://vimeo.com/api/v2/video/$id.php";
                    $cache = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));

                    $apiresp = $cache;
                    $imga = unserialize($apiresp);

                    //        print_r($cache);

                    $thumb = $imga[0]['thumbnail_medium'];
                }
            }



            $fout.='<span class="videogallery--navigation-outer--block">';
            if($margs['skin']=='oasis'){
                $fout.='
<span class="block-thumb" style="background-image: url('.$thumb.');"></span>';
            }
            if($margs['skin']=='balne'){
                $fout.='
<span class="image-con"><span class="hover-rect"></span><img class="fullwidth" src="'.$thumb.'" data-global-responsive-ratio="0.562"/></span>';
            }
            $fout.='<span class="block-title">'.$val['title'].'</span>';

            if(isset($val['uploader']) && $val['uploader']!=''){
                $fout.='<span class="block-extra">'.__('by ', 'dzsvg').'<strong>'.$val['uploader'].'</strong>'.'</span>';
            }

            $fout.='</span>';


            if($ix%$maxblocksperrow===($maxblocksperrow-1)){
                $fout.='</div>';
                $nr_pages++;
            }


            $ix++;

//                print_r($val);
        }

        // -- hier
        if($ix%$maxblocksperrow<=($maxblocksperrow-1) && $ix%$maxblocksperrow>0){
            $fout.='</div>';
            $nr_pages++;
        }
        $fout.='</div></div>';

        if($nr_pages>1){
            $fout.='<div class="videogallery--navigation-outer--bullets-con">';
            for($i=0;$i<$nr_pages;++$i){
                $fout.='<span class="navigation-outer--bullet';
                if($i==0){
                    $fout.=' active';
                }
                $fout.='"></span>';
            }
            $fout.='</div>';
        }



        $fout.='</div>';

        return $fout;
    }

    function show_shortcode_links($atts,$content = null) {
        //[videogallerylinks ids="2,3" height="300" source="pathtomp4.mp4" type="normal"]
        global $post;
        //print_r($post);
        $fout = '';
        //$this->sliders_index++;

        $this->front_scripts();

        $args = array(
            'ids' => '',
            'width' => 400,
            'height' => 300,
            'source' => '',
            'sourceogg' => '',
            'type' => 'normal',
            'autoplay' => 'on',
            'design_skin' => 'skin_aurora',
            'gallery_nav_type' => 'thumbs',
            'menuitem_width' => '275',
            'menuitem_height' => '75',
            'menuitem_space' => '1',
            'settings_ajax_extradivs' => '',
        );
        $args = array_merge($args,$atts);
        //print_r($args);
        if ($args['gallery_nav_type'] == 'scroller') {
            wp_enqueue_style('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.js');
        }
        $its = array();
        $ind_post = 0;
        $array_ids = explode(',',$args['ids']);
        //print_r($array_ids); print_r($args);
        foreach ($array_ids as $id) {
            $po = get_post($id);
            array_push($its,$po);
        }
        //print_r($its);
        $this->sliders_index++;

        $fout.='<div class="videogallery-with-links">';
        //==start vg-con
        $fout.='<div class="videogallery-con currGallery" style="width:'.$args['menuitem_width'].'px; height:'.$args['height'].'px; float:right; padding-top: 0; padding-bottom: 0;">';
        $fout.='<div class="preloader"></div>';
        $fout.='<div class="vg'.$this->sliders_index.' videogallery skin_default" >';

        $i = 0;
        foreach ($its as $it) {

            $the_src = wp_get_attachment_image_src(get_post_thumbnail_id($it->ID),'full');
            $fout.='<div class="vplayer-tobe" data-videoTitle="'.$it->post_title.'" data-type="link" data-src="'.get_permalink($it->ID).'">
<div class="menuDescription"><img src="'.$the_src[0].'" class="imgblock"/>
<div class="the-title">'.$it->post_title.'</div><div class="paragraph">'.$it->post_excerpt.'</div></div>
</div>';
            if ($it->ID == $post->ID) {
                $ind_post = $i;
            }
            $i++;
        }

        $fout.='</div>'; //==end vg
        $fout.='</div>'; //==end vg-con
        $fout.='';
        $fout.='<div class="history-video-element" style="overflow: hidden;">
<div class="vphistory vplayer-tobe" data-videoTitle="" data-img="" data-type="'.$args['type'].'" data-src="'.$args['source'].'"';
        if ($args['sourceogg'] != '') {
            if (strpos($args['sourceogg'],'.webm') === false) {
                $fout.=' data-sourceogg="'.$args['sourceogg'].'"';
            } else {
                $fout.=' data-sourcewebm="'.$args['sourceogg'].'"';
            }
        }
        $fout.='>
</div>
<div class="nest-script">
<div class="toexecute" style="display:none">
jQuery(document).ready(function($){
    var videoplayersettings = {
        autoplay : "'.$args['autoplay'].'"
        ,controls_out_opacity : 0.9
        ,controls_normal_opacity : 0.9
        ,settings_hideControls : "off"
        ,design_skin: "skin_aurora"
	,settings_swfPath : "'.$this->thepath.'preview.swf"
    };
    $(".vphistory").vPlayer(videoplayersettings);
})
</div>
</div>
</div>';

        $fout.='<script>
jQuery(".toexecute").each(function(){
    var _t = jQuery(this);
    if(_t.hasClass("executed")==false){
        eval(_t.text());
        _t.addClass("executed");
    }
})
jQuery(document).ready(function($){
dzsvg_init(".vg'.$this->sliders_index.'", {
    totalWidth:"'.$args['menuitem_width'].'"
    ,settings_mode:"normal"
    ,menuSpace:0
    ,randomise:"off"
    ,autoplay :"'.$args['autoplay'].'"
    ,cueFirstVideo: "off"
    ,autoplayNext : "on"
    ,nav_type: "'.$args['gallery_nav_type'].'"
    ,menuitem_width:"'.$args['menuitem_width'].'"
    ,menuitem_height:"'.$args['menuitem_height'].'"
    ,menuitem_space:"'.$args['menuitem_space'].'"
    ,menu_position:"right"
    ,transition_type:"fade"
    ,design_skin: "skin_navtransparent"
    ,embedCode:""
    ,shareCode:""
    ,logo: ""
    ,design_shadow:"off"
    ,settings_disableVideo:"on"
    ,startItem: "'.$ind_post.'"
    ,settings_enableHistory: "on"
        ,settings_ajax_extraDivs : "'.$args['settings_ajax_extradivs'].'"
});
});
</script>';
        $fout.='</div>';

        return $fout;
    }

    function show_shortcode($atts) {
        global $post;
        $fout = '';
        $iout = ''; //items parse

//        echo 'show_shortcode()';

        if ($this->mainoptions['debug_mode'] == 'on') {
            echo 'memory usage - ' . memory_get_usage() . "\n";
        }

        $margs = array(
            'id' => 'default'
        ,'db' => ''
        ,'category' => ''
        ,'fullscreen' => 'off'
        ,'settings_separation_mode' => 'normal'  // === normal ( no pagination ) or pages or scroll or button
        ,'settings_separation_pages_number' => '5'//=== the number of items per 'page'
        ,'settings_separation_paged' => '0'//=== the page number
        ,'return_mode' => 'normal' // -- "normal" returns the whole gallery, "items" returns the items array, "parsed items" returns the parsed items ( for pagination for example )
        );

        if ($atts == '') {
            $atts = array();
        }

        $margs = array_merge($margs,$atts);

        if (isset($_GET['dzsvg_settings_separation_paged'])) {
            $margs['settings_separation_paged'] = $_GET['dzsvg_settings_separation_paged'];
        }

        $extra_galleries = array();

        //===setting up the db
        $currDb = '';
        if (isset($margs['db']) && $margs['db'] != '') {
            $this->currDb = $margs['db'];
            $currDb = $this->currDb;
        }
        $this->dbs = get_option($this->dbdbsname);

        //echo 'ceva'; print_r($this->dbs);
        if ($currDb != 'main' && $currDb != '') {
            $dbitemsname = $this->dbitemsname.'-'.$currDb;
            $this->mainitems = get_option($dbitemsname);
        }
        //===setting up the db END
//        print_r($margs) ; echo $this->dbitemsname; print_r($this->mainitems);


        if ($this->mainitems == '') {
            return;
        }

        $this->front_scripts();


        if($margs['return_mode']=='normal'){ $this->sliders_index++; }



        $i = 0;
        $k = 0;
        $id = 'default';
        if (isset($margs['id'])) {
            $id = $margs['id'];
        }



        //---- extra galleries code

        if(strpos($id,',')!==false){
            $auxa = explode(",", $id);
            $id = $auxa[0];

            unset($auxa[0]);
            $extra_galleries = $auxa;
//            print_r($auxa);
        }

        //echo 'ceva' . $id;
        for ($i = 0; $i < count($this->mainitems); $i++) {
            if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id']))
                $k = $i;
        }

        $its = $this->mainitems[$k];
//        print_r($this->mainitems);


        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_html5vp' => 'skin_aurora',
            'html5design_controlsopacityon' => '1',
            'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'youtube_sdquality' => 'small',
            'youtube_hdquality' => 'hd720',
            'youtube_defaultquality' => 'hd',
            'yt_customskin' => 'on',
            'vimeo_byline' => '0',
            'vimeo_portrait' => '0',
            'vimeo_color' => '',
            'settings_video_overlay' => 'off',
        );

        $vpsettings = array();


        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = $its['settings']['vpconfig'];
        for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
            if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainvpconfigs[$i]['settings']['id'])) {
                $vpconfig_k = $i;
            }
        }
        $vpsettings = $this->mainvpconfigs[$vpconfig_k];

        if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($vpsettingsdefault,$vpsettings['settings']);

        unset($vpsettings['settings']['id']);
        //print_r($vpsettings);
        if (is_array($its['settings']) == false) {
            $its['settings'] = array();
        }
        $its['settings'] = array_merge($its['settings'],$vpsettings['settings']);
        //print_r($its);



        if ($post && $this->sliders_index == 1) {
            if (get_post_meta($post->ID,'dzsvg_preview',true) == 'on') {
                wp_enqueue_script('preseter',$this->thepath.'preseter/preseter.js');
                wp_enqueue_style('preseter',$this->thepath.'preseter/preseter.css');
                echo '<div class="preseter"><div class="the-icon"></div>
<div class="the-content"><h3>Quick Config</h3>
<form method="GET">
<div class="setting">
<div class="alabel">Menu Position:</div>
<div class="select-wrapper"><span>right</span><select name="opt3" class="textinput short"><option>right</option><option>down</option><option>up</option><option>left</option><option>none</option></select></div>
</div>
<div class="setting">
<div class="alabel">Autoplay:</div>
<div class="select-wrapper"><span>on</span><select name="opt4" class="textinput short"><option value="on">'.__('on','dzsvg').'</option><option value="off">'.__('off','dzsvg').'</option></select></div>
</div>
<div class="setting type_all">
    <div class="setting-label">'.__('Feed From','dzsvg').'</div>
    <div class="select-wrapper"><span>normal</span><select class="textinput styleme" name="feedfrom">
        <option>ytuserchannel</option>
        <option>ytkeywords</option>
        <option>ytplaylist</option>
        <option>vmuserchannel</option>
        <option>vmchannel</option>
    </select></div>
</div>
<div class="setting">
    <div class="alabel">Target Feed User</div>
    <div class="sidenote">Or playlist ID if you have selected playlist in the dropdown</div>
    <input type="text" name="opt6" value="digitalzoomstudio"/>
</div>
<div class="setting">
    <input type="submit" class="button-primary" name="submiter" value="Submit"/>
</div>
</form>
</div><!--end the-content-->
</div>';
                if (isset($_GET['opt3'])) {
                    $its['settings']['nav_type'] = 'none';
                    $its['settings']['menuposition'] = $_GET['opt3'];
                    $its['settings']['autoplay'] = $_GET['opt4'];
                    $its['settings']['feedfrom'] = $_GET['feedfrom'];
                    $its['settings']['youtubefeed_user'] = $_GET['opt6'];
                    $its['settings']['ytkeywords_source'] = $_GET['opt6'];
                    $its['settings']['ytplaylist_source'] = $_GET['opt6'];
                    $its['settings']['vimeofeed_user'] = $_GET['opt6'];
                    $its['settings']['vimeofeed_channel'] = $_GET['opt6'];
                }
            }
        }//----dzsvg preview END


        if ($its['settings']['nav_type'] == 'scroller') {
            wp_enqueue_style('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.js');
        }

        $w = $its['settings']['width'].'px';
        $h = $its['settings']['height'].'px';
        $fullscreenclass = '';
        $theclass = 'videogallery';
        //echo $id;
        //$fout.='<div class="videogallery-con" style="width:'.$w.'; height:'.$h.'; opacity:0;">';
        if ($margs['category'] != '') {
            $its['settings']['autoplay'] = 'off';
        }


        $user_feed = '';
        $yt_playlist_feed = '';




        $skin_html5vg = 'skin_pro';
        if (isset($vpsettings['settings']['skin_html5vg']) == false || $vpsettings['settings']['skin_html5vg'] == 'skin_custom') {
            $skin_html5vg = 'skin_pro';
        } else {
            $skin_html5vg = $vpsettings['settings']['skin_html5vg'];
        }



        $wmode = 'opaque';
        if (isset($its['settings']['windowmode'])) {
            $wmode = $its['settings']['windowmode'];
        }


        $targetfeed = '';
        $target_file = '';
        if (($its['settings']['feedfrom'] == 'ytuserchannel') && $its['settings']['youtubefeed_user'] != '') {
            $user_feed = $its['settings']['youtubefeed_user'];
            $targetfeed = $its['settings']['youtubefeed_user'];
        }
        if (($its['settings']['feedfrom'] == 'ytplaylist') && $its['settings']['ytplaylist_source'] != '') {
            $yt_playlist_feed = $its['settings']['ytplaylist_source'];
            $targetfeed = $its['settings']['ytplaylist_source'];

            if (substr($yt_playlist_feed,0,2) == "PL") {
                $yt_playlist_feed = substr($yt_playlist_feed,2);
            }
            $user_feed = '';
        }


        $vimeo_maxvideos = 25;

        if (isset($its['settings']['vimeo_maxvideos']) == false || $its['settings']['vimeo_maxvideos'] == '') {
            $its['settings']['vimeo_maxvideos'] = 25;
        }
        $vimeo_maxvideos = $its['settings']['vimeo_maxvideos'];

        if ($its['settings']['vimeo_maxvideos'] == 'all') {
            $vimeo_maxvideos = 500;
        }




//        echo 'feedfrom - '.$its['settings']['feedfrom'];









        // -----
        // -- ---- ---- YouTube user channel feed ---
        // -----
        if (($its['settings']['feedfrom'] == 'ytuserchannel') && $its['settings']['youtubefeed_user'] != '') {

            // -- deleting all items
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            //echo $target_file;


            $cacher = get_option('dzsvg_cache_ytuserchannel');

            $cached = false;


            if ($cacher == false || is_array($cacher) == false || $this->mainoptions['disable_api_caching'] == 'on') {
                $cached = false;
            } else {

//                print_r($cacher);


                $ik = -1;
                $i = 0;
                for ($i = 0; $i < count($cacher); $i++) {
                    if ($cacher[$i]['id'] == $targetfeed) {
                        if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                            $ik = $i;

//                                echo 'yabebe';
                            $cached = true;
                            break;
                        }
                    }
                }


                if($cached) {
                    foreach ($cacher[$ik]['items'] as $lab => $item) {
                        if ($lab === 'settings') {
                            continue;
                        }

                        $its[$lab] = $item;
                    }
                }

            }
            $i = 0;



//            echo 'iscached - ' . $cached;


            if (!$cached){


                // -- if not cached

                $target_file = 'https://www.googleapis.com/youtube/v3/search?q=' . $targetfeed . '&key=' . $this->mainoptions['youtube_api_key'] . '&type=channel&part=snippet';




                $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));


                if ($this->mainoptions['debug_mode'] == 'on') {
                    echo 'debug mode: target file ( '.$target_file.' )  ida is is...<br>';
                    print_r($ida);
                    echo '<br/>';
                }

//            echo 'ceva'.$ida;

                if (isset($its['settings']['youtubefeed_maxvideos']) == false || $its['settings']['youtubefeed_maxvideos'] == '') {
                    $its['settings']['youtubefeed_maxvideos'] = 50;
                }
                $yf_maxi = $its['settings']['youtubefeed_maxvideos'];

                if ($its['settings']['youtubefeed_maxvideos'] == 'all') {
                    $yf_maxi = 50;
                }

//                if(intval($yf_maxi) && $yf_maxi)


                if (!(isset($its['settings']['is_not_nicename']) && $its['settings']['is_not_nicename']=='on')) {


                    if ($ida) {

                        $obj = json_decode($ida);


                        if ($this->mainoptions['debug_mode'] == 'on') {
                            echo 'debug mode: is not nicename is ON, obj is is...<br>';
                            print_r($obj);
                            echo '<br/>';
                        }


                        if ($obj && is_object($obj)) {


                            if (isset($obj->items[0]->id->channelId)) {

//                        array_push($this->arr_api_errors, '<div class="dzsvg-error">'.__('This is dirty').'</div>');

                                $channel_id = $obj->items[0]->id->channelId;


                                $breaker = 0;
                                $nextPageToken = 'none';

                                while ($breaker < 10 || $nextPageToken !== '') {


                                    $str_nextPageToken = '';

                                    if ($nextPageToken && $nextPageToken != 'none') {
                                        $str_nextPageToken = '&pageToken=' . $nextPageToken;
                                    }


                                    if ($this->mainoptions['youtube_api_key'] == '') {
                                        $this->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
                                    }

                                    $target_file = 'https://www.googleapis.com/youtube/v3/search?key=' . $this->mainoptions['youtube_api_key'] . '&channelId=' . $channel_id . '&part=snippet&order=date&type=video' . $str_nextPageToken . '&maxResults=' . $yf_maxi;

//                        echo $target_file;

                                    $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));


                                    if ($ida) {

                                        $obj = json_decode($ida);

//                                        print_r($obj);


                                        if ($obj && is_object($obj)) {

//                                        print_r($obj);

                                            if (isset($obj->items[0]->id->videoId)) {


                                                foreach ($obj->items as $ytitem) {
//                    print_r($ytitem); echo $ytitem->id->videoId;


                                                    if (isset($ytitem->id->videoId) == false) {
                                                        echo 'this does not have id ? ';
                                                        continue;
                                                    }
                                                    $its[$i]['source'] = $ytitem->id->videoId;
                                                    $its[$i]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
                                                    $its[$i]['type'] = "youtube";

                                                    $aux = $ytitem->snippet->title;
                                                    $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                                                    $aux = str_replace($lb, ' ', $aux);
                                                    $its[$i]['title'] = $aux;

                                                    $aux = $ytitem->snippet->description;
                                                    $lb = array("\r\n","\n","\r");
                                                    $aux = str_replace($lb,'<br>',$aux);
                                                    $lb = array('"');
                                                    $aux = str_replace($lb,'&quot;',$aux);
                                                    $lb = array("'");
                                                    $aux = str_replace($lb,'&#39;',$aux);


                                                    $auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

                                                    $its[$i]['description'] = $auxcontent;
                                                    $its[$i]['menuDescription'] = $auxcontent;

//                    print_r($its['settings']);
                                                    if ($its['settings']['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
                                                        $its[$i]['uploader'] = $ytitem->snippet->channelTitle;
                                                    }

                                                    $i++;


//                                            if ($i > $yf_maxi + 1){ break; }

                                                }


                                            } else {

                                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('No videos to be found') . '</div>');
                                            }
//                                print_r($obj);
                                        } else {

                                            array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Object channel is not JSON...') . '</div>');
                                        }
                                    } else {

                                        array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot get info from YouTube API about channel') . '</div>');
                                    }


                                    if ($its['settings']['youtubefeed_maxvideos'] === 'all') {

                                        if (isset($obj->nextPageToken) && $obj->nextPageToken) {
                                            $nextPageToken = $obj->nextPageToken;
                                        } else {

                                            $nextPageToken = '';
                                            break;
                                        }

                                    } else {
                                        $nextPageToken = '';
                                        break;
                                    }

                                    $breaker++;
                                }


                                $sw34 = false;
                                $auxa34 = array(
                                    'id' => $targetfeed
                                , 'items' => $its
                                , 'time' => $_SERVER['REQUEST_TIME']

                                );

                                if (!is_array($cacher)) {
                                    $cacher = array();
                                } else {


                                    foreach ($cacher as $lab => $cach) {
                                        if ($cach['id'] == $targetfeed) {
                                            $sw34 = true;

                                            $cacher[$lab] = $auxa34;

                                            update_option('dzsvg_cache_ytuserchannel', $cacher);

//                                        print_r($cacher);
                                            break;
                                        }
                                    }


                                }

                                if ($sw34 == false) {

                                    array_push($cacher, $auxa34);

//                                            print_r($cacher);

                                    update_option('dzsvg_cache_ytuserchannel', $cacher);
                                }


                            } else {

                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot access channel ID, this is feed - ') . $target_file . '</div>');
                                try{

                                    if(isset($obj->error)){
                                        if($obj->error->errors[0]){


                                            array_push($this->arr_api_errors, '<div class="dzsvg-error">' .$obj->error->errors[0]->message . '</div>');
                                            if(strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction')!==false){

                                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
                                            }else{

                                            }
                                        }
                                    }

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
                                }catch(Exception $err){

                                }
                            }
                        } else {

                            array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Object is not JSON...') . '</div>');
                        }
                    }
                } else {
                    {

//                        array_push($this->arr_api_errors, '<div class="dzsvg-error">'.__('This is dirty').'</div>');

                        $channel_id = $targetfeed;


                        $breaker = 0;
                        $nextPageToken = 'none';

                        while ($breaker < 10 || $nextPageToken !== '') {


                            $str_nextPageToken = '';

                            if ($nextPageToken && $nextPageToken != 'none') {
                                $str_nextPageToken = '&pageToken=' . $nextPageToken;
                            }


                            if ($this->mainoptions['youtube_api_key'] == '') {
                                $this->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
                            }

                            $target_file = 'https://www.googleapis.com/youtube/v3/search?key=' . $this->mainoptions['youtube_api_key'] . '&channelId=' . $channel_id . '&part=snippet&order=date&type=video' . $str_nextPageToken . '&maxResults=' . $yf_maxi;

//                        echo $target_file;

                            $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));


                            if ($ida) {


                                $obj = json_decode($ida);


                                if ($obj && is_object($obj)) {

//                                        print_r($obj);

                                    if (isset($obj->items[0]->id->videoId)) {


                                        foreach ($obj->items as $ytitem) {
                                            print_r($ytitem); echo $ytitem->id->videoId;


                                            if (isset($ytitem->id->videoId) == false) {
                                                echo 'this does not have id ? ';
                                                continue;
                                            }
                                            $its[$i]['source'] = $ytitem->id->videoId;
                                            $its[$i]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
                                            $its[$i]['type'] = "youtube";

                                            $aux = $ytitem->snippet->title;
                                            $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                                            $aux = str_replace($lb, ' ', $aux);
                                            $its[$i]['title'] = $aux;

                                            $aux = $ytitem->snippet->description;
                                            $lb = array("\r\n","\n","\r");
                                            $aux = str_replace($lb,'<br>',$aux);
                                            $lb = array('"');
                                            $aux = str_replace($lb,'&quot;',$aux);
                                            $lb = array("'");
                                            $aux = str_replace($lb,'&#39;',$aux);


                                            $auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

                                            $its[$i]['description'] = $auxcontent;
                                            $its[$i]['menuDescription'] = $auxcontent;

//                    print_r($its['settings']);
                                            if ($its['settings']['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
                                                $its[$i]['uploader'] = $ytitem->snippet->channelTitle;
                                            }

                                            $i++;


//                                            if ($i > $yf_maxi + 1){ break; }

                                        }


                                    } else {

                                        array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('No videos to be found') . '</div>');
                                    }
//                                print_r($obj);
                                } else {

                                    array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Object channel is not JSON...') . '</div>');
                                }
                            } else {

                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot get info from YouTube API about channel') . '</div>');
                            }


                            if ($its['settings']['youtubefeed_maxvideos'] === 'all') {

                                if (isset($obj->nextPageToken) && $obj->nextPageToken) {
                                    $nextPageToken = $obj->nextPageToken;
                                } else {

                                    $nextPageToken = '';
                                    break;
                                }

                            } else {
                                $nextPageToken = '';
                                break;
                            }

                            $breaker++;
                        }


                        $sw34 = false;
                        $auxa34 = array(
                            'id' => $targetfeed
                        , 'items' => $its
                        , 'time' => $_SERVER['REQUEST_TIME']
                        , 'maxlen' => $its['settings']['youtubefeed_maxvideos']

                        );

                        if (!is_array($cacher)) {
                            $cacher = array();
                        } else {


                            foreach ($cacher as $lab => $cach) {
                                if ($cach['id'] == $targetfeed) {
                                    $sw34 = true;

                                    $cacher[$lab] = $auxa34;

                                    update_option('dzsvg_cache_ytuserchannel', $cacher);

//                                        print_r($cacher);
                                    break;
                                }
                            }


                        }

                        if ($sw34 == false) {

                            array_push($cacher, $auxa34);

//                                            print_r($cacher);

                            update_option('dzsvg_cache_ytuserchannel', $cacher);
                        }


                    }
                }


            }




        }
        // -- END YT USER CHANNEL





        //==============START youtube playlist
        if (($its['settings']['feedfrom'] == 'ytplaylist') && $its['settings']['ytplaylist_source'] != '') {



            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }



            $targetfeed = $its['settings']['ytplaylist_source'];





            $cacher = get_option('dzsvg_cache_ytplaylist');

            $cached = false;
            $found_for_cache = false;


            if ($cacher == false || is_array($cacher) == false || $this->mainoptions['disable_api_caching'] == 'on') {
                $cached = false;
            } else {

//                print_r($cacher);

                if ($this->mainoptions['debug_mode'] == 'on') {
                    if(isset($_GET['show_cacher']) && $_GET['show_cacher']=='on'){ print_r($cacher); };
                }


                $ik = -1;
                $i = 0;
                for ($i = 0; $i < count($cacher); $i++) {
                    if ($cacher[$i]['id'] == $targetfeed) {
                        if(isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $its['settings']['youtubefeed_maxvideos']){
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;

//                                echo 'yabebe';
                                $cached = true;
                                break;
                            }
                        }

                    }
                }


                if($cached){

                    foreach ($cacher[$ik]['items'] as $lab => $item) {
                        if ($lab === 'settings') {
                            continue;
                        }

                        $its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
                    }

                }
            }



            if ($this->mainoptions['debug_mode'] == 'on') {
                echo 'is cached - '.$cached.' | ';
            }



            if(!$cached){
                if (isset($its['settings']['youtubefeed_maxvideos']) == false || $its['settings']['youtubefeed_maxvideos'] == '') {
                    $its['settings']['youtubefeed_maxvideos'] = 50;
                }
                $yf_maxi = $its['settings']['youtubefeed_maxvideos'];

                if ($its['settings']['youtubefeed_maxvideos'] == 'all') {
                    $yf_maxi = 50;
                }



                $breaker = 0;

                $i_for_its = 0;
                $nextPageToken = 'none';

                while ($breaker < 10 || $nextPageToken !== '') {


                    $str_nextPageToken = '';

                    if ($nextPageToken && $nextPageToken != 'none') {
                        $str_nextPageToken = '&pageToken=' . $nextPageToken;
                    }

//                echo '$breaker is '.$breaker;

                    if($this->mainoptions['youtube_api_key']==''){
                        $this->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
                    }


                    $target_file='https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $targetfeed . '&key=' . $this->mainoptions['youtube_api_key'] . '' . $str_nextPageToken . '&maxResults='.$yf_maxi;

//                    echo $target_file;


                    if ($this->mainoptions['debug_mode'] == 'on') {
                        echo 'target file - '.$target_file;
                    }
//                    echo 'target file - '.$target_file;


                    $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

                    if ($ida) {

                        $obj = json_decode($ida);


                        if ($this->mainoptions['debug_mode'] == 'on') {
                            echo 'mode yt playlist - ida is '.$ida;


                            if ($this->mainoptions['debug_mode'] == 'on') {
                                if(isset($_GET['show_idar']) && $_GET['show_idar']=='on'){ print_r($obj); };
                            }
                        }

                        if ($obj && is_object($obj)) {
//                            print_r($obj);


                            // -- still ytplaylist

//                                        print_r($obj);

                                if (isset($obj->items[0]->snippet->resourceId->videoId)) {


                                    foreach ($obj->items as $ytitem) {
//                                print_r($ytitem);


                                        if ($this->mainoptions['debug_mode'] == 'on') {
                                            if(isset($_GET['show_item']) && $_GET['show_item']=='on'){ print_r($ytitem); };
                                        }
                                        if (isset($ytitem->snippet->resourceId->videoId) == false) {
                                            echo 'this does not have id ? ';
                                            continue;
                                        }
                                        $its[$i_for_its]['source'] = $ytitem->snippet->resourceId->videoId;

                                        if($ytitem->snippet->thumbnails){

                                            $its[$i_for_its]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
                                        }
                                        $its[$i_for_its]['type'] = "youtube";

                                        $aux = $ytitem->snippet->title;
                                        $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                                        $aux = str_replace($lb, ' ', $aux);
                                        $its[$i_for_its]['title'] = $aux;

                                        $aux = $ytitem->snippet->description;
                                        $lb = array("\r\n","\n","\r");
                                        $aux = str_replace($lb,'<br>',$aux);
                                        $lb = array('"');
                                        $aux = str_replace($lb,'&quot;',$aux);
                                        $lb = array("'");
                                        $aux = str_replace($lb,'&#39;',$aux);


                                        $auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

                                        $its[$i_for_its]['description'] = $auxcontent;
                                        $its[$i_for_its]['menuDescription'] = $auxcontent;

//                    print_r($its['settings']);
                                        if ($its['settings']['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
                                            $its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;
                                        }

                                        $i_for_its++;


                                    }

                                    $found_for_cache=true;


                                } else {

                                    array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube playlist videos to be found - maybe API key not set ? This is the feed - '.$target_file) . '</div>');

                                    try{

                                        if(isset($obj->error)){
                                            if($obj->error->errors[0]){


                                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' .$obj->error->errors[0]->message . '</div>');
                                                if(strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction')!==false){

                                                    array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
                                                }else{

                                                }
                                            }
                                        }

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
                                    }catch(Exception $err){

                                    }
                                }

                        }






                        if ($its['settings']['youtubefeed_maxvideos'] === 'all') {

                            if (isset($obj->nextPageToken) && $obj->nextPageToken) {
                                $nextPageToken = $obj->nextPageToken;
                            } else {

                                $nextPageToken = '';
                                break;
                            }

                        } else {
                            $nextPageToken = '';
                            break;
                        }


                    }
                    $breaker++;
                }





                if($found_for_cache){

                    $sw34 = false;
                    $auxa34 = array(
                        'id' => $targetfeed
                    , 'items' => $its
                    , 'time' => $_SERVER['REQUEST_TIME']
                    , 'maxlen' => $its['settings']['youtubefeed_maxvideos']

                    );

                    if (!is_array($cacher)) {
                        $cacher = array();
                    } else {


                        foreach ($cacher as $lab => $cach) {
                            if ($cach['id'] == $targetfeed) {
                                $sw34 = true;

                                $cacher[$lab] = $auxa34;

                                update_option('dzsvg_cache_ytplaylist', $cacher);

//                                        print_r($cacher);
                                break;
                            }
                        }


                    }

                    if ($sw34 == false) {

                        array_push($cacher, $auxa34);

//                                            print_r($cacher);

                        update_option('dzsvg_cache_ytplaylist', $cacher);
                    }
                }
            }


        }
        //=======END youtube playlist
        //
        //




        //==============START youtube keywords
        if (($its['settings']['feedfrom'] == 'ytkeywords') && $its['settings']['ytkeywords_source'] != '') {



            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }



            $targetfeed = $its['settings']['ytkeywords_source'];




            $cacher = get_option('dzsvg_cache_ytkeywords');

            $cached = false;
            $found_for_cache = false;


            if ($cacher == false || is_array($cacher) == false || $this->mainoptions['disable_api_caching'] == 'on') {
                $cached = false;
            } else {

//                print_r($cacher);


                $ik = -1;
                $i = 0;
                for ($i = 0; $i < count($cacher); $i++) {
                    if ($cacher[$i]['id'] == $targetfeed) {
                        if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                            $ik = $i;

//                                echo 'yabebe';
                            $cached = true;
                            break;
                        }
                    }
                }


                if($cached){

                    foreach ($cacher[$ik]['items'] as $lab => $item) {
                        if ($lab === 'settings') {
                            continue;
                        }

                        $its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
                    }

                }
            }




            if(!$cached){
                if (isset($its['settings']['youtubefeed_maxvideos']) == false || $its['settings']['youtubefeed_maxvideos'] == '') {
                    $its['settings']['youtubefeed_maxvideos'] = 50;
                }
                $yf_maxi = $its['settings']['youtubefeed_maxvideos'];

                if ($its['settings']['youtubefeed_maxvideos'] == 'all') {
                    $yf_maxi = 50;
                }



                $breaker = 0;

                $i_for_its = 0;
                $nextPageToken = 'none';

                while ($breaker < 5 || $nextPageToken !== '') {


                    $str_nextPageToken = '';

                    if ($nextPageToken && $nextPageToken != 'none') {
                        $str_nextPageToken = '&pageToken=' . $nextPageToken;
                    }

//                echo '$breaker is '.$breaker;


                    $targetfeed = str_replace(' ','+',$targetfeed);


                    if($this->mainoptions['youtube_api_key']==''){
                        $this->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
                    }

                    $target_file='https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . $targetfeed . '&type=video&key=' . $this->mainoptions['youtube_api_key'] . $str_nextPageToken.'&maxResults='.$yf_maxi;


                    $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

                    if ($ida) {

                        $obj = json_decode($ida);


                        if ($obj && is_object($obj)) {
//                                print_r($obj);



                            if (isset($obj->items[0]->id->videoId)) {


                                foreach ($obj->items as $ytitem) {
//                                print_r($ytitem);


                                    if (isset($ytitem->id->videoId) == false) {
                                        echo 'this does not have id ? ';
                                        continue;
                                    }
                                    $its[$i_for_its]['source'] = $ytitem->id->videoId;
                                    $its[$i_for_its]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
                                    $its[$i_for_its]['type'] = "youtube";

                                    $aux = $ytitem->snippet->title;
                                    $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                                    $aux = str_replace($lb, ' ', $aux);
                                    $its[$i_for_its]['title'] = $aux;

                                    $aux = $ytitem->snippet->description;
                                    $lb = array("\r\n","\n","\r");
                                    $aux = str_replace($lb,'<br>',$aux);
                                    $lb = array('"');
                                    $aux = str_replace($lb,'&quot;',$aux);
                                    $lb = array("'");
                                    $aux = str_replace($lb,'&#39;',$aux);


                                    $auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

                                    $its[$i_for_its]['description'] = $auxcontent;
                                    $its[$i_for_its]['menuDescription'] = $auxcontent;

//                    print_r($its['settings']);
                                    if ($its['settings']['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
                                        $its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;
                                    }

                                    $i_for_its++;

                                    $found_for_cache = true;

                                }


                            } else {

                                array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboard videos to be found') . '</div>');
                            }

                        }






                        if ($its['settings']['youtubefeed_maxvideos'] === 'all') {

                            if (isset($obj->nextPageToken) && $obj->nextPageToken) {
                                $nextPageToken = $obj->nextPageToken;
                            } else {

                                $nextPageToken = '';
                                break;
                            }

                        } else {
                            $nextPageToken = '';
                            break;
                        }


                    }else{

                        array_push($this->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboards ida found '.$target_file) . '</div>');
                    }
                    $breaker++;
                }



                if($found_for_cache){

                    $sw34 = false;
                    $auxa34 = array(
                        'id' => $targetfeed
                    , 'items' => $its
                    , 'time' => $_SERVER['REQUEST_TIME']
                    , 'maxlen' => $its['settings']['youtubefeed_maxvideos']

                    );

                    if (!is_array($cacher)) {
                        $cacher = array();
                    } else {


                        foreach ($cacher as $lab => $cach) {
                            if ($cach['id'] == $targetfeed) {
                                $sw34 = true;

                                $cacher[$lab] = $auxa34;

                                update_option('dzsvg_cache_ytkeywords', $cacher);

//                                        print_r($cacher);
                                break;
                            }
                        }


                    }


                    if ($sw34 == false) {

                        array_push($cacher, $auxa34);

//                                            print_r($cacher);

                        update_option('dzsvg_cache_ytkeywords', $cacher);
                    }
                }



            }


        }
        //=======END youtube keywords
        //
        //














        // -- start vimeo user channel //http://vimeo.com/api/v2/blakewhitman/videos.json
        if (($its['settings']['feedfrom'] == 'vmuserchannel') && $its['settings']['vimeofeed_user'] != '') {






            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            $target_file = "http://vimeo.com/api/v2/".$its['settings']['vimeofeed_user']."/videos.json";

            $cacher = get_option('cache_dzsvg_vmuser');

            if ($cacher == '') {
                $cacher = array();
            }
            if (count($cacher) == 0 || $this->mainoptions['disable_api_caching'] == 'on') {


                $ida = '';

                // -- oAuth 2
                if ($this->mainoptions['vimeo_api_user_id'] != '' && $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {



                    if (!class_exists('VimeoAPIException')) {
                        require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                    }


                    $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                    $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                    $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                    $token = $this->mainoptions['vimeo_api_access_token'];

                    // Do an authentication call
                    $vimeo = new Vimeo($consumer_key,$consumer_secret);
                    $vimeo->setToken($token); //,$token_secret
//                    $vimeo->user_id = $vimeo_id;
//                        echo $this->mainoptions['disable_api_caching'].'hmmdada/channels/' . $its['settings']['vimeofeed_channel'];

                    $vimeo_response = $vimeo->request('/users/'.$its['settings']['vimeofeed_user'].'/videos?per_page='.$vimeo_maxvideos);
//                            print_r($vimeo_response);



                    if ($vimeo_response['status'] != 200) {
//                        throw new Exception($channel_videos['body']['message']);
                        echo 'vimeo error';
                    }

                    if (isset($vimeo_response['body']['data'])) {
                        $ida = $vimeo_response['body']['data'];
                    }


                } else {
                    $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                }

                $jida = $ida;
                if (is_array($ida)) {
                    $jida = json_encode($ida);
                }

                $cache_mainaux = array();
                $cache_aux = array(
                    'output' => $jida
                ,'username' => $its['settings']['vimeofeed_user']
                ,'time' => $_SERVER['REQUEST_TIME']
                );
                array_push($cache_mainaux,$cache_aux);
                update_option('cache_dzsvg_vmuser',$cache_mainaux);
            } else {
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $its['settings']['vimeofeed_user']) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
                                break;
                            }
                        }
                    }
                    if ($ik > -1) {
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = '';
                        if ($this->mainoptions['vimeo_api_user_id'] != '' && $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {



                            if (!class_exists('VimeoAPIException')) {
                                require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                            }


                            $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                            $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                            $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                            $token = $this->mainoptions['vimeo_api_access_token'];

                            // Do an authentication call
                            $vimeo = new Vimeo($consumer_key,$consumer_secret);
                            $vimeo->setToken($token); //,$token_secret
                            //                    $vimeo->user_id = $vimeo_id;
                            $vimeo_response = $vimeo->request('/users/'.$its['settings']['vimeofeed_user'].'/videos?per_page='.$vimeo_maxvideos);
//                            print_r($vimeo_response);
                            if ($vimeo_response['status'] != 200) {
                                throw new Exception($vimeo_response['body']['message']);
                            }
                            if (isset($vimeo_response['body']['data'])) {
                                $ida = $vimeo_response['body']['data'];
                            }



                        } else {
                            $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                        }


                        $jida = $ida;
                        if (is_array($ida)) {
                            $jida = json_encode($ida);
                        }

                        //=== we test if we already have the username - but old call - to replace that old call
                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $its['settings']['vimeofeed_user']) {
                                $ik = $i;
                                break;
                            }
                        }
                        $cache_aux = array(
                            'output' => $jida
                        ,'username' => $its['settings']['vimeofeed_user']
                        ,'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher,$cache_aux);
                        }
                        update_option('cache_dzsvg_vmuser',$cacher);
                    }
                }
            }
            $idar = array();

            if ($this->mainoptions['debug_mode'] == 'on') {
                echo 'debug mode: ida is...<br>';
                print_r($ida);
                echo '<br/>';
                echo 'ida is object ';
                echo is_object($ida);
            }


//            print_r($ida);
            if (!is_object($ida) && !is_array($ida)) {
                $idar = json_decode($ida); // === vmuser
            } else {
                $idar = $ida;
            }

            $i = 0;

            if ($this->mainoptions['debug_mode'] == 'on') {
                echo 'debug mode: idar is...<br>';
                print_r($idar);
                echo '<br/>';
            }
            if (is_array($idar)) {
//                echo 'idararray'; print_r($idar); echo 'idaarrayend';
                foreach ($idar as $item) {

                    if(is_object($item)){
//                        echo 'cev23a';
                        $item = (array) $item;
                    }

                    if(isset($item['uri'])){
                        $auxa = explode('/',$item['uri']);
                    }
                    if(isset($item['url'])){
                        $auxa = explode('/',$item['url']);
                    }
                    $its[$i]['source'] = $auxa[count($auxa) - 1];


//                    print_r($item);


                    $vimeo_quality_ind = 2;

                    if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                        $vimeo_quality_ind = 3;
                    }

                    if($this->mainoptions['vimeo_thumb_quality']=='high'){

                        $vimeo_quality_ind = 4;
                    }

//                    print_r($item);

                    if(isset($item['pictures']) && is_object($item['pictures'])){
                        $item['pictures'] = (array) $item['pictures'];
                        if(is_object($item['pictures']['sizes'])){
                            $item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
                        }

                        if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
                            $item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
                        }
                        $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                    }else{

                        if(isset($item['thumbnail_medium'])){

                            $its[$i]['thethumb'] = $item['thumbnail_medium'];
                        }
                        if(isset($item['pictures']['sizes'][$vimeo_quality_ind]['link'])){

                            $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                        }
                    }
                    $its[$i]['type'] = "vimeo";

                    $aux = 'title';
                    if(isset($item['name'])){
                        $aux = $item['name'];

                    }
                    if(isset($item['title'])){
                        $aux = $item['title'];
                    }
                    $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                    $aux = str_replace($lb,' ',$aux);
                    $its[$i]['title'] = $aux;


                    $aux = $item['description'];

                    if($its['settings']['striptags']=='on'){
                        $aux = strip_tags($aux);
                    }

                    $lb = array("\r\n","\n","\r");
                    $aux = str_replace($lb,'<br>',$aux);
                    $lb = array('"');
                    $aux = str_replace($lb,'&quot;',$aux);
                    $lb = array("'");
                    $aux = str_replace($lb,'&#39;',$aux);
                    $its[$i]['description'] = $aux;
                    $its[$i]['menuDescription'] = $aux;
                    $i++;
                }
            } else {
                if (is_object($idar)) {
                    if (isset($idar->videos->video)) {
                        foreach ($idar->videos->video as $item) {

                            if(is_object($item)){
//                        echo 'cev23a';
                                $item = (array) $item;
                            }

                            if(isset($item['uri'])){
                                $auxa = explode('/',$item['uri']);
                            }
                            if(isset($item['url'])){
                                $auxa = explode('/',$item['url']);
                            }
                            $its[$i]['source'] = $auxa[count($auxa) - 1];




                            $vimeo_quality_ind = 2;

                            if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                                $vimeo_quality_ind = 3;
                            }

                            if($this->mainoptions['vimeo_thumb_quality']=='high'){

                                $vimeo_quality_ind = 4;
                            }

//                            print_r($item);
                            if(is_object($item['pictures'])){
                                $item['pictures'] = (array) $item['pictures'];
                                if(is_object($item['pictures']['sizes'])){
                                    $item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
                                }

                                if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
                                    $item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
                                }
//                        print_r($item);
                                $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                            }else{

                                $its[$i]['thethumb'] = $item['thumbnail_medium'];
                            }
                            $its[$i]['type'] = "vimeo";

                            $aux = 'title';
                            if(isset($item['name'])){
                                $aux = $item['name'];

                            }
                            if(isset($item['title'])){
                                $aux = $item['title'];
                            }
                            $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                            $aux = str_replace($lb,' ',$aux);
                            $its[$i]['title'] = $aux;


                            $aux = $item['description'];
                            if($its['settings']['striptags']=='on'){
                                $aux = strip_tags($aux);
                            }
                            $lb = array("\r\n","\n","\r");
                            $aux = str_replace($lb,'<br>',$aux);
                            $lb = array('"');
                            $aux = str_replace($lb,'&quot;',$aux);
                            $lb = array("'");
                            $aux = str_replace($lb,'&#39;',$aux);
                            $its[$i]['description'] = $aux;
                            $its[$i]['menuDescription'] = $aux;
                            $i++;
                        }
                    } else {

                        echo '<div class="error">error: vimeo api, no videos...</div>';
                    }
                } else {
                    echo '<div class="error">error: <a href="'.$target_file.'">this</a> returned nothing useful</div>';
                }
            }
        }

        // END vmchanneluser



//        print_r($its);



        //------start vmchannel //http://vimeo.com/api/v2/blakewhitman/videos.json
        // -- VIMEO CHANNEL
        if (($its['settings']['feedfrom'] == 'vmchannel') && $its['settings']['vimeofeed_channel'] != '') {
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            $target_file = "http://vimeo.com/api/v2/channel/".$its['settings']['vimeofeed_channel']."/videos.json";


            $cacher = get_option('cache_dzsvg_vmchannel');

            if ($cacher == '') {
                $cacher = array();
            }

            if (count($cacher) == 0 || $this->mainoptions['disable_api_caching'] == 'on') {
                $ida = '';
                if ($this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '' ) {



                    if (!class_exists('Vimeo')) {
                        require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                    }

                    $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                    $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                    $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                    $token = $this->mainoptions['vimeo_api_access_token'];

                    // Do an authentication call
                    $vimeo = new Vimeo($consumer_key,$consumer_secret);
                    $vimeo->setToken($token); //,$token_secret
//                    $vimeo->user_id = $vimeo_id;
                    $vimeo_response = $vimeo->request('/channels/'.$its['settings']['vimeofeed_channel'].'/videos?per_page='.$vimeo_maxvideos);
                    if ($vimeo_response['status'] != 200) {
                        throw new Exception($vimeo_response['body']['message']);
                    }
                    if (isset($vimeo_response['body']['data'])) {
                        $ida = $vimeo_response['body']['data'];
                    }
                } else {
                    $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                }



                if ($this->mainoptions['debug_mode'] == 'on') {
                    echo 'debug mode: mode vimeo channell target file - '.$target_file
                        .'<br>ida is:';
                    print_r($ida);
                }


                $jida = $ida;
                if (is_array($ida)) {
                    $jida = json_encode($ida);
                }
                if ($this->mainoptions['disable_api_caching'] != 'on') {
                    $cache_mainaux = array();
                    $cache_aux = array(
                        'output' => $jida
                    ,'username' => $its['settings']['vimeofeed_channel']
                    ,'time' => $_SERVER['REQUEST_TIME']
                    );
                    array_push($cache_mainaux,$cache_aux);
                    update_option('cache_dzsvg_vmchannel',$cache_mainaux);
                }
            } else {

                // -- channel
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $its['settings']['vimeofeed_channel']) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
//                                print_r($cacher[$i]['username']);
                                break;
                            }
                        }
                    }
                    if ($ik > -1) {
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = '';
                        if ($this->mainoptions['vimeo_api_user_id'] != '' && $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {



                            if (!class_exists('VimeoAPIException')) {
                                require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                            }

                            $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                            $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                            $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                            $token = $this->mainoptions['vimeo_api_access_token'];

                            // Do an authentication call
                            $vimeo = new Vimeo($consumer_key,$consumer_secret);
                            $vimeo->setToken($token); //,$token_secret


                            $vimeo_response = $vimeo->request('/channels/'.$its['settings']['vimeofeed_channel'].'/videos?per_page='.$vimeo_maxvideos);
                            if ($vimeo_response['status'] != 200) {
                                throw new Exception($vimeo_response['body']['message']);
                            }
                            if (isset($vimeo_response['body']['data'])) {
                                $ida = $vimeo_response['body']['data'];
                            }



                        } else {
                            $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                        }



                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $its['settings']['vimeofeed_channel']) {
                                $ik = $i;
                                break;
                            }
                        }


                        $jida = $ida;
                        if (is_array($ida)) {
                            $jida = json_encode($ida);
                        }
                        $cache_aux = array(
                            'output' => $jida
                        ,'username' => $its['settings']['vimeofeed_channel']
                        ,'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher,$cache_aux);
                        }
                        update_option('cache_dzsvg_vmchannel',$cacher);
                    }
                }
            }


            if (!is_object($ida) && !is_array($ida)) {
                $idar = json_decode($ida); // === vmuser
            } else {
                $idar = $ida;
            }


            $i = 0;


            if ($this->mainoptions['debug_mode'] == 'on') {
                echo 'debug mode: idar is...';
                print_r($idar);
            }


//            print_r($idar);
            if (is_array($idar)) {
                foreach ($idar as $item) {
                    if(is_object($item)){
//                        echo 'cev23a';
                        $item = (array) $item;
                    }
//                    print_r($item);

                    $auxa = array();
                    if(isset($item['uri'])){
                        $auxa = explode('/',$item['uri']);
                    }
                    if(isset($item['url'])){
                        $auxa = explode('/',$item['url']);
                    }
                    $its[$i]['source'] = $auxa[count($auxa) - 1];

//                    print_r($item['pictures']);





                    $vimeo_quality_ind = 2;

                    if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                        $vimeo_quality_ind = 3;
                    }

                    if($this->mainoptions['vimeo_thumb_quality']=='high'){

                        $vimeo_quality_ind = 4;
                    }

                    if(is_object($item['pictures'])){
                        $item['pictures'] = (array) $item['pictures'];
                        if(is_object($item['pictures']['sizes'])){
                            $item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
                        }

                        if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
                            $item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
                        }
                        $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                    }else{

//                        if(isset($item['thumbnail_medium'])){
//
//                            $its[$i]['thethumb'] = $item['thumbnail_medium'];
//                        }
                        if(isset($item['thumbnail_large'])){

                            $its[$i]['thethumb'] = $item['thumbnail_large'];
                        }
                        if(isset($item['pictures']['sizes'][$vimeo_quality_ind]['link'])){

                            $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                        }


//                        echo $its[$i]['thethumb'];
                    }
                    $its[$i]['type'] = "vimeo";


                    if(isset($item['name'])){
                        $aux = $item['name'];

                    }
                    if(isset($item['title'])){
                        $aux = $item['title'];
                    }




                    $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                    $aux = str_replace($lb,' ',$aux);
                    $its[$i]['title'] = $aux;


                    $aux = $item['description'];
                    if($its['settings']['striptags']=='on'){
                        $aux = strip_tags($aux);
                    }
                    $lb = array("\r\n","\n","\r");
                    $aux = str_replace($lb,'<br>',$aux);
                    $lb = array('"');
                    $aux = str_replace($lb,'&quot;',$aux);
                    $lb = array("'");
                    $aux = str_replace($lb,'&#39;',$aux);
                    $its[$i]['description'] = $aux;
                    $its[$i]['menuDescription'] = $aux;
                    $i++;
                }
            } else {
                if (is_object($idar)) {
                    print_r($idar);
                    if (isset($idar->videos->video)) {
                        foreach ($idar->videos->video as $item) {
                            $its[$i]['source'] = $item->id;
                            $its[$i]['thethumb'] = $item->thumbnails->thumbnail[0]->_content;
                            $its[$i]['type'] = "vimeo";

                            $aux = $item->title;
                            $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                            $aux = str_replace($lb,' ',$aux);
                            $its[$i]['title'] = $aux;

                            $aux = $item->description;
                            $lb = array("\r\n","\n","\r","&",'???');
                            $aux = str_replace($lb,' ',$aux);
                            $lb = array('"');
                            $aux = str_replace($lb,'&quot;',$aux);
                            $lb = array("'");
                            $aux = str_replace($lb,'&#39;',$aux);
                            $its[$i]['menuDescription'] = $aux;
                            $i++;
                        }
                    } else {

                        echo '<div class="error">error: vimeo api, no videos...</div>';
                    }
                } else {
                    echo '<div class="error">error: <a href="'.$target_file.'">this</a> returned nothing useful</div>';
                }
            }
//            print_r($its);
        }
        // -- end vmchannel





        //------start vmalbum //http://vimeo.com/api/v2/blakewhitman/videos.json
        if (($its['settings']['feedfrom'] == 'vmalbum') && $its['settings']['vimeofeed_vmalbum'] != '') {
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }


            $target_file = "http://vimeo.com/api/v2/album/".$its['settings']['vimeofeed_vmalbum']."/videos.json";



            $cacher = get_option('cache_dzsvg_vmalbum');

            if ($cacher == '') {
                $cacher = array();
            }

            if (count($cacher) == 0 || $this->mainoptions['disable_api_caching'] == 'on') {
                $ida = '';
                if ($this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '' ) {



                    if (!class_exists('Vimeo')) {
                        require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                    }

                    $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                    $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                    $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                    $token = $this->mainoptions['vimeo_api_access_token'];

                    // Do an authentication call
                    $vimeo = new Vimeo($consumer_key,$consumer_secret);
                    $vimeo->setToken($token); //,$token_secret
                    $vimeo_response = $vimeo->request('/albums/'.$its['settings']['vimeofeed_vmalbum'].'/videos?per_page='.$vimeo_maxvideos);


                    if ($this->mainoptions['debug_mode'] == 'on') {

                        echo 'debug mode: mode vimeo album - making autetificated call - '
                            .'<br>$vimeo_response is:';
                        print_r($vimeo_response);
                    }

                    if ($vimeo_response['status'] != 200) {
                        error_log('dzsvg.php line 4023: '.$vimeo_response['body']['message']);
                    }
                    if (isset($vimeo_response['body']['data'])) {
                        $ida = $vimeo_response['body']['data'];
                    }
                } else {
                    $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                }



                if ($this->mainoptions['debug_mode'] == 'on') {
                    echo 'debug mode: mode vimeo album target file - '
                        .'<br>vimeo_response is:';
                    print_r($ida);
                }


                $jida = $ida;
                if (is_array($ida)) {
                    $jida = json_encode($ida);
                }
                if ($this->mainoptions['disable_api_caching'] != 'on') {
                    $cache_mainaux = array();
                    $cache_aux = array(
                        'output' => $jida
                    ,'username' => $its['settings']['vimeofeed_vmalbum']
                    ,'time' => $_SERVER['REQUEST_TIME']
                    );
                    array_push($cache_mainaux,$cache_aux);
                    update_option('cache_dzsvg_vmalbum',$cache_mainaux);
                }
            } else {
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $its['settings']['vimeofeed_vmalbum']) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
//                                print_r($cacher[$i]['username']);
                                break;
                            }
                        }
                    }
                    if ($ik > -1) {
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = '';
                        if ($this->mainoptions['vimeo_api_user_id'] != '' && $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {



                            if (!class_exists('VimeoAPIException')) {
                                require_once(dirname(__FILE__).'/vimeoapi/vimeo.php');
                            }

                            $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
                            $consumer_key = $this->mainoptions['vimeo_api_client_id'];
                            $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
                            $token = $this->mainoptions['vimeo_api_access_token'];

                            // Do an authentication call
                            $vimeo = new Vimeo($consumer_key,$consumer_secret);
                            $vimeo->setToken($token); //,$token_secret


                            $vimeo_response = $vimeo->request('/albums/'.$its['settings']['vimeofeed_vmalbum'].'/videos?per_page='.$vimeo_maxvideos);
                            if ($vimeo_response['status'] != 200) {
                                throw new Exception($vimeo_response['body']['message']);
                            }
                            if (isset($vimeo_response['body']['data'])) {
                                $ida = $vimeo_response['body']['data'];
                            }



                        } else {
                            $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
                        }



                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $its['settings']['vimeofeed_vmalbum']) {
                                $ik = $i;
                                break;
                            }
                        }


                        $jida = $ida;
                        if (is_array($ida)) {
                            $jida = json_encode($ida);
                        }
                        $cache_aux = array(
                            'output' => $jida
                        ,'username' => $its['settings']['vimeofeed_vmalbum']
                        ,'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher,$cache_aux);
                        }
                        update_option('cache_dzsvg_vmalbum',$cacher);
                    }
                }
            }


            if (!is_object($ida) && !is_array($ida)) {
                $idar = json_decode($ida); // === vmuser
            } else {
                $idar = $ida;
            }


            $i = 0;


            if ($this->mainoptions['debug_mode'] == 'on') {
                echo 'debug mode: idar is...';
                print_r($idar);
            }


//            print_r($idar);
            if (is_array($idar)) {
                foreach ($idar as $item) {
                    if(is_object($item)){
//                        echo 'cev23a';
                        $item = (array) $item;
                    }
//                    print_r($item);


                    if(isset($item['uri'])){
                        $auxa = explode('/',$item['uri']);
                    }
                    if(isset($item['url'])){
                        $auxa = explode('/',$item['url']);
                    }





                    $vimeo_quality_ind = 2;

                    if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                        $vimeo_quality_ind = 3;
                    }

                    if($this->mainoptions['vimeo_thumb_quality']=='high'){

                        $vimeo_quality_ind = 4;
                    }

                    $its[$i]['source'] = $auxa[count($auxa) - 1];
                    if(is_object($item['pictures'])){
                        $item['pictures'] = (array) $item['pictures'];
                        if(is_object($item['pictures']['sizes'])){
                            $item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
                        }

                        if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
                            $item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
                        }
                        $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                    }else{


                        if(is_array($item['pictures'])){
                            $item['pictures'] = (array) $item['pictures'];
                            if(is_array($item['pictures']['sizes'])){
                                $item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
                            }

                            if(is_array($item['pictures']['sizes'][$vimeo_quality_ind])){
                                $item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
                            }
                            $its[$i]['thethumb'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                        }else{

                            $its[$i]['thethumb'] = $item['thumbnail_medium'];
                        }

//                        print_r($item);
                    }

                    $its[$i]['type'] = "vimeo";


                    if(isset($item['name'])){
                        $aux = $item['name'];

                    }
                    if(isset($item['title'])){
                        $aux = $item['title'];
                    }


                    $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                    $aux = str_replace($lb,' ',$aux);
                    $its[$i]['title'] = $aux;

                    $aux = $item['description'];
                    if($its['settings']['striptags']=='on'){
                        $aux = strip_tags($aux);
                    }
                    $lb = array("\r\n","\n");
                    $aux = str_replace($lb,'<br>',$aux);
                    $lb = array('"');
                    $aux = str_replace($lb,'&quot;',$aux);
                    $lb = array("'");
                    $aux = str_replace($lb,'&#39;',$aux);
                    $its[$i]['description'] = $aux;
                    $its[$i]['menuDescription'] = $aux;
                    $i++;
                }
            } else {
                if (is_object($idar)) {
                    if (isset($idar->videos->video)) {
                        foreach ($idar->videos->video as $item) {
                            $its[$i]['source'] = $item->id;
                            $its[$i]['thethumb'] = $item->thumbnails->thumbnail[0]->_content;
                            $its[$i]['type'] = "vimeo";

                            $aux = $item->title;
                            $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                            $aux = str_replace($lb,' ',$aux);
                            $its[$i]['title'] = $aux;

                            $aux = $item->description;
                            $lb = array("\r\n","\n","\r");
                            $aux = str_replace($lb,'<br>',$aux);
                            $lb = array('"');
                            $aux = str_replace($lb,'&quot;',$aux);
                            $lb = array("'");
                            $aux = str_replace($lb,'&#39;',$aux);
                            $its[$i]['menuDescription'] = $aux;
                            $i++;
                        }
                    } else {

                        echo '<div class="error">error: vimeo api, no videos...</div>';
                    }
                } else {
                    echo '<div class="error">error: <a href="'.$target_file.'">this</a> returned nothing useful</div> ';

                    echo '<div style="display:none;">';
                    print_r($ida);
                    echo '</div>';
                }
            }
//            print_r($its);
        }








        if ($its['settings']['randomize'] == 'on' && is_array($its)) {

            $backup_its = $its;
//print_r($its); $rand_keys = array_rand($its, count($its)); print_r($rand_keys);
            shuffle($its);
//print_r($its);print_r($backup_its);

            for ($i = 0; $i < count($its); $i++) {
                if (isset($its[$i]['feedfrom'])) {
                    //print_r($it);

                    unset($its[$i]);
                }
            }
            $its['settings'] = $backup_its['settings'];
            $its = array_reverse($its);
//print_r($its);
        }

        if (isset($its['settings']['order']) && $its['settings']['order'] == 'DESC') {
            $its = array_reverse($its);
        }

        // --- items settled

        if($margs['return_mode']=='items'){
            return $its;
        }


        foreach($extra_galleries as $extragal){
            $args = array(
                'id' => $extragal,
                'return_mode' => 'items',

            );

//            print_r($this->show_shortcode($args));


            foreach($this->show_shortcode($args) as $lab=>$it3){
                if($lab==='settings'){
                    continue;
                }
                array_push($its,$it3);
            }
//            $fout.=$this->show_shortcode($args);
//            print_r($its);
        }


        // --- if display mode is wall, it cannot be shown on a laptop, and height needs to be set to auto
        if ($its['settings']['displaymode'] == 'wall') {
            $its['settings']['laptopskin'] = 'off';
            $its['settings']['height'] = 'auto';
        }

        // ------- some sanitizing
        $tw = $its['settings']['width'];
        $th = $its['settings']['height'];




        $etw = $tw;
        $eth = $th;



        if (strpos($tw,"%") === false) {
            $tw = $tw.'px';
        }
        if (strpos($th,"%") === false && $th != 'auto') {
            $th = $th.'px';
        }

        if (strpos($its['settings']['facebooklink'],"{currurl}") !== false) {
            $its['settings']['facebooklink'] = str_replace('{currurl}',urlencode(dzs_curr_url()),$its['settings']['facebooklink']);
        }



        if ($margs['fullscreen'] == 'on') {
            $tw = '100%';
            $th = '100%';
        }



//        echo 'ceva'; echo $its['settings']['skin_html5vg'];
        if ($its['settings']['skin_html5vg'] == 'skin_custom') {
            $fout.='<style>';
            $fout.='.vg'.$this->sliders_index.'.videogallery { background:'.$this->mainoptions_dc['background'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .navigationThumb{ background: '.$this->mainoptions_dc['thumbs_bg'].'; } ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .navigationThumb.active{ background-color: '.$this->mainoptions_dc['thumbs_active_bg'].'; } ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .navigationThumb{ color: '.$this->mainoptions_dc['thumbs_text_color'].'; } .vg'.$this->sliders_index.'.videogallery .navigationThumb .the-title{ color: '.$this->mainoptions_dc['thumbs_text_color'].'; } ';

            if ($this->mainoptions_dc['thumbnail_image_width'] != '') {
                $fout.='.vg'.$this->sliders_index.'.videogallery .imgblock{ width: '.$this->mainoptions_dc['thumbnail_image_width'].'px; } ';
            }

            if ($this->mainoptions_dc['thumbnail_image_height'] != '') {
                $fout.='.vg'.$this->sliders_index.'.videogallery .imgblock{ height: '.$this->mainoptions_dc['thumbnail_image_height'].'px; } ';
            }


            $fout.='</style>';
        }



        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $fout.='<style>';
            $fout.='.vg'.$this->sliders_index.'.videogallery .background{ background-color:'.$this->mainoptions_dc['controls_background'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .scrub-bg{ background-color:'.$this->mainoptions_dc['scrub_background'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .scrub-buffer{ background-color:'.$this->mainoptions_dc['scrub_buffer'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .playSimple{ border-left-color:'.$this->mainoptions_dc['controls_color'].';} .vg'.$this->sliders_index.'.videogallery .stopSimple .pause-part-1{ background-color: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .stopSimple .pause-part-2{ background-color: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .volumeicon{ background: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .volumeicon:before{ border-right-color: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .volume_static{ background: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .hdbutton-con .hdbutton-normal{ color: '.$this->mainoptions_dc['controls_color'].'; } .vg'.$this->sliders_index.'.videogallery .total-timetext{ color: '.$this->mainoptions_dc['controls_color'].'; } ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .playSimple:hover{ border-left-color: '.$this->mainoptions_dc['controls_hover_color'].'; } .vg'.$this->sliders_index.'.videogallery .stopSimple:hover .pause-part-1{ background-color: '.$this->mainoptions_dc['controls_hover_color'].'; } .vg'.$this->sliders_index.'.videogallery .stopSimple:hover .pause-part-2{ background-color: '.$this->mainoptions_dc['controls_hover_color'].'; } .vg'.$this->sliders_index.'.videogallery .volumeicon:hover{ background: '.$this->mainoptions_dc['controls_hover_color'].'; } .vg'.$this->sliders_index.'.videogallery .volumeicon:hover:before{ border-right-color: '.$this->mainoptions_dc['controls_hover_color'].'; } ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .volume_active{ background-color: '.$this->mainoptions_dc['controls_highlight_color'].'; } .vg'.$this->sliders_index.'.videogallery .scrub{ background-color: '.$this->mainoptions_dc['controls_highlight_color'].'; } .vg'.$this->sliders_index.'.videogallery .hdbutton-con .hdbutton-hover{ color: '.$this->mainoptions_dc['controls_highlight_color'].'; } ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .curr-timetext{ color: '.$this->mainoptions_dc['timetext_curr_color'].'; } ';
            $fout.='</style>';
        }
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
            $fout.='<style>';
            $fout.='.vg'.$this->sliders_index.'.videogallery .background{ background-color:'.$this->mainoptions_dc_aurora['controls_background'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .scrub-bg{ background-color:'.$this->mainoptions_dc_aurora['scrub_background'].';} ';
            $fout.='.vg'.$this->sliders_index.'.videogallery .scrub-buffer{ background-color:'.$this->mainoptions_dc_aurora['scrub_buffer'].';} ';

            $fout.='.vg'.$this->sliders_index.'.videogallery .vplayer .playSimple path{ fill:'.$this->mainoptions_dc_aurora['controls_color'].';}             .vg'.$this->sliders_index.'.videogallery .vplayer .pauseSimple path{ fill: '.$this->mainoptions_dc_aurora['controls_color'].'; }           .vg'.$this->sliders_index.'.videogallery .vplayer .fscreencontrols rect,.vg'.$this->sliders_index.'.videogallery .vplayer .fscreencontrols polygon { fill: '.$this->mainoptions_dc_aurora['controls_color'].'; }        .vg'.$this->sliders_index.'.videogallery .vplayer .volumeicon path{ fill: '.$this->mainoptions_dc_aurora['controls_color'].'; }       .vg'.$this->sliders_index.'.videogallery .vplayer .hdbutton-con .hdbutton-normal{ color: '.$this->mainoptions_dc_aurora['controls_color'].'; }  ';

            // -- hover
            $fout.='.vg'.$this->sliders_index.'.videogallery .vplayer .playcontrols:hover .playSimple path{ fill:'.$this->mainoptions_dc_aurora['controls_hover_color'].';}             .vg'.$this->sliders_index.'.videogallery .vplayer .playcontrols:hover .pauseSimple path{ fill: '.$this->mainoptions_dc_aurora['controls_hover_color'].'; }           .vg'.$this->sliders_index.'.videogallery .vplayer .fscreencontrols:hover rect,.vg'.$this->sliders_index.'.videogallery .vplayer .fscreencontrols:hover polygon { fill: '.$this->mainoptions_dc_aurora['controls_hover_color'].'; }        .vg'.$this->sliders_index.'.videogallery .vplayer .volumecontrols:hover .volumeicon path{ fill: '.$this->mainoptions_dc_aurora['controls_hover_color'].'; }       .vg'.$this->sliders_index.'.videogallery .vplayer .hdbutton-con:hover .hdbutton-normal{ color: '.$this->mainoptions_dc_aurora['controls_hover_color'].'; }  ';



            $fout.='.vg'.$this->sliders_index.'.videogallery .volume_active{ background-color: '.$this->mainoptions_dc_aurora['controls_highlight_color'].'; } .vg'.$this->sliders_index.'.videogallery .scrub{ background-color: '.$this->mainoptions_dc_aurora['controls_highlight_color'].'; } .vg'.$this->sliders_index.'.videogallery .hdbutton-con .hdbutton-hover{ color: '.$this->mainoptions_dc_aurora['controls_highlight_color'].'; } ';
            $fout.='</style>';
        }





        $fout.='<div class="gallery-precon gp'.$this->sliders_index.'';
        if ($margs['fullscreen'] == 'on') {
            $fout.=' gallery-is-fullscreen';
        }


        $str_h = 'auto';
        if ($margs['fullscreen'] == 'on') {
            $str_h='100%';
        }

        $fout.='" style="width:'.$tw.';height:'.$str_h.';';

        if ($margs['fullscreen'] == 'on') {
            $fout.=' position:'.'fixed'.'; z-index:50005; top:0; left:0;';
        }
        if ($margs['category'] != '') {
//            $fout.=' display:none;"';
            $fout.='"';
            $fout.=' data-category="'.$margs['category'].'';
        }
        /*
         *
         */
        $fout.='"';
        $fout.='>';


        $menuitem_w = $its['settings']['html5designmiw'];
        $menuitem_h = $its['settings']['html5designmih'];
        $menuposition = ($its['settings']['menuposition']);
//        echo $menuposition;
        $html5mp = $menuposition;

        $jreadycall = 'jQuery(document).ready(function($)';
        if ($menuposition == 'right' || $menuposition == 'left') {
            //$tw -= $menuitem_w;
        }
        if ($menuposition == 'down' || $menuposition == 'up') {
            //$th -= $menuitem_h;
        }
        if ($menuposition == 'down') {
            $html5mp = 'bottom';
        }
        if ($menuposition == 'up') {
            $html5mp = 'top';
        }



        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {
            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';
            }else{

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }
        //echo $its['settings']['skin_html5vg'];

        if (!isset($its['settings']['fullscreen']) || $margs['fullscreen'] != 'on') {
            $fout.='<div class="videogallery-con';

            if (isset($its['settings']['laptopskin']) && $its['settings']['laptopskin'] == 'on') {
                $fout.=' skin-laptop';
                $its['settings']['totalheight'] = '';
                $th = '';
                $its['settings']['bgcolor'] = 'transparent';
            }

//            echo 'hmmdada';


            $str_th = '';


            if ($margs['fullscreen'] == 'on') {
                $str_th=' height: 100%;';
            }

            $fout.='" style="width:'.$tw.';'.$str_th.'">';

            if (isset($its['settings']['laptopskin']) && $its['settings']['laptopskin'] == 'on') {
                $fout.='<img class="thelaptopbg" src="'.$this->thepath.'videogallery/img/mb-body.png"/>';
            }
            $fout.='<div class="preloader"></div>';
        }

        $css_classid = str_replace(' ','_',$its['settings']['id']);


        foreach($this->arr_api_errors as $dzsvg_error){
            echo $dzsvg_error;
        }

        if (isset($its['settings']['enable_search_field']) && $its['settings']['enable_search_field']=='on' ) {
            if( !(  $its['settings']['displaymode'] == 'normal' && $its['settings']['nav_type']=='thumbs' && ( $html5mp=='left' || $html5mp=='right' ) )){

                $fout.='<div class="vg'.$this->sliders_index.'-search-field dzsvg-search-field outer"><input type="text" placeholder="'.__('Search').'..."/><svg class="search-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="230.042 230.042 15 15" enable-background="new 230.042 230.042 15 15" xml:space="preserve"> <g> <path fill="#898383" d="M244.708,243.077l-3.092-3.092c0.746-1.076,1.118-2.275,1.118-3.597c0-0.859-0.167-1.681-0.501-2.465 c-0.333-0.784-0.783-1.46-1.352-2.028s-1.244-1.019-2.027-1.352c-0.785-0.333-1.607-0.5-2.466-0.5s-1.681,0.167-2.465,0.5 s-1.46,0.784-2.028,1.352s-1.019,1.244-1.352,2.028s-0.5,1.606-0.5,2.465s0.167,1.681,0.5,2.465s0.784,1.46,1.352,2.028 s1.244,1.019,2.028,1.352c0.784,0.334,1.606,0.501,2.465,0.501c1.322,0,2.521-0.373,3.597-1.118l3.092,3.083 c0.217,0.229,0.486,0.343,0.811,0.343c0.312,0,0.584-0.114,0.812-0.343c0.228-0.228,0.342-0.499,0.342-0.812 C245.042,243.569,244.931,243.3,244.708,243.077z M239.241,239.241c-0.79,0.79-1.741,1.186-2.853,1.186s-2.062-0.396-2.853-1.186 c-0.79-0.791-1.186-1.741-1.186-2.853s0.396-2.063,1.186-2.853c0.79-0.791,1.741-1.186,2.853-1.186s2.062,0.396,2.853,1.186 s1.186,1.741,1.186,2.853S240.032,238.45,239.241,239.241z"/> </g> </svg> </div>';

            }
        }


        $fout.='<div class="vg'.$this->sliders_index.' videogallery id_'.$css_classid.' '.$skin_html5vg;



        if (isset($its['settings']['extra_classes']) && $its['settings']['extra_classes'] != '') {
            $fout.=' '.$its['settings']['extra_classes'].'';
        }


        $fout.='" style="background-color:'.$its['settings']['bgcolor'].'; width:'.$tw.'; height:'.$th.';">';
//<div class="vplayer-tobe" data-videoTitle="Pages"  data-description="<img src=thumbs/pages1.jpg class='imgblock'/><div class='the-title'>Pages</div>AE Project by Generator" data-sourcemp4="video/pages.mp4" data-sourceogg="video/pages.ogv" ><div class="videoDescription">You can have a description here if you choose to.</div></div>



        $fout.=$this->parse_items($its,$margs);
        $iout.=$this->parse_items($its,$margs);

//        foreach($extra_galleries as $extragal){
//            $args = array(
//                'id' => $extragal,
//                'return_mode' => 'items',
//
//            );
//
//            array_push($its,$this->show_shortcode($args));
////            $fout.=$this->show_shortcode($args);
//        }

        $html5vgautoplay = 'off';
        if ($its['settings']['autoplay'] == 'on') {
            $html5vgautoplay = 'on';
        }

        if (!isset($its['settings']['fullscreen']) || $its['settings']['fullscreen'] != 'on') {
            $fout.= '</div>';
        }
        $fout.='</div>
<script>
var videoplayersettings = {
autoplay : "off",
controls_out_opacity : 0.9,
controls_normal_opacity : 0.9
,settings_swfPath : "'.$this->thepath.'preview.swf"';

        $fout.='}
';
        if ($its['settings']['displaymode'] == 'wall') {
            $fout.='window.zoombox_videoplayersettings = videoplayersettings;';


            wp_enqueue_style('dzsap',$this->thepath.'assets/audioplayer/audioplayer.css');
            wp_enqueue_script('dzsap',$this->thepath.'assets/audioplayer/audioplayer.js');
        }

        $fout.=$jreadycall.'{
videoplayersettings.design_skin = "'.$skin_vp.'";
videoplayersettings.settings_youtube_usecustomskin = "'.$its['settings']['yt_customskin'].'";
videoplayersettings.controls_normal_opacity = "'.$its['settings']['html5design_controlsopacityon'].'";
videoplayersettings.controls_out_opacity = "'.$its['settings']['html5design_controlsopacityout'].'";
videoplayersettings.settings_video_overlay = "'.$its['settings']['settings_video_overlay'].'";';

        if (isset($its['settings']['youtube_sdquality'])) {
            $fout.='videoplayersettings.youtube_sdQuality = "'.$its['settings']['youtube_sdquality'].'";';
        }if (isset($its['settings']['youtube_hdquality'])) {
            $fout.='videoplayersettings.youtube_hdQuality = "'.$its['settings']['youtube_hdquality'].'";';
        }if (isset($its['settings']['youtube_defaultquality'])) {
            $fout.='videoplayersettings.youtube_defaultQuality = "'.$its['settings']['youtube_defaultquality'].'";';
        }



        if(isset($its['settings']['settings_video_end_reset_time']) && $its['settings']['settings_video_end_reset_time']=='off'){
            $fout.='videoplayersettings.settings_video_end_reset_time="off";';
        }

        if (isset($its['settings']['rtmp_streamserver'])) {
            $fout.='videoplayersettings.rtmp_streamServer = "'.$its['settings']['rtmp_streamserver'].'";';
        }

        if(isset($vpsettings['settings']['settings_ios_usecustomskin'])){
            $fout.='videoplayersettings.settings_ios_usecustomskin = "'.$its['settings']['settings_ios_usecustomskin'].'";';

        }
        if(isset($vpsettings['settings']['ga_enable_send_play'])){
            $fout.='videoplayersettings.ga_enable_send_play = "'.$its['settings']['ga_enable_send_play'].'";';

        }


        if(isset($its['settings']['set_responsive_ratio_to_detect']) && $its['settings']['set_responsive_ratio_to_detect']=='on'){
            $fout.='videoplayersettings.responsive_ratio = "detect";';

        }

        if($this->mainoptions['analytics_enable']=='on'){

            $vals = $this->mainoptions['analytics_galleries'];

            foreach($vals as $lab =>$val){

                if($lab===$k){

                    $fout.='videoplayersettings.action_video_view = window.dzsvg_wp_send_view;';
                    $fout.='videoplayersettings.action_video_contor_10secs = window.dzsvg_wp_send_contor_10_secs;';
                }
            }
        }


        $fout.='console.info("DZSVG_INIT ", $(".vg'.$this->sliders_index.'"));';

        $fout.=' dzsvg_init(".vg'.$this->sliders_index.'",{
menuSpace:0
,randomise:"off"
,settings_menu_overlay:"on"
,totalWidth : "'.$tw.'"';
        if (isset($its['settings']['totalheight']) && $its['settings']['totalheight'] != '') {
            $fout.=',totalHeight : "'.$th.'"';
        }

        if (isset($its['settings']['forcevideoheight']) && $its['settings']['forcevideoheight'] != '') {
            $fout.=',forceVideoHeight : "'.$its['settings']['forcevideoheight'].'"';
        }


        if ($this->mainoptions['settings_trigger_resize'] == 'on') {
            $fout.=',settings_trigger_resize:"1000"';
        };

        $fout.=',autoplay :"'.$html5vgautoplay.'"
,autoplayNext : "'.$its['settings']['autoplaynext'].'"
,nav_type : "'.$its['settings']['nav_type'].'"
,menuitem_width:"'.$menuitem_w.'"
,menuitem_space:"'.$its['settings']['html5designmis'].'"
,menuitem_height:"'.$menuitem_h.'"
,modewall_bigwidth:"900"
,modewall_bigheight:"500"
';
        if (isset($its['settings']['nav_space'])) {
            $fout.=',nav_space: "'.$its['settings']['nav_space'].'"';
        }
        if ($margs['settings_separation_mode'] == 'scroll' || $margs['settings_separation_mode'] == 'button') {
            $fout.=',settings_separation_mode: "'.$margs['settings_separation_mode'].'"';
            $fout.=',settings_separation_pages: [';
            for ($i = 1; $i < (ceil(count($its) - 1) / intval($margs['settings_separation_pages_number']) ); $i++) {

                if ($i > 1) {
                    $fout.=',';
                }
                $aux_args = $margs;
                $fout.='"'.$this->thepath.'ajaxreceiver.php?args='.urlencode(json_encode($aux_args)).'&dzsvg_settings_separation_paged='.($i + 1).'"';
            }
            $fout.=']';
        }
        if (isset($its['settings']['cueFirstVideo'])) {
            $fout.=',cueFirstVideo:"'.$its['settings']['cueFirstVideo'].'"';
        }
        if ((isset($its['settings']['disable_video_title']) && $its['settings']['disable_video_title'] == 'on')) {
            $fout.=',disable_videoTitle:"on"';
        }
        if (isset($its['settings']['displaymode']) && ($its['settings']['displaymode'] == 'wall' || $its['settings']['displaymode'] == 'normal') || $its['settings']['displaymode'] == 'rotator' || $its['settings']['displaymode'] == 'rotator3d') {
            $fout.=',settings_mode:"'.$its['settings']['displaymode'].'"';
        }

        if(isset($its['settings']['mode_wall_layout']) && $its['settings']['mode_wall_layout'] && $its['settings']['mode_wall_layout']!='none'){

            $fout.=',extra_class_slider_con:"'.$its['settings']['mode_wall_layout'].'"';
        }

        if (isset($its['settings']['logoLink']) && $its['settings']['logoLink'] != '') {
            $fout.=',logoLink:"'.$its['settings']['logoLink'].'"';
        }
        $fout.=',menu_position:"'.$html5mp.'"
,transition_type:"'.$its['settings']['html5transition'].'"
,design_skin: "'.$skin_html5vg.'"';

        if (isset($its['settings']['logo']) && $its['settings']['logo'] != '') {
            $fout.=',logo : "'.$its['settings']['logo'].'" ';
        }


        if (isset($its['settings']['playorder'])) {
            $fout.=',playorder :"'.$its['settings']['playorder'].'"';
        }
        if (isset($its['settings']['design_navigationuseeasing'])) {
            $fout.=',design_navigationUseEasing :"'.$its['settings']['design_navigationuseeasing'].'"';
        }
        if (isset($its['settings']['enable_search_field']) && $its['settings']['enable_search_field']=='on') {
            $fout.=',search_field :"on"';
        }
//        print_r($its['settings']);
        if (isset($its['settings']['settings_enable_linking']) && $its['settings']['settings_enable_linking']=='on' ) {
            $fout.=',settings_enable_linking :"'.$its['settings']['settings_enable_linking'].'"';
        }
        if (isset($its['settings']['autoplay_ad']) ) {
            $fout.=',autoplay_ad :"'.$its['settings']['autoplay_ad'].'"';
        }



        if (isset($its['settings']['enable_search_field']) && $its['settings']['enable_search_field']=='on' ) {
            if( !(  $its['settings']['displaymode'] == 'normal' && $its['settings']['nav_type']=='thumbs' && ( $html5mp=='left' || $html5mp=='right' ) )){

                $fout.=',search_field_con: $(".vg'.$this->sliders_index.'-search-field > input")';

            }
        }

        if($its['settings']['enableunderneathdescription']=='on'){
            $its['settings']['enable_secondcon']='off';
            $fout.=',settings_secondCon: "#as'.$this->sliders_index.'-secondcon"';
        }


        if ($its['settings']['sharebutton'] == 'on') {
            $auxout = '';
            if ($its['settings']['facebooklink']) {

                if($its['settings']['facebooklink']=='{{share}}'){

                    $auxout .= '<a class="dzsvg-social-icon"  href="#"  onclick=\'window.dzsvg_open_social_link("http://www.facebook.com/sharer.php?u={{replacewithcurrurl}}"); return false;\'>';
                }else{
                    $auxout .= '<a class="dzsvg-social-icon" target="_blank" href="'.stripslashes($its['settings']['facebooklink']).'">';
                }
                $auxout.='<i class="fa fa-facebook"></i></a>';
            }
            if ($its['settings']['twitterlink']) {
                $auxout .= '<a class="dzsvg-social-icon" target="_blank"  href="'.stripslashes($its['settings']['twitterlink']).'"><i class="fa fa-twitter"></i></a>';
            }
            if ($its['settings']['googlepluslink']) {
                $auxout .= '<a class="dzsvg-social-icon" target="_blank"  href="'.stripslashes($its['settings']['googlepluslink']).'"><i class="fa fa-google-plus-official" aria-hidden="true"></i></a>';
            }
            if (isset($its['settings']['social_extracode']) && $its['settings']['social_extracode'] != '') {
                $auxout.=$its['settings']['social_extracode'];
            }

            $auxout = str_replace("'", "\\'", $auxout);
            $auxout = str_replace("\\\'", "\\'", $auxout);
            $fout.=',shareCode : '."'".$auxout."'".' ';
        }

        if($its['settings']['enable_secondcon']=='on'){
            $fout.=',settings_secondCon:".dzsas-second-con-for-'.$css_classid.'"';
        }
        if($its['settings']['enable_outernav']=='on'){
            $fout.=',settings_outerNav:$(".videogallery--navigation-outer-for-'.$css_classid.'")';
        }

        if ($its['settings']['embedbutton'] == 'on') {
            $auxout = '<iframe src="'.$this->thepath.'bridge.php?action=view&id='.$its['settings']['id'].'&db='.$this->currDb.'" width="'.$its['settings']['width'].'" height="'.$its['settings']['height'].'" style="overflow:hidden;" scrolling="no" frameborder="0"></iframe>';
            $fout.=',embedCode : \''.$auxout.'\' ';
        }
        if (isset($its['settings']['rtl']) && $its['settings']['rtl'] == 'on') {
            $fout.=',masonry_options : {isRTL: true} ';
        }

        $fout.=',videoplayersettings : videoplayersettings
})
})
</script>';
        if ($its['settings']['shadow'] == 'on') {
            $fout.='<div class="all-shadow" style="width:'.$tw.';"></div>';
        }

        $fout.='<div class="clear"></div>';

        if ($margs['settings_separation_mode'] == 'pages') {
            $fout.='<div class="con-dzsvg-pagination">';
            //echo ceil((count($its) - 1) / intval($margs['settings_separation_pages_number']));
            for ($i = 0; $i < (ceil(count($its) - 1) / intval($margs['settings_separation_pages_number']) ); $i++){
                $str_active = '';
                if (($i + 1) == $margs['settings_separation_paged']) {
                    $str_active = ' active';
                };

                $auxurl = add_query_arg(array('dzsvg_settings_separation_paged' => ($i + 1)),dzs_curr_url());

                $fout.='<a class="pagination-number '.$str_active.'" href="'.esc_url($auxurl).'">'.($i + 1).'</a>';
            }
            $fout.='</div>';
        }

        $fout.='</div>'; //END gallery-precon

        if($its['settings']['enableunderneathdescription']=='on'){

            $fout.='<div id="as'.$this->sliders_index.'-secondcon" class="dzsas-second-con"><div class="dzsas-second-con--clip">';
            foreach ($its as $lab => $val){
                if ($lab==='settings') {
                    continue;
                }

//                print_r($val);

                $fout.='<div class="item">';
                if(isset($val['title'])){
                    $fout.='<h4>'.$val['title'].'</h4>';
                }



                if(isset($val['menuDescription'])) {


                    $maxlen = 100;
                    if (isset($its['settings']['maxlen_desc']) && $its['settings']['maxlen_desc']) {
                        $maxlen = $its['settings']['maxlen_desc'];
                    }


//            echo 'maxlen - '.$maxlen;

                    $striptags = false;

                    $try_to_close_unclosed_tags = true;


                    if (isset($its['settings']['striptags']) && $its['settings']['striptags'] === 'on') {
//                $striptags=true;
                        $try_to_close_unclosed_tags = false;
                    }
                    if (isset($its['settings']['try_to_close_unclosed_tags']) && $its['settings']['try_to_close_unclosed_tags'] === 'off') {
                        $try_to_close_unclosed_tags = false;
                    }


//            echo 'description - '.$val['menuDescription'];

                    if (isset($val['menuDescription']) && $val['menuDescription']) {
                        $fout.= '<p>' . dzs_get_excerpt(0,
                                array(
                                    'content' => $val['menuDescription'],
                                    'maxlen' => $maxlen,
                                    'try_to_close_unclosed_tags' => $try_to_close_unclosed_tags,
                                    'striptags' => $striptags,
                                )
                            ) . '</p>';
                    }
                }

                $fout.='</div>';

//                print_r($val);

            }
            $fout.='</div></div>';
        }


        if ($its['settings']['displaymode'] == 'wall') {
            wp_enqueue_script('jquery.masonry',$this->thepath."assets/masonry/jquery.masonry.min.js");

            wp_enqueue_style('zoombox',$this->thepath.'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox',$this->thepath.'assets/zoombox/zoombox.js');
        }






        //=======alternatewall
        //----mode alternatewall
        if ($its['settings']['displaymode'] == 'alternatewall') {
            $fout = '';
            $iout = '';
            $fout.='<style>
            .dzs-gallery-container .item{ width:23%; margin-right:1%; float:left; position:relative; display:block; margin-bottom:10px; }
            .dzs-gallery-container .item-image{ width:100%; }
            .dzs-gallery-container h4{  color:#D26; }
            .dzs-gallery-container h4:hover{ background: #D26; color:#fff; }
            .last { margin-right:0!important; }
            .clear { clear:both; }
            </style>';
            $fout.='<div class="dzs-gallery-container">';


            $fout.=$this->parse_items($its,$margs);
            $iout.=$this->parse_items($its,$margs);



            $fout.='<div class="clear"></div>';
            $fout.='</div>';


            if ($margs['settings_separation_mode'] == 'pages') {
                $fout.='<div class="con-dzsvg-pagination">';
                //echo ceil((count($its) - 1) / intval($margs['settings_separation_pages_number']));
                for ($i = 0; $i < (ceil(count($its) - 1) / intval($margs['settings_separation_pages_number']) ); $i++) {
                    $str_active = '';
                    if (($i + 1) == $margs['settings_separation_paged']) {
                        $str_active = ' active';
                    }
                    $fout.='<a class="pagination-number '.$str_active.'" href="'.esc_url(add_query_arg(array('dzsvg_settings_separation_paged' => ($i + 1)),dzs_curr_url())).'">'.($i + 1).'</a>';
                }
                $fout.='</div>';
            }

            $fout.='<div class="clear"></div>';
            $fout.='<script>jQuery(document).ready(function($){ jQuery(".zoombox").zoomBox(); });</script>';

            wp_enqueue_style('zoombox',$this->thepath.'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox',$this->thepath.'assets/zoombox/zoombox.js');

            return $fout;
        }


        //=======alternate menu
        /////---mode alternatemenu
        if ($its['settings']['displaymode'] == 'alternatemenu') {
            $i = 0;
            $k = 0;


            $current_urla = explode("?",dzs_curr_url());
            $current_url = $current_urla[0];

            $fout = '';
            $fout .= '
<style type="text/css">
.submenu{
margin:0;
padding:0;
list-style-type:none;
list-style-position:outside;
position:relative;
z-index:32;
}

.submenu a{
display:block;
padding:5px 15px;
background-color: #28211b;
color:#fff;
text-decoration:none;
}

.submenu li ul a{
display:block;
width:200px;
height:auto;
}

.submenu li{
float:left;
position:static;
width: auto;
position:relative;
}

.submenu ul, .submenu ul ul{
position:absolute;
width:200px;
top:auto;
display:none;
list-style-type:none;
list-style-position:outside;
}
.submenu > li > ul{
position:absolute;
top:auto;
left:0;
margin:0;
}

.submenu a:hover{
background-color:#555;
color:#eee;
}

.submenu li:hover ul, .submenu li li:hover ul{
display:block;
}
</style>';

            $fout .= '<ul class="submenu">';
            if (isset($this->mainitems)) {
                for ($k = 0; $k < count($this->mainitems); $k++) {
                    if (count($this->mainitems[$k]) < 2) {
                        continue;
                    }
                    $fout.='<li><a href="#">'.$this->mainitems[$k]["settings"]["id"].'</a>';

                    if (isset($this->mainitems[$k]) && count($this->mainitems[$k]) > 1) {

                        $fout.='<ul>';
                        for ($i = 0; $i < count($this->mainitems[$k]); $i++) {
                            if (isset($this->mainitems[$k][$i]["thethumb"]))
                                $fout.='<li><a href="'.$current_url.'?the_source='.$this->mainitems[$k][$i]["source"].'&the_thumb='.$this->mainitems[$k][$i]["thethumb"].'&the_type='.$this->mainitems[$k][$i]["type"].'&the_title='.$this->mainitems[$k][$i]["title"].'">'.$this->mainitems[$k][$i]["title"].'</a>';
                        }
                        $fout.='</ul>';
                    }
                    $fout.='</li>';
                }
            }

            $k = 0;
            $i = 0;
            $fout .= '</ul>
<div class="clearfix"></div>
<br>';

            if (isset($_REQUEST['the_source'])) {
                $fout.='<a class="zoombox" data-type="video" data-videotype="'.$_REQUEST['the_type'].'" data-src="'.$_REQUEST['the_source'].'"><img class="item-image" src="';
                if ($its[$i]['thethumb'] != '')
                    $fout.=$_REQUEST['the_thumb'];
                else {
                    if ($its[$i]['type'] == "youtube") {
                        $fout.='https://img.youtube.com/vi/'.$_REQUEST['the_source'].'/0.jpg';
                        $its[$i]['thethumb'] = 'https://img.youtube.com/vi/'.$_REQUEST['the_source'].'/0.jpg';
                    }
                }
                $fout.='"/></a>';
            }


            $fout.='<script>jQuery(document).ready(function($){ jQuery(".zoombox").zoomBox(); });</script>';

            wp_enqueue_style('zoombox',$this->thepath.'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox',$this->thepath.'assets/zoombox/zoombox.js');

            return $fout;
        }

        if ($this->mainoptions['debug_mode'] == 'on') {
            echo 'memory usage - ' . memory_get_usage() . "\n <br>memory limit - " . ini_get('memory_limit');;

        }


        if ($margs['return_mode'] != 'parsed items') {
            return $fout;
        } else {
            return $iout;
        }




        //echo $k;
    }

    function parse_items($its,$pargs) {
        //====returns only the html5 gallery items



        $margs = array(
            'settings_separation_mode' => 'normal',
            'settings_separation_paged' => '0',
            'settings_separation_pages_number' => '5',
            'single' => 'off',
        );

        if (is_array($pargs) == false) {
            $pargs = $margs;
        }

        $margs = array_merge($margs,$pargs);

        $fout = '';
        $start_nr = 0; // === the i start nr
        $end_nr = count($its); // === the i start nr
        $nr_per_page = 5;
        $nr_items = count($its) - 1;
        $nr_page = intval($margs['settings_separation_paged']);



        if ($nr_page == 0) {
            $nr_page = 1;
        }
//        print_r($its); print_r($margs); echo $margs['settings_separation_mode']; echo $margs['settings_separation_mode']!='normal';
        if ($margs['settings_separation_mode'] != 'normal') {
            $nr_per_page = intval($margs['settings_separation_pages_number']);

            if ($nr_per_page * $nr_page <= $nr_items) {
                $start_nr = $nr_per_page * ($nr_page - 1);
                $end_nr = $start_nr + $nr_per_page;
            } else {
                $start_nr = $nr_items - $nr_per_page - 1;
                $end_nr = $nr_items;
            }
        }
//        echo 'ceva '.$nr_per_page . ' || ' . ($nr_per_page * $nr_page) . ' ||||| ' . $start_nr . ' ' . $end_nr;

        if (isset($its['settings']['displaymode']) && $its['settings']['displaymode'] == 'alternatewall') {
            for ($i = $start_nr; $i < $end_nr; $i++) {
                if (!isset($its[$i]['type'])) {
                    continue;
                }
                $islastonrow = false;
                if ($i % 4 == 3) {
                    $islastonrow = true;
                }
                $itemclass = 'item';
                if ($islastonrow == true) {
                    $itemclass.=' last';
                }
                $fout.='<div class="'.$itemclass.'">';
                //$fout.='<a href="' . $this->thepath . 'ajax.php?ajax=true&height=' . $its['settings']['height'] . '&width=' . $its['settings']['width'] . '&type=' . $its[$i]['type'] . '&source=' . $its[$i]['source'] . '" title="' . $its[$i]['type'] . '" rel=""><img class="item-image" src="';
                $fout.='<a class="zoombox" data-type="video" data-videotype="'.$its[$i]['type'].'" data-src="'.$its[$i]['source'].'"><img class="item-image" src="';
                if ($its[$i]['thethumb'] != '')
                    $fout.=$its[$i]['thethumb'];
                else {
                    if ($its[$i]['type'] == "youtube") {
                        $fout.='https://img.youtube.com/vi/'.$its[$i]['source'].'/0.jpg';
                        $its[$i]['thethumb'] = 'https://img.youtube.com/vi/'.$its[$i]['source'].'/0.jpg';
                    }
                }
                $fout.='"/></a>';
                $fout.='<h4>'.$its[$i]['title'].'</h4>';
                $fout.='</div>';
                if ($islastonrow) {
                    $fout.='<div class="clear"></div>';
                }
            }
            return $fout;
        }


        //print_r($its); print_r($margs); echo ' start nr : '.$start_nr; echo ' end nr : '. $end_nr;

        for ($i = $start_nr; $i < $end_nr; $i++) {
            if (isset($its[$i]) == false) {
                continue;
            }


            $che = $its[$i];
            $this->index_players++;

            if ($che['source'] == '' || $che['source'] == ' ') {
                continue;
            }


            $vp_id = 'vp'.$this->index_players;
            if (isset($che['cssid']) && $che['cssid'] != '') {
                $vp_id = $che['cssid'];
            }
            if (isset($its['settings']['ids_point_to_source']) && $its['settings']['ids_point_to_source']=='on') {
                $vp_id = 'vg'.$this->sliders_index.'_'.'vp'.$che['source'];
            }


            $fout.='<div id="'.$vp_id.'" class="'.$vp_id.' vplayer-tobe"';
            if (isset($its['settings']['coverImage']) && $its['settings']['coverImage']) {
                $fout.=' data-img="'.$its['settings']['coverImage'].'"';
            }



//            print_r($its['settings']);
            if ((isset($its['settings']['disable_video_title']) && $its['settings']['disable_video_title'] != 'on') && isset($che['title']) && $che['title']) {
                $che['title'] = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$che['title']);
                $che['title'] = str_replace(array('"'),"&#8221;",$che['title']);
                $fout.=' data-videoTitle="'.$che['title'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'video') {
                $fout.=' data-sourcemp4="'.$che['source'].'"';


                if (isset($che['html5sourceogg']) && $che['html5sourceogg'] != '') {

                    if (strpos($che['html5sourceogg'],'.webm') === false) {
                        $fout.=' data-sourceogg="'.$che['html5sourceogg'].'"';
                    } else {
                        $fout.=' data-sourcewebm="'.$che['html5sourceogg'].'"';
                    }
                }
            }
            if (isset($che['audioimage']) && $che['audioimage'] != '') {
                $fout.=' data-previewimg="'.$che['audioimage'].'"';
                $fout.=' data-img="'.$che['audioimage'].'"';
            } else {

                if (isset($its['settings']['displaymode']) && $its['settings']['displaymode'] == 'wall' && isset($che['thethumb']) && $che['thethumb'] != '') {
                    $fout.=' data-previewimg="'.$che['thethumb'].'"';
                }
            }
            if (isset($che['type']) && $che['type'] == 'audio') {
                $fout.=' data-source="'.$che['source'].'"';
                $fout.=' data-sourcemp3="'.$che['source'].'"';
                if (isset($che['html5sourceogg']) && $che['html5sourceogg'] != '') {
                    $fout.=' data-sourceogg="'.$che['html5sourceogg'].'"';
                }
                if (isset($che['audioimage']) && $che['audioimage'] != '') {
                    $fout.=' data-audioimg="'.$che['audioimage'].'"';
                }
                $fout.=' data-type="audio"';
            }
            if (isset($che['type']) && $che['type'] == 'youtube') {
                $fout.=' data-type="youtube"';
                $fout.=' data-src="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'vimeo') {
                $fout.=' data-type="vimeo"';
                $fout.=' data-src="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'image') {
                $fout.=' data-type="image"';
                $fout.=' data-source="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'dash') {
                $fout.=' data-type="dash"';
                $fout.=' data-source="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'link') {
                $fout.=' data-type="link"';
                $fout.=' data-source="'.$che['source'].'"';

                if (isset($che['type']) && $che['type'] == 'link') {

                    $fout.=' data-target="'.$che['link_target'].'"';
                }


            }
            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout.=' data-type="inline"';
            }
            if (isset($che['type']) && $che['type'] == 'rtmp') {
                $fout.=' data-type="rtmp"';
                $fout.=' data-source="'.$che['source'].'"';
            }
            $aux = 'adsource';
            if (isset($che[$aux]) && $che[$aux] != '') {
                if (isset($che['adtype']) && $che['adtype'] != 'inline') {
                    $fout.=' data-'.$aux.'="'.$che[$aux].'"';
                }
            }
            $aux = 'adtype';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-'.$aux.'="'.$che[$aux].'"';
            }
            $aux = 'adlink';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-'.$aux.'="'.$che[$aux].'"';
            }
            $aux = 'adskip_delay';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-'.$aux.'="'.$che[$aux].'"';
            }
            $aux = 'playfrom';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-'.$aux.'="'.$che[$aux].'"';
            }

            $aux='responsive_ratio';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-'.$aux.'="'.$che[$aux].'"';
            }

            // -- if the video player is single shortcode then we can alter width height
            if ($margs['single'] == 'on') {
//                print_r($margs);
                // ===== some sanitizing
                $tw = $margs['width'];
                $th = $margs['height'];
                $str_tw = '';
                $str_th = '';



                if ($tw != '') {
                    if (strpos($tw,"%") === false && $tw != 'auto') {
                        $str_tw = ' width: '.$tw.'px;';
                    } else {
                        $str_tw = ' width: '.$tw.';';
                    }
                }


                if ($th != '') {
                    if (strpos($th,"%") === false && $th != 'auto') {
                        $str_th = ' height: '.$th.'px;';
                    } else {
                        $str_th = ' height: '.$th.';';
                    }
                }


                $fout.=' style="'.$str_tw.$str_th.'"';
            }




            $fout.='>';



            $maxlen = 100;
            if (isset($its['settings']['maxlen_desc']) && $its['settings']['maxlen_desc']) {
                $maxlen = $its['settings']['maxlen_desc'];
            }


            $striptags = false;

            $try_to_close_unclosed_tags = true;


            if (isset($its['settings']['striptags']) && $its['settings']['striptags']==='on') {
//                $striptags=true;
                $try_to_close_unclosed_tags = false;
            }
            if (isset($its['settings']['try_to_close_unclosed_tags']) && $its['settings']['try_to_close_unclosed_tags']==='off') {
                $try_to_close_unclosed_tags=false;
            }

//            echo 'description - '.$che['description'];

            $aux24 = '';
            if (isset($che['description']) && $che['description']) {
                $aux24 = '<div class="videoDescription">'.dzs_get_excerpt(0,
                        array(
                            'content' => $che['description'],
                            'maxlen' => $maxlen,
                            'try_to_close_unclosed_tags'=>$try_to_close_unclosed_tags,
                            'striptags'=>$striptags,
                        )
                    ).'</div>';
            }

//            echo $aux24.'-'.strrpos($aux24, '</').'-'.strlen($aux24).'- ';
            $aux24 = str_replace('</</div>', '</div>', $aux24);

            $fout.=$aux24;

            $aux = 'subtitle_file';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fil = DZSHelpers::get_contents($che[$aux]);
                $fout.='<div class="subtitles-con-input">'.$fil.'</div>';
            }

            $fout.='<div class="menuDescription">';
//            if($i==1){
//                print_r($che);
//            }
            //==== imgblock or imgfull
            $thumbclass = 'imgblock';


            if (isset($its['settings']['thumb_extraclass']) && $its['settings']['thumb_extraclass'] != '') {
                $thumbclass .= ' '.$its['settings']['thumb_extraclass'];
            }

            if (isset($its['settings']['nav_type']) && $its['settings']['nav_type'] == 'outer') {
                $thumbclass = 'imgfull';
            }

            if (isset($che['thethumb']) && $che['thethumb'] != '') {
//                echo 'hmmdada'; print_r($che['thethumb']);
                $fout.='<img src="'.$che['thethumb'].'" class="'.$thumbclass.'"/>';
            } else {
                if ($che['type'] == 'youtube') {
                    $fout.='{ytthumb}';
                }
            }
            if ((isset($its['settings']['disable_title']) && $its['settings']['disable_title'] != 'on') && isset($che['title']) && $che['title']) {
                $fout.='<div class="the-title">'.stripslashes($che['title']).'</div>';
            }
//            echo 'hmmtest'.!isset($its['settings']['disable_menu_description']).' '.isset($its['settings']['disable_menu_description']).' '.$its['settings']['disable_menu_description'];


            $aux24 = '<div class="paragraph">';
            if (((isset($its['settings']['disable_menu_description'])) && $its['settings']['disable_menu_description'] != 'on') && isset($che['menuDescription']) && $che['menuDescription']) {

                $aux24.=''.dzs_get_excerpt(0,
                        array(
                            'content' => stripslashes($che['menuDescription']),
                            'maxlen' => $maxlen,
                            'try_to_close_unclosed_tags'=>$try_to_close_unclosed_tags,
                            'striptags'=>$striptags,
                        )
                    ).'';

            }



//            echo $aux24.'-'.strrpos($aux24, '</').'-'.strlen($aux24).'- ';

            if(strrpos($aux24, '</')===strlen($aux24)-2){
                $aux24 = substr($aux24,0,-2);
            }

            $aux24.='</div>';

            $fout.=$aux24;



            $fout.='</div>'; //---menuDescription END
            if (isset($che['tags']) && $che['tags']) {
                $arr_septag = explode('$$;',$che['tags']);
                foreach ($arr_septag as $septag) {
                    //print_r($septag);
                    if ($septag != '') {
                        $arr_septagprop = explode('$$',$septag);
                        //print_r($arr_septagprop);
                        $fout.='<div class="dzstag-tobe" data-starttime="'.$arr_septagprop[0].'" data-endtime="'.$arr_septagprop[1].'" data-left="'.$arr_septagprop[2].'" data-top="'.$arr_septagprop[3].'" data-width="'.$arr_septagprop[4].'" data-height="'.$arr_septagprop[5].'" data-link="'.$arr_septagprop[6].'">'.$arr_septagprop[7].'</div>';
                    }
                }
                //print_r($arr_septag);
            }

            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout.=stripslashes($che['source']);
            }


            if (isset($che['adtype']) && $che['adtype'] == 'inline') {
                $fout.='<div class="adSource">'.$che['adsource'].'</div>';
            }

            $fout.='</div>';
        }
        return $fout;
    }



    function show_shortcode_showcase($pargs = array()) {



        //[dzsvp_portal count="5" mode="ullist" type="latest"]
        $fout = '';

        $margs = array(
            'count' => '5',
            'type' => 'video_items',
            'mode' => 'scrollmenu',
            'style' => 'list',
            'desc_count' => 'default',
            'desc_readmore_markup' => 'default',
            'max_videos' => '',
            'cat' => '',
            'linking_type' => 'default',
            'return_only_items' => 'off',
            'mode_scrollmenu_height' => '160',
            'mode_zfolio_skin' => 'skin-forwall',
            'mode_zfolio_layout' => '3columns',
            'mode_zfolio_gap' => '30px',
            'mode_zfolio_enable_special_layout' => 'off',
        );

        if (!is_array($pargs)) {
            $pargs = array();
        }

        $margs = array_merge($margs, $pargs);



        if($margs['mode']=='zfolio'){
            if($margs['linking_type']=='default'){
                $margs['linking_type'] = 'zoombox';
            }
        }




        if($margs['linking_type']=='default'){
            $margs['linking_type'] = 'direct_link';
        }



        // -- latest


        $its = array();
        $cats = array();

        if($margs['type']=='video_items'){
            $args = array(
                'post_type' => 'dzsvideo',
                'posts_per_page' => $margs['count'],
                'orderby' => 'date',
                'order' => 'DESC',
            );

            if($margs['cat']){
//                $args['cat'] = $margs['cat'];

                $cats = explode(',',$margs['cat']);

//                foreach($cats_aux as $val){
//                    array_push($cats, $val);
//                }

                $cats = array_values($cats);

//                print_rr($cats);


                if($args['post_type']=='dzsvideo'){
                    $args['tax_query']= array(
                        array(
                            'taxonomy' => 'dzsvideo_category',
                            'field'    => 'id',
                            'terms'    => $cats,
                        ),
                    );
                }

            }


//            print_rr($args);




            $query = new WP_Query($args);
            $its = $this->transform_to_array_for_parse($query->posts, $margs);
        }


        if($margs['type']=='youtube'){
//            echo 'ceva';


            include_once "class_parts/parse_yt_vimeo.php";

            $its = dzsvg_parse_yt($margs['youtube_link'], $margs);

//            print_r($its);
        }


        if($margs['type']=='vimeo'){
//            echo 'ceva';


            include_once "class_parts/parse_yt_vimeo.php";

            $its = dzsvg_parse_vimeo($margs['vimeo_link'], $margs);

//            print_r($its);
        }


        if($margs['return_only_items']=='on'){
            return $its;
        }


//        print_r($query->posts);



        $fout.=$this->parse_items_showcase($its, $margs);


        if($margs['type']=='layouter'){

        }



//        print_r($its);

        wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
        wp_enqueue_style('dzstabsandaccordions',$this->thepath.'assets/dzstabsandaccordions/dzstabsandaccordions.css');
        wp_enqueue_script('dzstabsandaccordions',$this->thepath."assets/dzstabsandaccordions/dzstabsandaccordions.js");
        return $fout;



        //echo $k;
    }

    function transform_to_array_for_parse($argits, $pargs=array()){

        global $post;
        $margs = array(
            'type' => 'video_items',
            'mode' => 'posts',
        );

        if (!is_array($pargs)) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        $its = array();


//        print_r($argits);

        foreach($argits as $it){


//            print_r($it);


            $aux25=array();

            $aux25['extra_classes'] = '';



            if($margs['type']=='video_items'){
                $it_id = $it->ID;
                $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);


//            print_r($author_data);


                if($imgsrc){

                    if(is_array($imgsrc)){
                        $aux25['thumbnail'] = $imgsrc[0];
                    }else{
                        $aux25['thumbnail'] = $imgsrc;
                    }

                }else{
                    if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
                        $aux25['thumbnail'] =  get_post_meta($it_id, 'dzsvp_thumb', true);
                    }
                }



                $aux25['type'] = get_post_meta($it_id, 'dzsvp_item_type', true);



                $aux = get_post_meta($it_id, 'dzsvp_featured_media', true);
                $aux25['source'] = $aux;

                if($aux25['type']=='youtube'){
//                    echo ' aux - '.$aux;
//                    $ceva = DZSHelpers::get_query_arg("https://www.youtube.com/watch?dada=alceva&v=MozX3qFIkp", 'va');
                    if(strpos($aux, 'youtube.com')!==false){


                        $aux = DZSHelpers::get_query_arg($aux,'v');


//                        echo ' aux - '.$aux;
                        $aux25['source'] = $aux;

                    }
                }

                $aux25['title']=$it->post_title;


                $aux25['permalink']=get_permalink($it_id);
                $aux25['permalink_to_post']=get_permalink($it_id);

                if($margs['linking_type']=='zoombox'){
                    $aux25['permalink'] = $aux25['source'];
                }

//                print_r($it);


                $maxlen = $margs['desc_count'];

//            print_r($margs);

                if($maxlen=='default'){

                    if ($margs['mode'] == 'scrollmenu') {
                        $maxlen = 50;
                    }
                }
                if($maxlen=='default'){
                    $maxlen=100;
                }

                $aux25['description'] = $this->sanitize_description($it->post_content, array(
                    'desc_count'=>intval($maxlen),
                    'striptags'=>'on',
                    'try_to_close_unclosed_tags'=>'on',
                    'desc_readmore_markup'=>$margs['desc_readmore_markup'],
                ));





                if($post && $post->ID === $it_id){
                    $aux25['extra_classes'].= ' active';
                }

                array_push($its, $aux25);
            }





        }



        return $its;

    }



    function parse_items_showcase($its, $pargs) {
        global $post;
        $fout = '';

        $margs = $pargs;
        $this->sliders_index++;

//        print_r($its);

        $slider_index = $this->sliders_index;
//        print_r($margs);

        if ($margs['mode'] == 'ullist') {
            $fout.='<ul class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }

        if ($margs['mode'] == 'list') {
            $fout.='<div class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }
        if ($margs['mode'] == 'scroller') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');

            $fout.='<div id="dzsvpas' . $slider_index . '" class="advancedscroller auto-height item-padding-20 skin-black dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout.='<ul class="items">';
        }
        if ($margs['mode'] == 'scrollmenu') {

            wp_enqueue_style('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.js');

            $fout.='<div  class="dzs_slideshow_' . $slider_index . ' scroller-con skin_royale scrollbars-inset  dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '"  style="width: 100%;	height: '.$margs['mode_scrollmenu_height'].'px;" data-options="">';
            $fout.='<div class="inner" style=""><div class="gallery-items skin-viva">';
        }
        if ($margs['mode'] == 'featured') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');


            $fout.='<div class="dzspb_lay_con dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout.='<div class="dzspb_layb_two_third">';
            $fout.='<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-inset auto-height">';
            $fout.='<ul class="items">';
        }
        if ($margs['mode'] == 'layouter') {

            wp_enqueue_style('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.css');
            wp_enqueue_script('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.js');
            wp_enqueue_script('masonry', $this->thepath . 'assets/dzslayouter/masonry.pkgd.min.js');


            $fout.='<div class="dzslayouter auto-init skin-loading-grey transition-fade hover-arcana" style="" data-options="{prefferedclass: \'wides\', settings_overwrite_margin: \'0\', settings_lazyload: \'on\'}"><ul class="the-items-feed">';
        }
        if ($margs['mode'] == 'zfolio') {




            wp_enqueue_style('zfolio', $this->thepath . 'assets/zfolio/zfolio.css');
            wp_enqueue_script('zfolio', $this->thepath . 'assets/zfolio/zfolio.js');
            wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');
            wp_enqueue_script('zfolio.isotope', $this->thepath . 'assets/zfolio/jquery.isotope.min.js');


            $fout.='<div class="zfolio zfolio'.$slider_index.' '.$margs['mode_zfolio_skin'].'  delay-effects  ';

            if($margs['mode_zfolio_layout']=='5columns'){
                $fout.=' layout-5-cols-15-margin';
            }else{
                $fout.=' layout-3-cols-15-margin';
            }

            $fout.='"';

            if($margs['mode_zfolio_gap']=='1px'){
                $fout.=' data-margin="1"';
            }

            $fout.=' data-options=\'\'>
                        <div class="items ">

                            ';
        }

//        print_r($its);

        $ii = 0;

        foreach ($its as $it) {


            $it_default = array(

                'thumbnail'=>'',
                'author_display_name'=>'',
                'type'=>'video',
                'permalink'=>'',
                'permalink_to_post'=>'',
                'title'=>'',
                'description'=>'',
                'extra_classes'=>'',
                'source'=>'', // -- the mp4 link, image source, vimeo id or youtube id ( should already be parsed )
            );



            $it = array_merge($it_default, $it);




            $str_featuredimage = '';



            if ($it['thumbnail']) {
            } else {

                if ($it['type'] == 'youtube'){

                    $yt_id = $it['source'];;


                    if(strpos($yt_id, 'youtube.com/')!==false){
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }

                    $it['thumbnail'] = 'http://img.youtube.com/vi/' . $yt_id . '/0.jpg';
                }
                if ($it['type'] == 'vimeo') {

                    $yt_id = $it['source'];


                    if(strpos($yt_id, 'vimeo.com/')!==false){
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }



                    $hash = unserialize(DZSHelpers::get_contents("http://vimeo.com/api/v2/video/$yt_id.php"));

//                    print_r($hash);
                    $it['thumbnail'] = $hash[0]['thumbnail_medium'];
                }
            }




            if($margs['desc_readmore_markup']=='default'){
                if ($margs['mode'] == 'scrollmenu') {
                    $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                }
            }
            if($margs['desc_readmore_markup']=='default'){
                $margs['desc_readmore_markup'] = '';
            }


            $desc = $it['description'];
//            echo $str_featuredimage;

            $extra_attr = ''; // -- extra attr for the blank container elements
            $extra_attr_for_zoombox = ''; // -- extra attr for zoombox ( data-biggallery )
            $extra_classes_for_zoombox = ''; // -- apply zoombox class

            if($margs['linking_type']=='zoombox'){
                $extra_classes_for_zoombox .= ' zoombox';
                $extra_attr_for_zoombox.=' data-type="'.$it['type'].'"  data-biggallery="ullist'.$slider_index.'"  data-biggallerythumbnail="' . $it['thumbnail'] . '"';





                wp_enqueue_style('dzs.vplayer',$this->thepath.'videogallery/vplayer.css');
                wp_enqueue_script('dzs.vplayer',$this->thepath."videogallery/vplayer.js");


            }

            if ($margs['mode'] == 'ullist') {



                if($margs['linking_type']=='zoombox'){
                    $extra_classes_for_zoombox .= ' zoombox';
                    $extra_attr_for_zoombox.=' data-type="'.$it['type'].'"  data-biggallery="ullist'.$slider_index.'"  data-biggallerythumbnail="' . $it['thumbnail'] . '"';





                    wp_enqueue_style('dzs.vplayer',$this->thepath.'videogallery/vplayer.css');
                    wp_enqueue_script('dzs.vplayer',$this->thepath."videogallery/vplayer.js");


                }

                $fout.='<li><a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr_for_zoombox.'>' . $it['title'] . '</a></li>';
            }
            if ($margs['mode'] == 'list') {
                $fout.='<div class="dzsvp-item">';
                $fout.='<div class="dzspb_lay_con">';
                if ($it['thumbnail']) {

                    $fout.='<div class="dzspb_layb_one_fourth">';
                    $fout.='<a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr_for_zoombox.'>';
                    $fout.='<img src="' . $it['thumbnail'] . '" style="width:100%;"/>';
                    $fout.='</a>';
                    $fout.='</div>';
                    $fout.='<div class="dzspb_layb_three_fourth">';
                    $fout.='<h4 style="margin-top:2px; margin-bottom: 5px;"><a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr.'>' . $it['title'] . '</a></h4>';
                    if($it['author_display_name']){

                        $fout.='<p>by <em>' . $it['author_display_name'] . '</em></p>';
                    }
                    $fout.='<p>' . $it['description'] . '</p>';
                    $fout.='</div>';
                } else {

                    $fout.='<div class="dzspb_layb_one_full">';
                    $fout.='<h4 style="margin-top:2px; margin-bottom: 5px;"><a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr_for_zoombox.'>' . $it['title'] . '</a></h4>';
                    if($it['author_display_name']){

                        $fout.='<p>by <em>' . $it['author_display_name'] . '</em></p>';
                    }
                    $fout.='<p>' . $it['description'] . '</p>';
                    $fout.='</div>';
                }
                $fout.='</div>';
                $fout.='</div>';
            }
            if ($margs['mode'] == 'list-2') {
                $fout.='<div class="dzsvp-item">';
                $fout.='<div class="dzspb_lay_con">';

                $fout.='<div class="dzspb_layb_one_full">';
                $fout.='<p><a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr_for_zoombox.'>';
                $fout.='<img src="' . $it['thumbnail'] . '" style="width:100%;"/>';
                $fout.='</a></p>';
                $fout.='<h4 style="margin-top:2px; margin-bottom: 5px; text-align: center; "><a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr.'>' . $it['title'] . '</a></h4>';
                $fout.='</div>';

                $fout.='</div>';
                $fout.='</div>';
            }


            if ($margs['mode'] == 'zfolio') {

                $src = $it['source'];

                if($it['type']=='vimeo'){

                    $src = 'https://vimeo.com/'.$src;
                }



                $zoombox_cls = '';
//                print_r($margs);

                if($margs['linking_type']==='zoombox'){
                    $zoombox_cls = ' zoombox';
                }


                $fout.='<div class="zfolio-item';



                if($margs['mode_zfolio_enable_special_layout']=='on'){
                    echo $ii%5;


                    switch($ii%5){
                        case 0:
                            $fout.=' layout-tall';
                            break;
                        case 1:
                            $fout.=' layout-big';
                            break;
                        case 2:
                            $fout.=' layout-wide';
                            break;
                        default:
                            $fout.=' ';
                            break;
                    }
                }

                $fout.='" data-dzsvgindex="'.$ii.'"  data-category="" data-overlay_extra_class="" style="" >
                                <div class="zfolio-item--inner">
                                    <a href="'.$src.'" data-type="'.$it['type'].'" class="the-feature-con '.$zoombox_cls.'" style="height: 217.8px;" data-biggallery="zfolio'.$slider_index.'" data-biggallerythumbnail="' . $it['thumbnail'] . '"><div class="the-feature" style="background-image: url(' . $it['thumbnail'] . ');"></div><div class="the-overlay"></div></a>
                                    <div class="item-meta">
                                        <div class="the-title">'.$it['title'].'</div>
                                        <div class="the-desc">'.$it['description'].'</div>
                                    </div>
                                    <div class="item-meta-secondary">';
                if($it['author_display_name']){

                    $fout.='<div class="s-item-meta"><span class="strong">Uploader:</span> ' . $it['author_display_name'] . '</div>';
                }
                if(isset($it['upload_date']) && $it['upload_date']){



                    $d2 = new DateTime($it['upload_date'], new DateTimeZone('Europe/Rome'));
                    $t2 = $d2->getTimestamp();

//                    echo $t2 . ' --- '.current_time('timestamp').' ||| ';

                    $str_date = human_time_diff( $t2, current_time('timestamp') ) . ' ago';
                    $fout.='<div class="s-item-meta"><span class="strong">Published:</span> ' . $str_date . '</div>';
                }
                                    $fout.='</div>
                                </div>



                            </div>';
            }


            if ($margs['mode'] == 'scrollmenu') {

//                print_r($post);


                $fout.='<a href="' . $it['permalink'] . '" class="dzsscr-gallery-item';


                $fout.=' '.$it['extra_classes'];

                $fout.='">';


                if($it['thumbnail']){
                    $fout.='<div class="the-thumb" style="background-image:url('.$it['thumbnail'].'); "></div>';
                }



                $fout.='
                        <div class="the-meta">
                            <div class="the-title">'.$it['title'].'</div>
                            <div class="the-desc">'.$desc.'</div>
                        </div>
                    </a>';

            }
            if ($margs['mode'] == 'scroller') {
                $fout.='<li class="item-tobe">';
                if ($it['thumbnail']) {

                    $fout.='<a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"'.$extra_attr_for_zoombox.'><img class="fullwidth" src="' . $it['thumbnail'] . '"/></a>';
                    $fout.='<h5 class="name"><a href="' . $it['permalink'] . '">' . $it['title'] . '</a></h5>';
                }
                $fout.='</li>';
            }
            if ($margs['mode'] == 'layouter') {

                $fout.='<li data-link="'.$it['permalink'].'" data-src="'.$str_featuredimage.'" ><div class="feed-title">' . $it->post_title . '</div></li>';
            }


            if ($margs['mode'] == 'featured') {
                $fout.='<li class="item-tobe';
                if ($ii == 0) {
                    $fout.=' needs-loading';
                }
                $fout.='">';
                if ($it['thumbnail']) {

                    $fout.='<a class="'.$extra_classes_for_zoombox.'" href="' . $it['permalink'] . '"><img class="fullwidth" src="' . $it['thumbnail'] . '"'.$extra_attr_for_zoombox.'/></a>';
                }
                $fout.='</li>';
            }

            $ii++;
        }


        if ($margs['mode'] == 'layouter') {
            $fout.='</ul></div>';
        }

        if ($margs['mode'] == 'ullist') {
            $fout.='</ul>';
        }
        if ($margs['mode'] == 'list') {
            $fout.='</div>';
        }
        if ($margs['mode'] == 'scrollmenu') {
            $fout.='</div>';
            $fout.='</div>';
            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsscr_init(".dzs_slideshow_' . $slider_index . '",{
    settings_skin:\'skin_slider\'
    ,enable_easing:\'on\'
});
});</script>';
        }
        if ($margs['mode'] == 'scroller') {
            $fout.='</ul>';
            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
    settings_swipe: "on"
    ,design_arrowsize: "0"
    ,design_itemwidth: "25%"
});
});</script>';
        }
        if ($margs['mode'] == 'zfolio') {
            $fout.='</div><div class="zfolio-preloader-circle-con zfolio-preloader-con">
                            <div class="zfolio-preloader-circle"></div>
                        </div>
                    </div>';



            $item_thumb_height = '0.6';
            if($margs['mode_zfolio_enable_special_layout']=='on'){
                $item_thumb_height = '1';
            }

            $fout.='<script>
jQuery(document).ready(function($){
dzszfl_init(".zfolio' . $slider_index . '",{ design_item_thumb_height:"'.$item_thumb_height.'"
,item_extra_class:""
,excerpt_con_transition: "wipe"
});
});</script>';
        }
        if ($margs['mode'] == 'featured') {
            $fout.='</ul>';
            $fout.='</div>';
            $fout.='</div>';


            $fout.='<div id="dzsvpas' . $slider_index . '-secondcon" class="dzspb_layb_one_third dzsas-second-con">';
            $fout.='<div class="dzsas-second-con--clip">';


            foreach ($its as $it) {
                $fout.='<div class="item">';
                $fout.='<h4><a href="' . $it['permalink_to_post'] . '">' . $it['title'] . '</a></h4>';
                $fout.='<p>' . $it['description'] . '</p>';
                $fout.='</div>';
            }

            $fout.='</div>';
            $fout.='</div>';



            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
            settings_mode: "onlyoneitem"
            ,design_arrowsize: "0"
            ,settings_swipe: "on"
            ,settings_swipeOnDesktopsToo: "on"
            ,settings_slideshow: "on"
            ,settings_slideshowTime: "300"
            ,settings_autoHeight:"on"
            ,settings_transition:"fade"
            ,settings_secondCon: "#dzsvpas' . $slider_index . '-secondcon"
            ,design_bulletspos:"none"
});
});</script>';
        }


        return $fout;
    }



    function parse_items_view($its, $pargs) {
        global $post;
        $fout = '';

        $margs = $pargs;
        $this->sliders_index++;


        $slider_index = $this->sliders_index;
//        print_r($margs);

        if ($margs['mode'] == 'ullist') {
            $fout.='<ul class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }

        if ($margs['mode'] == 'list') {
            $fout.='<div class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }
        if ($margs['mode'] == 'scroller') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');

            $fout.='<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-black dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout.='<ul class="items">';
        }
        if ($margs['mode'] == 'scrollmenu') {

            wp_enqueue_style('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.js');

            $fout.='<div  class="dzs_slideshow_' . $slider_index . ' scroller-con skin_royale scrollbars-inset  dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '"  style="width: 100%;	height: '.$margs['mode'].'px;" data-options="">';
            $fout.='<div class="inner" style=""><div class="gallery-items skin-viva">';
        }
        if ($margs['mode'] == 'featured') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');


            $fout.='<div class="dzspb_lay_con dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout.='<div class="dzspb_layb_two_third">';
            $fout.='<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-inset">';
            $fout.='<ul class="items">';
        }
        if ($margs['mode'] == 'layouter') {

            wp_enqueue_style('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.css');
            wp_enqueue_script('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.js');
            wp_enqueue_script('masonry', $this->thepath . 'assets/dzslayouter/masonry.pkgd.min.js');


            $fout.='<div class="dzslayouter auto-init skin-loading-grey transition-fade hover-arcana" style="" data-options="{prefferedclass: \'wides\', settings_overwrite_margin: \'0\', settings_lazyload: \'on\'}"><ul class="the-items-feed">';
        }

//        print_r($its);

        $ii = 0;

        foreach ($its as $it) {


            $it_id = $it->ID;
            $str_featuredimage = '';

            $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);

            $author_id = $it->post_author;
            $author_data = get_userdata($author_id);

//            print_r($author_data);


            if($imgsrc){

            }else{
                if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
                    $imgsrc =  get_post_meta($it_id, 'dzsvp_thumb', true);
                }
            }


            if ($imgsrc) {
                if(is_array($imgsrc)){
                    $str_featuredimage = $imgsrc[0];
                }else{
                    $str_featuredimage = $imgsrc;
                }

            } else {

                if (get_post_meta($it_id, 'dzsvp_item_type', true) == 'youtube') {

                    $yt_id = get_post_meta($it_id, 'dzsvp_featured_media', true);


                    if(strpos($yt_id, 'youtube.com/')!==false){
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }

                    $str_featuredimage = 'http://img.youtube.com/vi/' . $yt_id . '/0.jpg';
                }
                if (get_post_meta($it_id, 'dzsvp_item_type', true) == 'vimeo') {

                    $yt_id = get_post_meta($it_id, 'dzsvp_featured_media', true);


                    if(strpos($yt_id, 'vimeo.com/')!==false){
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }



                    $hash = unserialize(DZSHelpers::get_contents("http://vimeo.com/api/v2/video/$yt_id.php"));

//                    print_r($hash);
                    $str_featuredimage = $hash[0]['thumbnail_medium'];
                }
            }

            $maxlen = $margs['desc_count'];

//            print_r($margs);

            if($maxlen=='default'){

                if ($margs['mode'] == 'scrollmenu') {
                    $maxlen = 50;
                }
            }
            if($maxlen=='default'){
                $maxlen=100;
            }

            if($margs['desc_readmore_markup']=='default'){
                if ($margs['mode'] == 'scrollmenu') {
                    $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                }
            }
            if($margs['desc_readmore_markup']=='default'){
                $margs['desc_readmore_markup'] = '';
            }


            $desc = $this->sanitize_description($it->post_content, array(
                'desc_count'=>intval($maxlen),
                'striptags'=>'on',
                'try_to_close_unclosed_tags'=>'on',
                'desc_readmore_markup'=>$margs['desc_readmore_markup'],
            ));
//            echo $str_featuredimage;

            if ($margs['mode'] == 'ullist') {
                $fout.='<li><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></li>';
            }
            if ($margs['mode'] == 'list') {
                $fout.='<div class="dzsvp-item">';
                $fout.='<div class="dzspb_lay_con">';
                if ($str_featuredimage) {

                    $fout.='<div class="dzspb_layb_one_fourth">';
                    $fout.='<a href="' . get_permalink($it_id) . '">';
                    $fout.='<img src="' . $str_featuredimage . '" style="width:100%;"/>';
                    $fout.='</a>';
                    $fout.='</div>';
                    $fout.='<div class="dzspb_layb_three_fourth">';
                    $fout.='<h4 style="margin-top:2px; margin-bottom: 5px;"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                    $fout.='<p>by <em>' . $author_data->display_name . '</em></p>';
                    $fout.='<p>' . $it->post_content . '</p>';
                    $fout.='</div>';
                } else {

                    $fout.='<div class="dzspb_layb_one_full">';
                    $fout.='<h4 style="margin-top:2px; margin-bottom: 5px;"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                    $fout.='<p>by <em>' . $author_data->display_name . '</em></p>';
                    $fout.='<p>' . $it->post_content . '</p>';
                    $fout.='</div>';
                }
                $fout.='</div>';
                $fout.='</div>';
            }
            if ($margs['mode'] == 'list-2') {
                $fout.='<div class="dzsvp-item">';
                $fout.='<div class="dzspb_lay_con">';

                $fout.='<div class="dzspb_layb_one_full">';
                $fout.='<p><a href="' . get_permalink($it_id) . '">';
                $fout.='<img src="' . $str_featuredimage . '" style="width:100%;"/>';
                $fout.='</a></p>';
                $fout.='<h4 style="margin-top:2px; margin-bottom: 5px; text-align: center; "><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                $fout.='</div>';

                $fout.='</div>';
                $fout.='</div>';
            }


            if ($margs['mode'] == 'scrollmenu') {

//                print_r($post);


                $fout.='<a href="' . get_permalink($it_id) . '" class="dzsscr-gallery-item';


                if($post && $post->ID === $it_id){
                    $fout.= ' active';
                }

                $fout.='">';


                if($str_featuredimage){
                    $fout.='<div class="the-thumb" style="background-image:url('.$str_featuredimage.'); "></div>';
                }



                        $fout.='
                        <div class="the-meta">
                            <div class="the-title">'.$it->post_title.'</div>
                            <div class="the-desc">'.$desc.'</div>
                        </div>
                    </a>';

            }
            if ($margs['mode'] == 'scroller') {
                $fout.='<li class="item-tobe">';
                if ($str_featuredimage) {

                    $fout.='<a href="' . get_permalink($it_id) . '"><img class="fullwidth" src="' . $str_featuredimage . '"/></a>';
                    $fout.='<h5 class="name"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h5>';
                }
                $fout.='</li>';
            }
            if ($margs['mode'] == 'layouter') {

                $fout.='<li data-link="'.get_permalink($it_id).'" data-src="'.$str_featuredimage.'" ><div class="feed-title">' . $it->post_title . '</div></li>';
            }


            if ($margs['mode'] == 'featured') {
                $fout.='<li class="item-tobe';
                if ($ii == 0) {
                    $fout.=' needs-loading';
                }
                $fout.='">';
                if ($str_featuredimage) {

                    $fout.='<a href="' . get_permalink($it_id) . '"><img class="fullwidth" src="' . $str_featuredimage . '"/></a>';
                }
                $fout.='</li>';
            }

            $ii++;
        }


        if ($margs['mode'] == 'layouter') {
            $fout.='</ul></div>';
        }

        if ($margs['mode'] == 'ullist') {
            $fout.='</ul>';
        }
        if ($margs['mode'] == 'list') {
            $fout.='</div>';
        }
        if ($margs['mode'] == 'scrollmenu') {
            $fout.='</div>';
            $fout.='</div>';
            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsscr_init(".dzs_slideshow_' . $slider_index . '",{
    settings_skin:\'skin_slider\',
    settings_replacewheelxwithy:\'on\'
    ,enable_easing:\'on\'
});
});</script>';
        }
        if ($margs['mode'] == 'scroller') {
            $fout.='</ul>';
            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
    settings_swipe: "on"
    ,design_arrowsize: "0"
    ,design_itemwidth: "200"
});
});</script>';
        }
        if ($margs['mode'] == 'featured') {
            $fout.='</ul>';
            $fout.='</div>';
            $fout.='</div>';


            $fout.='<div id="dzsvpas' . $slider_index . '-secondcon" class="dzspb_layb_one_third dzsas-second-con">';
            $fout.='<div class="dzsas-second-con--clip">';


            foreach ($its as $it) {
                $fout.='<div class="item">';
                $fout.='<h4><a href="' . get_permalink($it->ID) . '">' . $it->post_title . '</a></h4>';
                $fout.='<p>' . $it->post_content . '</p>';
                $fout.='</div>';
            }

            $fout.='</div>';
            $fout.='</div>';



            $fout.='</div>';
            $fout.='<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
            settings_mode: "onlyoneitem"
            ,design_arrowsize: "0"
            ,settings_swipe: "on"
            ,settings_swipeOnDesktopsToo: "on"
            ,settings_slideshow: "on"
            ,settings_slideshowTime: "300"
            ,settings_autoHeight:"on"
            ,settings_transition:"fade"
            ,settings_secondCon: "#dzsvpas' . $slider_index . '-secondcon"
            ,design_bulletspos:"none"
});
});</script>';
        }


        return $fout;
    }



    function filter_the_content($content) {
        global $post, $dzsvg, $current_user;
        $po_id = $post->ID;

        $this->sw_content_added = false;

        $fout = '';

        $nr_views = 0;

        if (isset($_POST['dzsvp-upload-video-confirmer']) && $_POST['dzsvp-upload-video-confirmer'] == 'Submit') {
            echo ('<script>window.location.href="' . admin_url('edit.php?post_type=dzsvideo') . '";</script>');
        }



        if ($post->post_type == 'dzsvideo' && get_post_meta($po_id, 'dzsvp_featured_media', true) != '') {

            $fout.=$this->parse_videoitem($post);


                wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');

            
        }

        if (!$this->sw_content_added) {

            $fout.=$content;
        }

//            echo 'ceva '.$po_id.' '.$dzsvg->mainoptions['dzsvp_page_upload'];
//            print_r($post);
//            print_r($dzsvg);



        // -- page upload
        if ($post->post_type == 'page' && $dzsvg->mainoptions['dzsvp_page_upload'] != '') {
            if ($po_id == $dzsvg->mainoptions['dzsvp_page_upload']) {
                if ($current_user && isset($current_user->data->ID) && $current_user->data->ID != false) {

                    $fout.='<form method="POST" class="dzsvp-page-upload--mainuploader phase-one">';

                    $fout.='<div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
                <select class="textinput item-type" data-label="type" name="dzsvp_item_type">
            <option>video</option>
            <option>youtube</option>
            <option>vimeo</option>
                </select>
                <div class="option-con clearfix">
                
                    <div class="an-option">
                    <div class="an-option-inner">
                    <div class="an-title">
                    ' . __('Self-hosted Video', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Stream videos your own hosted videos. You just have to include two formats of the video you are streaming. In the <strong>Featured Media</strong>
                    field you need to include the path to your mp4 formatted video. ', 'dzsvg') . ' .
                    </div>
                    </div>
                    </div>
                    

                    <div class="an-option">
                    <div class="an-option-inner">
                    <div class="an-title">
                    ' . __('YouTube', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Input in the <strong>Featured Media</strong> field below the youtube video ID. You can find the id contained in the link to 
                    the video - http://www.youtube.com/watch?v=<strong>ZdETx2j6bdQ</strong> ( for example )', 'dzsvg') . '
                    </div>
                    </div>
                    </div>
                    
                    
                    <div class="an-option">
                    <div class="an-option-inner">
                    <div class="an-title">
                    ' . __('Vimeo Video', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Insert in the <strong>Featured Media</strong> field the ID of the Vimeo video you want to stream. You can identify the ID easy from the link of the video,
                     for example, here see the bolded part', 'dzsvg') . ' - http://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>
                    </div>
                    
                </div>
            </div>';

                    $fout.='
            <div class="dzs-setting">
                <h5>' . __("Featured Media", 'dzsvp') . '</h5>
                    <div class="dzs-upload-con">
                ' . $this->misc_input_text('dzsvp_featured_media', array('class' => 'upload-type-video main-source not-in-phase-one', 'def_value' => '', 'seekval' => '')) . '
                <span class="dzs-single-upload type-video not-in-phase-one">
        <input class="" name="file_field" type="file" accept="video/*">
    </span>
    <div class="dzs-single-upload drag-drop type-video">
        <div class="dzs-single-upload--areadrop">
            <input class="" name="file_field" type="file" accept="video/*">
            <div class="instructions">drag &amp; drop the file</div>
        </div>
    </div>
    <div class="feedback"></div>
                    </div>
            <div class="dzs-setting not-in-phase-one">
                <h5>' . __("Title", 'dzsvp') . '</h5>
                    <input type="text" class="" name="video_title"/>
            </div>
            <div class="dzs-setting not-in-phase-one">
                <h5>' . __("Description", 'dzsvp') . '</h5>
                    <input type="text" class="" name="video_description"/>
            </div>';

                    $categories = get_terms( 'dzsvideo_category', 'orderby=count&hide_empty=0' );

                    if(count($categories)>0){
                        $fout.='<div class="dzs-setting not-in-phase-one">
                <h5>' . __("Category", 'dzsvp') . '</h5>
                    <select name="video_category">
                    <option value="uncategorized">'.__("Uncategorized", 'dzsvp').'</option>';

                        foreach($categories as $cat){
                            $fout.='<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
                        }
                        $fout.='</select>
            </div>';
                    }

                    $fout.='<br/>
            <div class="dzs-setting not-in-phase-one">
                <input type="submit" value="Submit" name="dzsvp-upload-video-confirmer"/>
            </div>
            </div>';

                    $categories = get_terms( 'dzsvideo_category', 'orderby=count&hide_empty=0' );
//                    print_r($categories);



                    $fout.='
<script>';

                    $this->sw_footer_add_progress = true;

                    if (isset($dzsvg->mainoptions['use_external_uploaddir']) && $dzsvg->mainoptions['use_external_uploaddir'] == 'on') {
                        $fout.= "window.dzs_upload_path = '" . site_url('wp-content') . "/upload/';
";
                        $fout.= "window.dzs_phpfile_path = '" . site_url('wp-content') . "/upload.php';
";
                    } else {
                        $fout.= "window.dzs_upload_path = '" . $dzsvg->thepath . "admin/upload/';
";
                        $fout.= "window.dzs_phpfile_path = '" . $dzsvg->thepath . "admin/upload.php';
";
                    }
                    $fout.='jQuery(document).ready(function($){
    window.dzsuploader_single_init(".dzs-single-upload", {});
})
</script>';

                    $fout.='</form>'; // close dzsvp-page-upload--mainuploader


                    wp_enqueue_style('dzsvg_dzsuploader', $dzsvg->thepath . 'admin/dzsuploader/upload.css');
                    wp_enqueue_script('dzsvg_dzsuploader', $dzsvg->thepath . 'admin/dzsuploader/upload.js');
                } else {

                    $fout.=__('You need an account to post on this site.');
                }
            }
        }

        return $fout;
    }




    function register_links(){

        global $dzsvg;


        register_taxonomy(
            'dzsvideo_category', 'dzsvideo', array(
                'label' => __('Video Categories', 'dzsvp'),
                'query_var' => true,
                'show_ui' => true,
                'hierarchical' => true,
                'rewrite'           => array( 'slug' => $dzsvg->mainoptions['dzsvp_categories_rewrite'] ),
            )
        );
        register_taxonomy(
            'dzsvideo_tags', 'dzsvideo', array(
                'label' => __('Video Tags', 'dzsvp'),
                'query_var' => true,
                'show_ui' => true,
                'hierarchical' => false,
                'rewrite'           => array( 'slug' => $dzsvg->mainoptions['dzsvp_tags_rewrite'] ),
            )
        );


        $labels = array(
            'name' => 'Video Items',
            'singular_name' => 'Video Item',
        );

        $permalinks = get_option('dzsvp_permalinks');
        //print_r($permalinks);

        $item_slug_permalink = empty($permalinks['item_base']) ? _x('video', 'slug', 'dzsvp') : $permalinks['item_base'];


        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'post-thumbnail', 'comments', 'excerpt'),
            'rewrite' => array('slug' => $item_slug_permalink),
            'yarpp_support' => true,
            'capabilities' => array(
                'edit_post' => 'dzsvp_edit_post',
                'edit_posts' => 'dzsvp_edit_posts',
                'edit_others_posts' => 'dzsvp_edit_others_posts',
                'publish_posts' => 'dzsvp_publish_posts',
                'edit_published_posts' => 'dzsvp_edit_published_posts',
                'read_post' => 'dzsvp_read_post',
                'read_private_posts' => 'dzsvp_read_private_posts',
                'delete_post' => 'dzsvp_delete_post',
                'delete_others_posts' => 'dzsvp_delete_others_posts'
            ),
            //'taxonomies' => array('categoryportfolio'),
        );
        register_post_type('dzsvideo', $args);
    }



    function permalink_settings() {

        echo wpautop(__('These settings control the permalinks used for products. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'dzsvp'));

        $permalinks = get_option('dzsvp_permalinks');
        $dzsvp_permalink = $permalinks['item_base'];
        //echo 'ceva';

        $item_base = _x('video', 'default-slug', 'dzsvp');

        $structures = array(
            0 => '',
            1 => '/' . trailingslashit($item_base)
        );
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><label><input name="dzsvp_permalink" type="radio" value="<?php echo $structures[0]; ?>" class="dzsvptog" <?php checked($structures[0], $dzsvp_permalink); ?> /> <?php _e('Default'); ?></label></th>
                <td><code><?php echo home_url(); ?>/?video=sample-item</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsvp_permalink" type="radio" value="<?php echo $structures[1]; ?>" class="dzsvptog" <?php checked($structures[1], $dzsvp_permalink); ?> /> <?php _e('Product', 'dzsvp'); ?></label></th>
                <td><code><?php echo home_url(); ?>/<?php echo $item_base; ?>/sample-item/</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsvp_permalink" id="dzsvp_custom_selection" type="radio" value="custom" class="tog" <?php checked(in_array($dzsvp_permalink, $structures), false); ?> />
                        <?php _e('Custom Base', 'dzsvp'); ?></label></th>
                <td>
                    <input name="dzsvp_permalink_structure" id="dzsvp_permalink_structure" type="text" value="<?php echo esc_attr($dzsvp_permalink); ?>" class="regular-text code"> <span class="description"><?php _e('Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'dzsvp'); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">
            jQuery(function() {
                jQuery('input.dzsvptog').change(function() {
                    jQuery('#dzsvp_permalink_structure').val(jQuery(this).val());
                });

                jQuery('#dzsvp_permalink_structure').focus(function() {
                    jQuery('#dzsvp_custom_selection').click();
                });
            });
        </script>
        <?php
    }

    function permalink_settings_save() {
        if (!is_admin()) {
            return;
        }

        // We need to save the options ourselves; settings api does not trigger save for the permalinks page
        if (isset($_POST['dzsvp_permalink_structure']) || isset($_POST['dzsvp_category_base']) && isset($_POST['dzsvp_product_permalink'])) {
            // Cat and tag bases

//                                $product_category_slug = dzs_clean($_POST['dzsvp_product_category_slug']);
//                                $product_tag_slug = dzs_clean($_POST['dzsvp_product_tag_slug']);
//                                $product_attribute_slug = dzs_clean($_POST['dzsvp_product_attribute_slug']);

            $permalinks = get_option('dzsvp_permalinks');
            if (!$permalinks)
                $permalinks = array();

//                                $permalinks['category_base'] = untrailingslashit($dzsvp_product_category_slug);
//                                $permalinks['tag_base'] = untrailingslashit($dzsvp_product_tag_slug);
//                                $permalinks['attribute_base'] = untrailingslashit($dzsvp_product_attribute_slug);
            // Product base
            $product_permalink = dzs_clean($_POST['dzsvp_permalink']);

            if ($product_permalink == 'custom') {
                $product_permalink = dzs_clean($_POST['dzsvp_permalink_structure']);
            } elseif (empty($product_permalink)) {
                $product_permalink = false;
            }

            $permalinks['item_base'] = untrailingslashit($product_permalink);

            update_option('dzsvp_permalinks', $permalinks);
        }
    }


    function admin_init() {



        add_meta_box('dzsvg_meta_options',__('DZS Video Gallery Settings'),array($this,'admin_meta_options'),'post','normal');
        add_meta_box('dzsvg_meta_options',__('DZS Video Gallery Settings'),array($this,'admin_meta_options'),'page','normal');







        // Add a section to the permalinks page

        if($this->mainoptions['enable_video_showcase']=='on') {
            add_meta_box('dzsvp_meta_options', __('DZS Video Portal Item - Settings'), array($this, 'dzsvideo_admin_meta_options'), 'dzsvideo', 'normal');
            add_settings_section('dzsvp-permalink', __('Video Items Permalink Base', 'dzsvp'), array($this, 'permalink_settings'), 'permalink');
        }
    }

    function dzsvideo_admin_meta_options(){
        global $post, $wp_version;
        $struct_uploader = '<div class="dzsvg-wordpress-uploader">
<a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';
        //$wp_version = '3.4.1';
        if ($wp_version < 3.5) {
            $struct_uploader = '<div class="dzs-single-upload">
<input id="files-upload" class="" name="file_field" type="file">
</div>';
        }
        ?>
        <div class="select-hidden-con">
            <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>" />


            <?php
            echo '<div class="dzs-setting">
            <h4 class="setting-label">' . __('Select Featured Media Type', 'dzsvp') . '</h4>
                <div class="main-feed-chooser select-hidden-metastyle">';


            echo DZSHelpers::generate_select('dzsvp_item_type', array(
                'seekval'=>get_post_meta($post->ID, 'dzsvp_item_type', true),
                'options'=>array(
                array(
                    'value' => 'video',
                    'label' => __('video', 'dzsvp'),
                ),
                array(
                    'value' => 'youtube',
                    'label' => __('youtube', 'dzsvp'),
                ),
                array(
                    'value' => 'vimeo',
                    'label' => __('vimeo', 'dzsvp'),
                ),
                array(
                    'value' => 'inline',
                    'label' => __('inline', 'dzsvp'),
                ),
                ),
                'class' => 'textinput mainsetting',
                'def_value' => '',
            )
            );


            echo '<div class="option-con clearfix">
                    <div class="an-option" title="' . __('video', 'dzsvp') . '">
                    <div class="fullbg" style="background-image:url(' . $this->thepath . 'admin/img/hero-type-video.png' . ');"></div>
                    </div>
                    
                    <div class="an-option" title="' . __('youtube', 'dzsvp') . '">
                    <div class="fullbg" style="background-image:url(' . $this->thepath . 'admin/img/hero-type-video-youtube.png' . ');"></div>
                    </div>
                    
                    <div class="an-option" title="' . __('vimeo', 'dzsvp') . '">
                    <div class="fullbg" style="background-image:url(' . $this->thepath . 'admin/img/hero-type-video-vimeo.png' . ');"></div>
                    </div>
                    
                    <div class="an-option" title="' . __('inline', 'dzsvp') . '">
                    <div class="fullbg" style="background-image:url(' . $this->thepath . 'admin/img/hero-type-link.png' . ');"></div>
                    </div>
<div class="clear"></div>
                    
                </div>
            </div>
        </div>';
            ?>
            <div class="dzs-setting">
                <h4><?php echo __('Featured Media', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvp_featured_media', array('class' => 'upload-type-video main-source', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvp_featured_media', true))); ?>
                <?php echo $struct_uploader; ?>
                <div class='sidenote mode_video'><?php echo __('the path to the location of the mp4 / if you have a ogg for firefox too you can place it in the backup field below', 'dzsvp'); ?></div>
                <div class='sidenote mode_youtube mode_vimeo'><?php echo __('input here the id or the link of the video', 'dzsvp'); ?></div>
                <div class='sidenote mode_inline'><?php echo __('input here any html', 'dzsvp'); ?></div>
            </div>
            <div class="dzs-setting mode_video mode_audio">
                <h4><?php echo __('Featured Media OGG backup', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvp_sourceogg', array('class' => 'input-big-image', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvp_sourceogg', true))); ?>
                <?php echo $struct_uploader; ?>
                <div class='sidenote'><?php echo __('a backup ogg file for html5 streaming', 'dzsvp'); ?></div>
            </div>


            <div class="dzs-setting">
                <h4><?php echo __('Thumbnail', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvp_thumb', array('class' => 'input-big-image main-thumb', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvp_thumb', true))); ?>
                <?php echo $struct_uploader; ?>
                <button style="display: inline-block; vertical-align: middle;" class="refresh-main-thumb button-secondary">Auto Generate</button>
                <div class='sidenote'><?php echo __('select a thumbnail for the video ( can auto generate if it is an Vimeo or YouTube track )', 'dzsvp'); ?></div>
            </div>

            <div class="dzs-setting">
                <h4><?php echo __('Extra Classes', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvp_extra_classes', array('class' => '', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvp_extra_classes', true))); ?>
                <div class='sidenote'><?php echo __('[advanced] some extra classes that you want added to the portfolio item', 'dzsvp'); ?></div>
            </div>

        </div>

        <?php
    }

    function admin_meta_options() {
        global $post;
        ?>
        <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>" />
        <h4><?php _e("Fullscreen Gallery",'dzsvg'); ?></h4>
        <select class="textinput styleme" name="dzsvg_fullscreen">
            <option>none</option>
            <?php
            foreach ($this->mainitems as $it) {
                echo '<option ';
                dzs_checked(get_post_meta($post->ID,'dzsvg_fullscreen',true),$it['settings']['id'],'selected');
                echo '>'.$it['settings']['id'].'</option>';
            }
            ?>
        </select>
        <div class="clear"></div>

        <div class="sidenote">
            <?php echo __('Get a fullscreen gallery in your post / page with a close button.','dzsvg'); ?><br/>
        </div>
        <?php
    }

    function admin_meta_save($post_id) {
        global $post;
        if (!$post) {
            return;
        }
        if (isset($post->post_type) && !($post->post_type == 'post' || $post->post_type == 'page' || $post->post_type == 'dzsvideo')) {
            return $post_id;
        }
        /* Check autosave */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (isset($_REQUEST['dzs_nonce'])) {
            $nonce = $_REQUEST['dzs_nonce'];
            if (!wp_verify_nonce($nonce,'dzs_nonce'))
                wp_die('Security check');
        }
        if (isset($_POST['dzsvg_fullscreen'])) {
            dzs_savemeta($post->ID,'dzsvg_fullscreen',$_POST['dzsvg_fullscreen']);
        }
        if (isset($_POST['dzsvg_extras_featured'])) {
            dzs_savemeta($post->ID,'dzsvg_extras_featured',$_POST['dzsvg_extras_featured']);
        }


        if (is_array($_POST)) {
            $auxa = $_POST;
            foreach ($auxa as $label => $value) {

                //print_r($label); print_r($value);
                if (strpos($label, 'dzsvg_') !== false) {
                    dzs_savemeta($post_id, $label, $value);
                }
            }
        }
    }



    public function parse_videoitem($po, $pargs = array()) {

        global $dzsvg, $current_user;
        $po_id = $po->ID;

        $fout = '';

//        print_r($po);


        $margs = array(
            'disable_meta' => 'auto',
        );


        $margs = array_merge($margs,$pargs);
//        print_r($margs);


        $this->sliders_index++;
        $dzsvg->front_scripts();

        $target_playlist = '';
        $target_playlist_startnr = 0;

        //---playlist setup

        if (isset($_GET['dzsvp_user']) && isset($_GET['dzsvp_playlist'])) {
            $target_user_id = $_GET['dzsvp_user'];

            $target_playlists = get_user_meta($target_user_id, 'dzsvp_playlists', true);
            if (is_array($target_playlists)) {
                $target_playlists = json_encode($target_playlists);
            }
            $target_playlists = json_decode($target_playlists, true);

//                print_r($target_playlists);

            foreach ($target_playlists as $pl) {
                if ($pl['name'] == $_GET['dzsvp_playlist']) {
                    $target_playlist = $pl;
                    break;
                }
            }
        }

//            print_r($target_playlist);


        if($margs['disable_meta']!='on') {
            if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {
//                wp_enqueue_style('dzstabs', $this->thepath . 'dzstabs/dzstabs.css');
//                wp_enqueue_script('dzstabs', $this->thepath . 'dzstabs/dzstabs.js');
            }
        }


        $featured_media = get_post_meta($po_id, 'dzsvp_featured_media', true);
        $type = 'video';

        if (get_post_meta($po_id, 'dzsvp_item_type', true) != '') {
            $type = get_post_meta($po_id, 'dzsvp_item_type', true);
        }






        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = '';




        $vpsettingsdefault = array(
            'id' => 'default',
            'skin_html5vp' => 'skin_aurora',
            'html5design_controlsopacityon' => '1',
            'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'youtube_sdquality' => 'small',
            'youtube_hdquality' => 'hd720',
            'youtube_defaultquality' => 'hd',
            'yt_customskin' => 'on',
            'vimeo_byline' => '0',
            'vimeo_portrait' => '0',
            'vimeo_color' => '',
            'html5design_controlsopacityon' => '1',
            'html5design_controlsopacityout' => '1',
            'settings_video_overlay' => 'off',
            'settings_disable_mouse_out' => 'off',
        );
        $vpsettings = array();


        $vpconfig_id = $dzsvg->mainoptions['dzsvp_video_config'];

        if ($vpconfig_id != '') {
            //print_r($this->mainvpconfigs);
            for ($i = 0; $i < count($dzsvg->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $dzsvg->mainvpconfigs[$i]['settings']['id']))
                    $vpconfig_k = $i;
            }
            $vpsettings = $dzsvg->mainvpconfigs[$vpconfig_k];


            if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
                $vpsettings['settings'] = array();
            }
        }

        if (!isset($vpsettings['settings']) || (isset($vpsettings['settings']) && !is_array($vpsettings['settings']))) {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($vpsettingsdefault, $vpsettings['settings']);


        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {

            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';

            }else{

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }


//        print_r($vpsettings);

        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $fout.='<style>';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' { background-color:' . $dzsvg->mainoptions_dc['background'] . ';} ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .background{ background-color:' . $dzsvg->mainoptions_dc['controls_background'] . ';} ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .scrub-bg{ background-color:' . $dzsvg->mainoptions_dc['scrub_background'] . ';} ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .scrub-buffer{ background-color:' . $dzsvg->mainoptions_dc['scrub_buffer'] . ';} ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .playSimple{ border-left-color:' . $dzsvg->mainoptions_dc['controls_color'] . ';} #mainvpfromvp' . $this->sliders_index . ' .stopSimple .pause-part-1{ background-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple .pause-part-2{ background-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon{ background: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:before{ border-right-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volume_static{ background: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .hdbutton-con .hdbutton-normal{ color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .total-timetext{ color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .playSimple:hover{ border-left-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple:hover .pause-part-1{ background-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple:hover .pause-part-2{ background-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:hover{ background: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:hover:before{ border-right-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .volume_active{ background-color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .scrub{ background-color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .hdbutton-con .hdbutton-hover{ color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } ';
            $fout.='#mainvpfromvp' . $this->sliders_index . ' .curr-timetext{ color: ' . $dzsvg->mainoptions_dc['timetext_curr_color'] . '; } ';
            $fout.='</style>';
        }


        $fout.='<div class="mainvp-con">';


        if ($target_playlist) {



            wp_enqueue_style('dzs.scroller', $dzsvg->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $dzsvg->thepath . 'assets/dzsscroller/scroller.js');

            $fout.='<div class="videogallery-con currGallery" style="width:275px; height:300px; float:right; padding-top: 0; padding-bottom: 0;">
<div class="preloader"></div>
<div class="vg-playlist videogallery skin_default" style="width:275px; height:300px; opacity:0;">';


            $i5 = 0;

            foreach ($target_playlist['items'] as $it_id) {
                $it = get_post($it_id);

                $auxsrc = get_permalink($it_id);

                $auxsrc = add_query_arg('dzsvp_user', $_GET['dzsvp_user'], $auxsrc);
                $auxsrc = add_query_arg('dzsvp_playlist', $_GET['dzsvp_playlist'], $auxsrc);

                if (strpos(dzs_curr_url(), $auxsrc) !== false) {
                    $target_playlist_startnr = $i5;
                }

                $str_featuredimage = '';

                $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);


//                print_r($imgsrc);
                if($imgsrc){

                }else{
                    if (get_post_meta($po_id, 'dzsvp_thumb', true)) {
                        $imgsrc =  get_post_meta($it_id, 'dzsvp_thumb', true);
                    }
                }



                if ($imgsrc) {
                    $str_featuredimage = '<img src="' . $imgsrc[0] . '" class="imgblock"/>';
                } else {

                    if (get_post_meta($it_id, 'dzsvp_item_type', true) == 'youtube') {
                        $str_featuredimage = '<img src="http://img.youtube.com/vi/' . get_post_meta($it_id, 'dzsvp_featured_media', true) . '/0.jpg" class="imgblock"/>';
                    }
                }



                $fout.='<div class="vplayer-tobe" data-videoTitle="' . $it->post_title . '" data-type="link" data-src="' . $auxsrc . '">
<div class="menuDescription">' . $str_featuredimage . '
    <div class="the-title">' . $it->post_title . '</div> ' . $it->post_content . '
</div>
</div>';
                $i5++;
            }


            $fout.='</div></div>';
            $fout.='<div class="history-video-element" style="overflow:hidden;">';
        }

        $fout.='<div id="mainvpfromvp' . $this->sliders_index . '" class="vplayer-tobe" data-videoTitle="' . $po->post_title . '" data-type="' . $type . '" data-src="' . $featured_media . '"></div>';



        if ($target_playlist) {

            $fout.='</div>'; // end .history-video-element
        }


        if($margs['disable_meta']!='on'){
            if ($dzsvg->mainoptions['dzsvp_enable_likes'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_viewcount'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_likescount'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_ratingscount'] == 'on') {

                $nr_views = 0;
                $fout.='<div class="extra-html">';
                if ($dzsvg->mainoptions['dzsvp_enable_likes'] == 'on') {
//                print_r($_COOKIE);
                    $fout.='<div class="btn-like';
                    if (isset($_COOKIE['dzsvp_likesubmitted-' . $po_id]) && $_COOKIE['dzsvp_likesubmitted-' . $po_id] == '1') {
                        $fout.=' active';
                    }
                    $fout.='"><span class="the-icon"></span>Like</div>';
                }
                if ($dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on') {

                    $w_rate = 0;
                    if (get_post_meta($po_id, '_dzsvp_rate_index', true)) {
                        $w_rate = floatval(get_post_meta($po_id, '_dzsvp_rate_index', true)) * 122 / 5;
                    }
                    $fout.='<div class="star-rating-con"><div class="star-rating-bg"></div><div class="star-rating-set-clip" style="width: ' . $w_rate . 'px;"><div class="star-rating-prog"></div></div><div class="star-rating-prog-clip"><div class="star-rating-prog"></div></div></div>';
                }
                if ($dzsvg->mainoptions['dzsvp_enable_viewcount'] == 'on') {
                    if (get_post_meta($po_id, '_dzsvp_views', true) != '') {
                        $nr_views = intval(get_post_meta($po_id, '_dzsvp_views', true));
                    }
                    if (!isset($_COOKIE['dzsvp_viewsubmitted-' . $po_id])) {
                        $nr_views++;
                    }

                    $fout.='<div class="counter-hits"><span class="the-number">' . $nr_views . '</span> views</div>';

//                update_post_meta()
                }
                if ($dzsvg->mainoptions['dzsvp_enable_likescount'] == 'on') {
                    $fout.='<div class="counter-likes"><span class="the-number">';
                    if (get_post_meta($po_id, '_dzsvp_likes', true) == '') {
                        $fout.='0';
                    } else {
                        $fout.=get_post_meta($po_id, '_dzsvp_likes', true);
                    }
                    $fout.='</span> ' . __('likes', 'dzsvp') . '</div>';
                }
                if ($dzsvg->mainoptions['dzsvp_enable_ratingscount'] == 'on') {
                    $fout.='<div class="counter-rates"><span class="the-number">';

                    $nr_rates = 0;

//                echo 'cevahmm'.get_post_meta($po_id, '_dzsvp_rate_nr', true);
//                print_r($_COOKIE);
                    if (get_post_meta($po_id, '_dzsvp_rate_nr', true)) {
                        $nr_rates = intval(get_post_meta($po_id, '_dzsvp_rate_nr', true));
                    }

                    $fout.=$nr_rates . '</span> ' . __('ratings', 'dzsvp') . '</div>';
                }
                $fout.='</div>';
                //<span class="the-number">{{get_plays}}</span> plays</div>
            }

        }


        $fout.='<script>';


        if($margs['disable_meta']!='on') {
            if ($dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on') {
                if (isset($_COOKIE['dzsvp_ratesubmitted-' . $po_id])) {
                    $fout .= 'window.starrating_alreadyrated="' . $_COOKIE['dzsvp_ratesubmitted-' . $po_id] . '";';
                }
            };
        }

        $fout.='jQuery(document).ready(function($){ var videoplayersettings = {
autoplay : "on",
cueVideo : "on",
controls_out_opacity : "' . $vpsettings['settings']['html5design_controlsopacityon'] . '",
controls_normal_opacity : "' . $vpsettings['settings']['html5design_controlsopacityout'] . '"
,settings_hideControls : "off"
,settings_video_overlay : "' . $vpsettings['settings']['settings_video_overlay'] . '"
,settings_disable_mouse_out : "' . $vpsettings['settings']['settings_disable_mouse_out'] . '"
,settings_swfPath : "' . $dzsvg->thepath . 'preview.swf"
,design_skin: "' . $skin_vp . '"';

        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $fout.=',controls_fscanvas_bg:"' . $dzsvg->mainoptions_dc['controls_color'] . '"';
            $fout.=',controls_fscanvas_hover_bg:"' . $dzsvg->mainoptions_dc['controls_hover_color'] . '"';
            $fout.=',fpc_background:"' . $dzsvg->mainoptions_dc['background'] . '"';
            $fout.=',fpc_controls_background:"' . $dzsvg->mainoptions_dc['controls_background'] . '"';
            $fout.=',fpc_scrub_background:"' . $dzsvg->mainoptions_dc['scrub_background'] . '"';
            $fout.=',fpc_scrub_buffer:"' . $dzsvg->mainoptions_dc['scrub_buffer'] . '"';
            $fout.=',fpc_controls_color:"' . $dzsvg->mainoptions_dc['controls_color'] . '"';
            $fout.=',fpc_controls_hover_color:"' . $dzsvg->mainoptions_dc['controls_hover_color'] . '"';
            $fout.=',fpc_controls_highlight_color:"' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '"';
        };


        $fout.='}; jQuery("#mainvpfromvp' . $this->sliders_index . '").vPlayer(videoplayersettings);';



        if($margs['disable_meta']!='on') {
            if ($dzsvg->mainoptions['dzsvp_enable_viewcount'] == 'on') {
                if (!isset($_COOKIE['dzsvp_viewsubmitted-' . $po_id])) {
                    $fout .= 'var data = {
    action: "dzsvp_submit_view",
    postdata: "1",
    playerid: "' . $po_id . '"
};

$.ajax({
    type: "POST",
    url: "index.php",
    data: data,
    success: function(response) {
    },
    error:function(arg){
    }
});';

                    update_post_meta($po_id, '_dzsvp_views', $nr_views);
                };
            };
        }

        if ($target_playlist) {
            $fout.='dzsvg_init(".vg-playlist",{
totalWidth:275
,settings_mode:"normal"
,menuSpace:0
,randomise:"off"
,autoplay :"off"
,cueFirstVideo: "off"
,autoplayNext : "on"
,nav_type: "scroller"
,menuitem_width:275
,menuitem_height:75
,menuitem_space:1
,menu_position:"right"
,transition_type:"fade"
,design_skin: "skin_navtransparent"
,embedCode:""
,shareCode:""
,logo: ""
,responsive: "on"
,design_shadow:"off"
,settings_disableVideo:"on"
,startItem: "'.$target_playlist_startnr.'"
,settings_enableHistory: "off"
,settings_ajax_extraDivs: ""
});';
        }



        $fout.='});</script>'; // end document ready



        $fout.='</div>'; // end mainvp-con



        if($margs['disable_meta']!='on') {
            if (($dzsvg->mainoptions['dzsvp_tab_share_content'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') && !is_post_type_archive('dzsvideo')) {
//            return $fout;

                $fout .= '<div id="tabsclean" class="dzs-tabs dzs-tabs-dzsvp-page">';
                if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {
                    $fout .= '';
                    $fout .= '<div class="dzs-tab-tobe"><div class="tab-menu">' . __('About', 'dzsvp') . '</div>';
                    $fout .= '<div class="tab-content">';
                    $fout .= $po->post_content;
                    $this->sw_content_added = true;
                }


                if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {
                    $fout .= '</div>';
                    $fout .= '</div>'; //close .dzs-tab-tobe


                    if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '') {
                        $fout .= '<div class="dzs-tab-tobe">';
                        $fout .= '<div class="tab-menu">' . __('Share', 'dzsvp') . '</div>';

                        $aux_cont = $dzsvg->mainoptions['dzsvp_tab_share_content'];
                        $aux_cont = str_replace('{{currurl}}', urlencode(dzs_curr_url()), $aux_cont);


                        $auxembed = '<iframe src="' . $dzsvg->thepath . 'bridge.php?action=view&dzsvideo=' . $po_id . '" style="width:100%; height:300px; overflow:hidden;" scrolling="no" frameborder="0"></iframe>';

                        $aux_cont = str_replace('{{embedcode}}', htmlentities($auxembed), $aux_cont);


                        $fout .= '<div class="tab-content"><br>' . $aux_cont . '</div>';
                        $fout .= '</div>';
                    }


                    $fout .= '</div>'; //close .dzs-tabs

                    $fout .= '<script>
jQuery(document).ready(function($){
dzstaa_init(".dzs-tabs-dzsvp-page",{ \'design_tabsposition\' : \'top\'
                ,design_transition: \'slide\'
                ,design_tabswidth: \'default\'
                ,toggle_breakpoint : \'400\'
                 ,toggle_type: \'accordion\'});
});</script>';
                }
            }
        }

        return $fout;
    }


    function handle_init() {


//        global $post;
        wp_enqueue_script('jquery');
//        print_r($post);
        if (is_admin()) {
            wp_enqueue_style('dzsvg_admin_global',$this->thepath.'admin/admin_global.css');
            wp_enqueue_script('dzsvg_admin_global',$this->thepath.'admin/admin_global.js');
            wp_enqueue_style('zoombox',$this->thepath.'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox',$this->thepath.'assets/zoombox/zoombox.js');


            if($this->mainoptions['analytics_enable']=='on'){

                wp_enqueue_script('google.charts','https://www.gstatic.com/charts/loader.js');

                if($this->mainoptions['analytics_enable_location']=='on'){

                    wp_enqueue_script('google.maps','https://www.google.com/jsapi');
                }
            }

            if (isset($_GET['page']) && ($_GET['page'] == $this->adminpagename || $_GET['page'] == $this->adminpagename_configs)) {
                if (current_user_can($this->capability_admin) && function_exists('wp_enqueue_media')) {
                    wp_enqueue_media();
                }

                $this->admin_scripts();
            }
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_designercenter) {
                wp_enqueue_style('dzsvg-dc.style',$this->thepath.'deploy/designer/style/style.css');
                wp_enqueue_script('dzs.farbtastic',$this->thepath."admin/colorpicker/farbtastic.js");
                wp_enqueue_style('dzs.farbtastic',$this->thepath.'admin/colorpicker/farbtastic.css');
                wp_enqueue_script('dzsvg-dc.admin',$this->thepath.'admin/admin-dc.js');
                wp_enqueue_style('dzs.vplayer',$this->thepath.'videogallery/vplayer.css');
                wp_enqueue_script('dzs.vplayer',$this->thepath."videogallery/vplayer.js");


            }
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions) {
                wp_enqueue_style('dzsvg_admin',$this->thepath.'admin/admin.css');
                wp_enqueue_script('dzsvg_admin',$this->thepath."admin/admin-mo.js");
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-sortable');



                wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');



                wp_enqueue_style('dzstabsandaccordions',$this->thepath.'assets/dzstabsandaccordions/dzstabsandaccordions.css');
                wp_enqueue_script('dzstabsandaccordions',$this->thepath."assets/dzstabsandaccordions/dzstabsandaccordions.js");



                wp_enqueue_style('dzs.dzscheckbox',$this->thepath.'assets/dzscheckbox/dzscheckbox.css');


                wp_enqueue_style('dzs.dzstoggle',$this->thepath.'dzstoggle/dzstoggle.css');
                wp_enqueue_script('dzs.dzstoggle',$this->thepath.'dzstoggle/dzstoggle.js');


                if(isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder']=='on'){

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_script('dzsvg_shortcode_builder', $this->thepath . 'tinymce/popup.js');





                    wp_enqueue_media();
                }


                if(isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder']=='on'){

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_script('dzsvg_shortcode_builder', $this->thepath . 'tinymce/popup_showcase.js');


                    wp_enqueue_style('dzsselector', $this->thepath . 'assets/dzsselector/dzsselector.css');
                    wp_enqueue_script('dzsselector', $this->thepath . 'assets/dzsselector/dzsselector.js');


                    wp_enqueue_media();
                }



            }

            if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');
                wp_enqueue_script('dzsvg_htmleditor',$this->thepath.'tinymce/plugin-htmleditor.js');
                wp_enqueue_script('dzsvg_configreceiver',$this->thepath.'tinymce/receiver.js');
            }
        } else {
            if (isset($this->mainoptions['always_embed']) && $this->mainoptions['always_embed'] == 'on') {
                $this->front_scripts();
            }


            wp_enqueue_style('dzs.vplayer',$this->thepath.'videogallery/vplayer.css');
        }


        if($this->mainoptions['enable_video_showcase']=='on'){
            $this->register_links();


            $this->permalink_settings_save();
        }


        include_once($this->base_path.'vc/part-vcintegration.php');



    }





    function handle_print_media_templates(){

//        if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' )
//            return;
//        echo 'ceva';
        include_once dirname(__FILE__).'/admin/visualeditor/tmpl-editor-boutique-banner.html';
    }
    function handle_admin_print_footer_scripts(){
//        echo 'hmmdada';
        ?>
        <script>
            (function($){
                var media = wp.media, shortcode_string = 'dzs_videogallery';
                wp.mce = wp.mce || {};
                console.info(wp.mce);
//
                if(media){
                    wp.mce.dzs_videogallery = {
                        shortcode_data: {},
                        template: media.template( 'dzsvg-shortcode-preview' ),
                        getContent: function() {
                            var options = this.shortcode.attrs.named;
                            options.innercontent = this.shortcode.content;
                            return this.template(options);
                        },
                        View: {

                            template: media.template( 'dzsvg-shortcode-preview' ),
                            postID: $('#post_ID').val(),
                            initialize: function( options ) {
                                this.shortcode = options.shortcode;
                                wp.mce.boutique_banner.shortcode_data = this.shortcode;
                            },
                            getHtml: function() {
                                var options = this.shortcode.attrs.named;
                                options.innercontent = this.shortcode.content;
                                return this.template(options);
                            }
                        },
                        createInstance: function( node ) {


//                        console.info('update', this, node);

//                        return "alceva";
                        },
                        edit: function( node ) {


//                        console.info(this, node);

                            var parsel = '';

                            if(sel!=''){


                                var ed = window.tinyMCE.get('content');
                                var sel=ed.selection.getContent();


                                var ed_sel = ed.dom.select('div[data-wpview-text="'+this.encodedText+'"]')[0];
                                console.info(' the selection - ',ed.dom.select('div[data-wpview-text="'+this.encodedText+'"]')[0]);
                                window.remember_sel = ed_sel;
                                ed.selection.select(ed_sel);

//                            console.info('check sel - ',ed,ed.selection.getContent());

                                parsel+='&sel=' + encodeURIComponent(sel);
                                window.mceeditor_sel = sel;
                            }else{
                                window.mceeditor_sel = '';
                            }
                            //console.log(aux);


                            window.htmleditor_sel = 'notset';


                            window.dzszb_open(dzsvg_settings.shortcode_generator_url+parsel, 'iframe', {bigwidth: 1200, bigheight: 700,forcenodeeplink: 'on', dims_scaling: 'fill'});
//                        var data = window.decodeURIComponent( $( node ).attr('data-wpview-text') );
//                        console.debug(this);
//                        var values = this.shortcode_data.attrs.named;
//                        values['innercontent'] = this.shortcode_data.content;
//                        console.log(values);
//
//                        wp.mce.dzs_videogallery.popupwindow(tinyMCE.activeEditor, values);
                            //$( node ).attr( 'data-wpview-text', window.encodeURIComponent( shortcode ) );
                        },
                        // this is called from our tinymce plugin, also can call from our "edit" function above
                        // wp.mce.dzs_videogallery.popupwindow(tinyMCE.activeEditor, "bird");
                        popupwindow: function(editor, values, onsubmit_callback){
                            console.info('popupwindow');
                            if(typeof onsubmit_callback != 'function'){
                                onsubmit_callback = function( e ) {
                                    // Insert content when the window form is submitted (this also replaces during edit, handy!)
                                    var s = '[' + shortcode_string;
                                    for(var i in e.data){
                                        if(e.data.hasOwnProperty(i) && i != 'innercontent'){
                                            s += ' ' + i + '="' + e.data[i] + '"';
                                        }
                                    }
                                    s += ']';
                                    if(typeof e.data.innercontent != 'undefined'){
                                        s += e.data.innercontent;
                                        s += '[/' + shortcode_string + ']';
                                    }
                                    editor.insertContent( s );
                                };
                            }
                            editor.windowManager.open( {
                                title: 'Banner',
                                body: [
                                    {
                                        type: 'textbox',
                                        name: 'title',
                                        label: 'Title',
                                        value: values['title']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'link',
                                        label: 'Button Text',
                                        value: values['link']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'linkhref',
                                        label: 'Button URL',
                                        value: values['linkhref']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'innercontent',
                                        label: 'Content',
                                        value: values['innercontent']
                                    }
                                ],
                                onsubmit: onsubmit_callback
                            } );
                        }
                    };
                    wp.mce.views.register( shortcode_string, wp.mce.dzs_videogallery );
                }
            }(jQuery));
        </script>

        <?php
    }

    function handle_admin_menu() {





        global $current_user;

        $the_plugins = get_plugins();
        $pluginname = 'DZS Video Portal';

        foreach ($the_plugins as $plugin) {
            if ($plugin['Name'] == $pluginname) {
                if (defined('DZSVP_VERSION')) {
                    $this->addons_dzsvp_activated = true;
                }
            }
        }



        $admin_cap = $this->capability_admin;

//        echo 'ceva'.$this->addons_dzsvp_activated;
        if ($this->mainoptions['admin_enable_for_users'] == 'on') {
            $this->capability_user = 'read';



            //if current user is not an admin then it is a user and should have it's own database
            if (current_user_can($this->capability_admin) == false) {
                //print_r($current_user);

                $currDb = 'user'.$current_user->data->ID;
                //echo 'ceva'; print_r($this->dbs);
                if ($currDb != 'main' && $currDb != '') {
                    $this->dbitemsname.='-'.$currDb;
                }
                $this->currDb = $currDb;

                if (is_array($this->dbs) && !in_array($currDb,$this->dbs) && $currDb != 'main' && $currDb != '') {
                    array_push($this->dbs,$currDb);
                    update_option($this->dbdbsname,$this->dbs);
                }

                $this->mainitems = get_option($this->dbitemsname);
                if ($this->mainitems == '') {

                    $mainitems_default_ser = file_get_contents(dirname(__FILE__).'/sampledata/defaultmainitems.txt');
                    $this->mainitems = unserialize($mainitems_default_ser);

                    update_option($this->dbitemsname,$this->mainitems);
                }
            }
            $admin_cap = $this->capability_user;
        }




        $dzsvg_page = add_menu_page(__('Video Gallery','dzsvg'),__('Video Gallery','dzsvg'),$admin_cap,$this->adminpagename,array($this,'admin_page'),'div');
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Video Player Configs','dzsvg'),__('Video Player Configs','dzsvg'),$this->capability_admin,$this->adminpagename_configs,array($this,'admin_page_vpc'));
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Designer Center','dzsvg'),__('Designer Center','dzsvg'),$this->capability_admin,$this->adminpagename_designercenter,array($this,'admin_page_dc'));
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Video Gallery Settings','dzsvg'),__('Settings','dzsvg'),$this->capability_admin,$this->adminpagename_mainoptions,array($this,'admin_page_mainoptions'));
        $dzsvg_subpage = add_submenu_page($this->adminpagename,__('Autoupdater','dzsvg'),__('Autoupdater','dzsvg'),$this->capability_admin,$this->adminpagename_autoupdater,array($this,'admin_page_autoupdater'));

    }

    function admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('tiny_mce');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('dzsvg_admin',$this->thepath."admin/admin.js");
        wp_enqueue_style('dzsvg_admin',$this->thepath.'admin/admin.css');
        wp_enqueue_script('dzs.farbtastic',$this->thepath."admin/colorpicker/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic',$this->thepath.'admin/colorpicker/farbtastic.css');
        wp_enqueue_style('dzsvg_dzsuploader',$this->thepath.'admin/dzsuploader/upload.css');
        wp_enqueue_script('dzsvg_dzsuploader',$this->thepath.'admin/dzsuploader/upload.js');
        wp_enqueue_style('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.css');
        wp_enqueue_script('dzs.scroller',$this->thepath.'assets/dzsscroller/scroller.js');
        wp_enqueue_style('dzs.dzscheckbox',$this->thepath.'assets/dzscheckbox/dzscheckbox.css');

        wp_enqueue_style('dzs.dzstoggle',$this->thepath.'dzstoggle/dzstoggle.css');
        wp_enqueue_script('dzs.dzstoggle',$this->thepath.'dzstoggle/dzstoggle.js');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
    }

    function front_scripts() {
        //print_r($this->mainoptions);
        $videogalleryscripts = array('jquery');
        wp_enqueue_script('dzs.vplayer',$this->thepath."videogallery/vplayer.js");


//        wp_enqueue_script('dzs.flashhtml5main', $this->thepath . "videogallery/flashhtml5main.js");
        wp_enqueue_style('dzs.vgallery.skin.custom',$this->thepath.'customs/skin_custom.css');


        if($this->mainoptions['disable_fontawesome']!='on'){

            wp_enqueue_style('fontawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
        }

        //if($this->mainoptions['embed_masonry']=='on'){
        //wp_enqueue_script('jquery.masonry', $this->thepath . "masonry/jquery.masonry.min.js");
        //}
    }

    function add_simple_field($pname,$otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';

        $args = array(
            'val' => ''
        );
        $args = array_merge($args,$otherargs);

        $val = $args['val'];

        //====check if the data from database txt corresponds
        if (isset($data[$pname])) {
            $val = $data[$pname];
        }
        $fout.='<div class="setting"><input type="text" class="textinput short" name="'.$pname.'" value="'.$val.'"></div>';
        echo $fout;
    }

    function add_cb_field($pname) {
        global $data;
        $fout = '';
        $val = '';
        if (isset($data[$pname]))
            $val = $data[$pname];
        $checked = '';
        if ($val == 'on')
            $checked = ' checked';

        $fout.='<div class="setting"><input type="checkbox" class="textinput" name="'.$pname.'" value="on" '.$checked.'/> on</div>';
        echo $fout;
    }

    function add_cp_field($pname,$otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';


        $args = array(
            'val' => '',
            'class' => '',
        );

        $args = array_merge($args,$otherargs);



        //print_r($args);
        $val = $args['val'];


        $fout.='
<div class="setting-input"><input type="text" class="textinput with-colorpicker '.$args['class'].'" name="'.$pname.'" value="'.$val.'">
<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
</div>';
        return $fout;
    }

    function admin_page_dc() {
        $dc_config = array(
            'ispreview' => 'off'
        );

        include_once("tinymce/popupiframe_designer_center.php");
        ?>


        <?php
    }

    function misc_input_text($argname,$pargs=array()) {
        $fout = '';

        $margs = array(
            'type' => 'text',
            'class' => '',
            'seekval' => '',
            'extra_attr' => '',
        );


        $margs = array_merge($margs,$pargs);

        $type = 'text';
        if (isset($margs['type'])) {
            $type =$margs['type'];
        }
        $fout.='<input type="'.$type.'"';
        if (isset($margs['class'])) {
            $fout.=' class="'.$margs['class'].'"';
        }
        $fout.=' name="'.$argname.'"';
        if (isset($margs['seekval'])) {
            $fout.=' value="'.$margs['seekval'].'"';
        }

        $fout.=$margs['extra_attr'];

        $fout.='/>';
        return $fout;
    }

    function misc_input_textarea($argname,$otherargs = array()) {
        $fout = '';
        $fout.='<textarea';
        $fout.=' name="'.$argname.'"';

        $margs = array(
            'class' => '',
            'val' => '',// === default value
            'seekval' => '',// ===the value to be seeked
            'type' => '',
        );
        $margs = array_merge($margs,$otherargs);



        if ($margs['class'] != '') {
            $fout.=' class="'.$margs['class'].'"';
        }
        $fout.='>';
        if (isset($margs['seekval']) && $margs['seekval'] != '') {
            $fout.=''.$margs['seekval'].'';
        } else {
            $fout.=''.$margs['val'].'';
        }
        $fout.='</textarea>';

        return $fout;
    }

    function misc_input_checkbox($argname,$argopts) {
        $fout = '';
        $auxtype = 'checkbox';

        if (isset($argopts['type'])) {
            if ($argopts['type'] == 'radio') {
                $auxtype = 'radio';
            }
        }
        $fout.='<input type="'.$auxtype.'"';
        $fout.=' name="'.$argname.'"';
        if (isset($argopts['class'])) {
            $fout.=' class="'.$argopts['class'].'"';
        }
        $theval = 'on';
        if (isset($argopts['val'])) {
            $fout.=' value="'.$argopts['val'].'"';
            $theval = $argopts['val'];
        } else {
            $fout.=' value="on"';
        }
        //print_r($this->mainoptions); print_r($argopts['seekval']);
        if (isset($argopts['seekval'])) {
            $auxsw = false;
            if (is_array($argopts['seekval'])) {
                //echo 'ceva'; print_r($argopts['seekval']);
                foreach ($argopts['seekval'] as $opt) {
                    //echo 'ceva'; echo $opt; echo
                    if ($opt == $argopts['val']) {
                        $auxsw = true;
                    }
                }
            } else {
                //echo $argopts['seekval']; echo $theval;
                if ($argopts['seekval'] == $theval) {
                    //echo $argval;
                    $auxsw = true;
                }
            }
            if ($auxsw == true) {
                $fout.=' checked="checked"';
            }
        }
        $fout.='/>';
        return $fout;
    }

    function admin_page_mainoptions() {
        //print_r($this->mainoptions);




        if(isset($_GET['dzsvp_shortcode_builder']) && $_GET['dzsvp_shortcode_builder']=='on') {

            do_action('dzsvg_mainoptions_before_wrap');
        }elseif(isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder']=='on'){
            dzsvg_shortcode_builder();
        }elseif(isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder']=='on'){
            dzsvg_shortcode_showcase_builder();
        }else {

            if (isset($_POST['dzsvg_delete_cache']) && $_POST['dzsvg_delete_cache'] == 'on') {
                delete_option('dzsvg_cache_ytuserchannel');
                delete_option('dzsvg_cache_ytplaylist');
                delete_option('dzsvg_cache_ytkeywords');
                delete_option('cache_dzsvg_vmuser');
                delete_option('cache_dzsvg_vmchannel');
                delete_option('cache_dzsvg_vmalbum');
                delete_option('dzsvg_cache_vmalbum');
                delete_option('dzsvg_cache_vmchannel');
                delete_option('dzsvg_cache_vmuser');
            }
//        print_r($this->mainoptions);
            ?>

            <div class="wrap">
                <h2><?php echo __('Video Gallery Main Settings', 'dzsvg'); ?></h2>
                <br/>

                <form class="mainsettings">

                    <a class="zoombox button-secondary" href="<?php echo $this->thepath; ?>readme/index.html" data-bigwidth="1100" data-scaling="fill" data-bigheight="700"><?php echo __("Documentation"); ?></a>


                    <?php
                    do_action('dzsvg_mainoptions_before_tabs');
                    ?>

                    <h3><?php echo __("Admin Options"); ?></h3>

                    <div class="dzs-tabs auto-init" data-options="{ 'design_tabsposition' : 'top'
                ,design_transition: 'fade'
                ,design_tabswidth: 'default'
                ,toggle_breakpoint : '400'
                 ,toggle_type: 'accordion'
                 ,settings_appendWholeContent: true
                 }">

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-tachometer"></i> <?php echo __("Settings"); ?>
                            </div>
                            <div class="tab-content">
                                <br>





                                <div class="setting">

                                    <?php
                                    $lab = 'always_embed';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Always Embed Scripts?','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )','dzsapp'); ?></div>
                                </div>




                                <div class="setting">

                                    <?php
                                    $lab = 'disable_fontawesome';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Disable FontAwesome','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('do not include the fontawesome library','dzsapp'); ?></div>
                                </div>




                                <div class="setting">

                                    <?php
                                    $lab = 'settings_trigger_resize';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Force Refresh Size Every 1000ms','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('sometimes sizes need to be recalculated ( for example if you use the gallery in tabs )','dzsapp'); ?></div>
                                </div>








                                <div class="setting">

                                    <?php
                                    $lab = 'replace_wpvideo';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Replace [video] Shortcode for Simple Videos','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('render simple wp videos with DZS Video Gallery','dzsapp'); ?></div>
                                </div>








                                <div class="setting">

                                    <?php
                                    $lab = 'enable_auto_backup';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Enable Autobackup','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('enable once per day autobackup of the main database','dzsapp'); ?></div>
                                </div>








                                <div class="setting">

                                    <?php
                                    $lab = 'enable_video_showcase';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Enable Video Showcase','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('enable Video Items and special Showcase options','dzsapp'); ?></div>
                                </div>






                                <!-- end general settings -->



                            </div>
                        </div>

                        <div class="dzs-tab-tobe tab-disabled">
                            <div class="tab-menu ">
                                &nbsp;&nbsp;
                            </div>
                            <div class="tab-content">

                            </div>
                        </div>

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-gear"></i> <?php echo __("Developer"); ?>
                            </div>
                            <div class="tab-content">
                                <br>



                                <?php
                                $lab = 'is_safebinding';
                                echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                ?>
                                <div class="setting">
                                    <h4 class="setting-label"><?php echo __('Safe binding?','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php

                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('the galleries admin can use a complex ajax backend to ensure fast editing, but this can cause limitation issues on php servers. Turn this to on if you want a faster editing experience ( and if you have less then 20 videos accross galleries ) '); ?></div>
                                </div>



                                <?php
                                $lab = 'disable_api_caching';
                                echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                ?>
                                <div class="setting">
                                    <h4 class="setting-label"><?php echo __('Do Not Use Caching','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php

                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('use caching for vimeo / youtube api ( recommended - on )'); ?></div>
                                </div>








                                <div class="setting">

                                    <?php
                                    $lab = 'admin_enable_for_users';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Enable Visitors Gallery Access','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('your logged in users will be able to have their own galleries','dzsapp'); ?></div>
                                </div>







                                <div class="setting">

                                    <?php
                                    $lab = 'force_file_get_contents';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Force File Get Contents','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('sometimes curl will not work for retrieving youtube user name / playlist - try enabling this option if so...','dzsapp'); ?></div>
                                </div>





                                <div class="setting">

                                    <?php
                                    $lab = 'replace_jwplayer';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Replace JWPlayer','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('render jw player shortcodes with DZS Video Gallery','dzsapp'); ?></div>
                                </div>



                                <div class="setting">

                                    <?php
                                    $lab = 'include_featured_gallery_meta';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Include Featured Gallery Option','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('only works on certain themes','dzsapp'); ?></div>
                                </div>





                                <div class="setting">

                                    <?php
                                    $lab = 'tinymce_enable_preview_shortcodes';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Enable Preview Shortcodes in TinyMce Editor','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('add a box with the shortcode in the tinymce Visual Editor','dzsapp'); ?></div>
                                </div>





                                <div class="setting">

                                    <?php
                                    $lab = 'debug_mode';
                                    echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                    ?>
                                    <h4 class="setting-label"><?php echo __('Debug Mode','dzsapp'); ?></h4>
                                    <div class="dzscheckbox skin-nova">
                                        <?php
                                        echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                        <label for="<?php echo $lab; ?>"></label>
                                    </div>
                                    <div class="sidenote"><?php echo __('activate debug mode ( advanced mode )','dzsapp'); ?></div>
                                </div>

















                                <!-- end developer settings -->



                            </div>
                        </div>

                        <div class="dzs-tab-tobe tab-disabled">
                            <div class="tab-menu ">
                                &nbsp;&nbsp;
                            </div>
                            <div class="tab-content">

                            </div>
                        </div>

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-paint-brush"></i> <?php echo __("Appearance") ?>
                            </div>
                            <div class="tab-content">
                                <br>



                                <?php
                                $lab = 'translate_skipad';
                                echo '
                                   <div class="setting">
                                       <div class="label">' . __('Translate Skip Ad', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                   </div>';
                                ?>

                                <div class="setting">
                                    <div class="label"><?php echo __('Extra CSS', 'dzsp'); ?></div>
                                    <?php echo $this->misc_input_textarea('extra_css', array('val' => '', 'seekval' => $this->mainoptions['extra_css'])); ?>
                                    <div class="sidenote"><?php echo __('', 'dzsp'); ?></div>
                                </div>

                            </div>
                        </div>

                        <div class="dzs-tab-tobe tab-disabled">
                            <div class="tab-menu ">
                                &nbsp;&nbsp;
                            </div>
                            <div class="tab-content">

                            </div>
                        </div>

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-vimeo"></i> <?php echo __("Vimeo") ?>
                            </div>
                            <div class="tab-content">
                                <br>


                                <div class="setting">
                                    <div class="label"><?php echo __('Vimeo Thumbnail Quality', 'dzsvp'); ?></div>
                                    <?php
                                    $arr_opts = array(
                                        array(
                                            'lab'=>__('Low Quality'),
                                            'val'=>'low',
                                        ),
                                        array(
                                            'lab'=>__('Medium Quality'),
                                            'val'=>'medium',
                                        ),
                                        array(
                                            'lab'=>__('High Quality'),
                                            'val'=>'high',
                                        ),
                                    );

                                    $lab = 'vimeo_thumb_quality';
                                    $val = $this->mainoptions[$lab];
                                    echo DZSHelpers::generate_select($lab, array('options' => $arr_opts, 'class' => 'styleme', 'seekval' => $val));
                                    ?>
                                </div>



                                <?php
                                $lab = 'vimeo_api_user_id';
                                echo '
                                   <div class="setting">
                                       <div class="label">' . __('Your User ID', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">' . __('get it from https://vimeo.com/settings, must be in the form of user123456 ', 'dzsvg') . '</div>
                                   </div>';

                                $lab = 'vimeo_api_client_id';
                                echo '
                                   <div class="setting">
                                       <div class="label">' . __('Client ID', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">' . __('you can get an api key from <a href="https://developer.vimeo.com/apps">here</a> - section <strong>oAuth2</strong> from the app ', 'dzsvg') . '</div>
                                   </div>';


                                $lab = 'vimeo_api_client_secret';
                                echo '
                                   <div class="setting">
                                       <div class="label">' . __('Client Secret', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                   </div>';


                                $lab = 'vimeo_api_access_token';
                                echo '
                                   <div class="setting">
                                       <div class="label">' . __('Access Token', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                   </div>';
                                ?>


                            </div>


                        </div>

                        <div class="dzs-tab-tobe tab-disabled">
                            <div class="tab-menu ">
                                &nbsp;&nbsp;
                            </div>
                            <div class="tab-content">

                            </div>
                        </div>

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-youtube"></i> <?php echo __("YouTube") ?>
                            </div>
                            <div class="tab-content">
                                <br>






                                <?php


                                echo '
                <div class="setting">
                    <div class="label">' . __('YouTube API Key', 'dzsvg') . '</div>
                    ' . $this->misc_input_text('youtube_api_key', array('val' => '', 'seekval' => $this->mainoptions['youtube_api_key'])) . '
                    <div class="sidenote">' . __('get a api key <a href="https://console.developers.google.com">here</a>, create a new project, access API > <strong>APIs</strong> and enabled YouTube Data API, then create your Public API Access from API > Credentials', 'dzsvg') . '</div>
                    <div class="sidenote">' . __('remember, do not enter anything in referers field, unless you know what you are doing, leave it clear like so - <a href="https://lh3.googleusercontent.com/5eps7rIYzxwpO5ftxy4D6GiMdimShMRWM7XE0-pQ5lI=w1221-h950-no">here</a>', 'dzsvg') . '</div>
                </div>';

                                ?>





                            </div>
                        </div>



                        <div class="dzs-tab-tobe tab-disabled">
                            <div class="tab-menu ">
                                &nbsp;&nbsp;
                            </div>
                            <div class="tab-content">
                                <br>


                            </div>
                        </div>

                        <div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-bar-chart"></i> <?php echo __("Analytics") ?>
                            </div>
                            <div class="tab-content">
                                <br>


                                <div class="dzs-container">
                                    <div class="one-half">
                                        <div class="setting">

                                            <?php
                                            $lab = 'analytics_enable';
                                            echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                            ?>
                                            <h4 class="setting-label"><?php echo __('Enable Analytics','dzsapp'); ?></h4>
                                            <div class="dzscheckbox skin-nova">
                                                <?php
                                                echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                                <label for="<?php echo $lab; ?>"></label>
                                            </div>
                                            <div class="sidenote"><?php echo __('activate analytics for the galleries','dzsapp'); ?></div>
                                        </div>


                                        <div class="setting">

                                            <?php
                                            $lab = 'analytics_enable_location';
                                            echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                            ?>
                                            <h4 class="setting-label"><?php echo __('Track Users Country?','dzsapp'); ?></h4>
                                            <div class="dzscheckbox skin-nova">
                                                <?php
                                                echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                                <label for="<?php echo $lab; ?>"></label>
                                            </div>
                                            <div class="sidenote"><?php echo __('use geolocation to track users country','dzsapp'); ?></div>
                                        </div>

                                        <div class="setting">

                                            <?php
                                            $lab = 'analytics_enable_user_track';
                                            echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
                                            ?>
                                            <h4 class="setting-label"><?php echo __('Track Statistic by User?','dzsapp'); ?></h4>
                                            <div class="dzscheckbox skin-nova">
                                                <?php
                                                echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on','seekval' => $this->mainoptions[$lab])); ?>
                                                <label for="<?php echo $lab; ?>"></label>
                                            </div>
                                            <div class="sidenote"><?php echo __('track views and minutes watched of each user','dzsapp'); ?></div>
                                        </div>




                                    </div>





                                    <div class="one-half">


                                        <div class="setting">

                                            <h4 class="setting-label"><?php echo __('Enabled Galleries','dzsapp'); ?></h4>
                                            <?php
                                            $lab = 'analytics_galleries[]';


                                            $vals = $this->mainoptions['analytics_galleries'];

                                            //                                               print_r($vals);



                                            foreach($this->mainitems as $lab2 => $it){
                                                //,'seekval' => $this->mainoptions[$lab]
                                                ?>

                                                <div class="dzscheckbox ">
                                                    <?php
                                                    echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => $lab2, 'seekval'=>$vals  )); ?>
                                                    <label><?php echo __("Gallery"); ?> <?php echo ($lab2+1); ?></label>
                                                </div>


                                                <?php
                                            }
                                            ?>

                                        </div>

                                    </div>

                                </div>






                            </div>
                        </div>

                    </div><!-- end .dzs-tabs -->














                    <?php






                    do_action('dzsvg_mainoptions_extra');
                    ?>
                    <br/>
                    <a href='#'
                       class="button-primary dzsvg-mo-save-mainoptions"><?php echo __('Save Options', 'dzsvg'); ?></a>
                </form>
                <br/><br/>

                <form class="mainsettings" method="POST">
                    <button name="dzsvg_delete_cache" value="on"
                            class="button-secondary"><?php echo __('Delete All Caches', 'dzsvg'); ?></button>
                </form>
                <div class="sidenote">Delete all YouTube and Vimeo channel feeds caches</div>
                <br/>

                <div class="saveconfirmer" style=""><img alt="" style="" id="save-ajax-loading2"
                                                         src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/>
                </div>
            </div>
            <div class="clear"></div><br/>
            <?php
        }
    }

    function admin_page_autoupdater(){

        ?>
        <div class="wrap">



            <?php

            $auxarray = array();


            if(isset($_GET['dzsvg_purchase_remove_binded']) && $_GET['dzsvg_purchase_remove_binded']=='on'){

                $this->mainoptions['dzsvg_purchase_code_binded']='off';

                update_option($this->dboptionsname,$this->mainoptions);

            }

            if(isset($_POST['action']) && $_POST['action']==='dzsvg_update_request'){





                if(isset($_POST['dzsvg_purchase_code'])){
                    $auxarray= array('dzsvg_purchase_code' => $_POST['dzsvg_purchase_code']);
                    $auxarray = array_merge($this->mainoptions, $auxarray);

                    $this->mainoptions= $auxarray;


                    update_option($this->dboptionsname,$auxarray);
                }



            }

            $extra_class = '';
            $extra_attr = '';
            $form_method = "POST";
            $form_action = "";
            $disable_button = '';

            $lab = 'dzsvg_purchase_code';

            if($this->mainoptions['dzsvg_purchase_code_binded']=='on'){
                $extra_attr = ' disabled';
                $disable_button = ' <input type="hidden" name="purchase_code" value="'.$this->mainoptions[$lab].'"/><input type="hidden" name="site_url" value="'.site_url().'"/><input type="hidden" name="redirect_url" value="'.esc_url(add_query_arg('dzsvg_purchase_remove_binded','on',dzs_curr_url())).'"/><button class="button-secondary" name="action" value="dzsvg_purchase_code_disable">'.__("Disable Key").'</button>';
                $form_action=' action="http://zoomthe.me/updater_dzsvg/servezip.php"';
            }





            echo '<form'.$form_action.' class="mainsettings" method="'.$form_method.'">';

            echo '
                <div class="setting">
                    <div class="label">'.__('Purchase Code','dzsvg').'</div>
                    '.$this->misc_input_text($lab,array('val' => '','seekval' => $this->mainoptions[$lab],'class' => $extra_class,'extra_attr' => $extra_attr)).$disable_button.'
                    <div class="sidenote">'.__('You can <a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="“_blank”">find it here</a> ','dzsvg').'</div>
                </div>';


            if($this->mainoptions['dzsvg_purchase_code_binded']=='on'){
                echo '</form><form class="mainsettings" method="post">';
            }

            echo '<p><button class="button-primary" name="action" value="dzsvg_update_request">'.__("Update").'</button></p>';

            if(isset($_POST['action']) && $_POST['action']==='dzsvg_update_request'){



//            echo 'ceva';


//            die();



                $aux = 'http://zoomthe.me/updater_dzsvg/servezip.php?purchase_code='.$this->mainoptions['dzsvg_purchase_code'].'&site_url='.site_url();
                $res = DZSHelpers::get_contents($aux);

//            echo 'hmm'; echo strpos($res,'<div class="error">'); echo 'dada'; echo $res;
                if($res===false){
                    echo 'server offline';
                }else{
                    if(strpos($res,'<div class="error">')===0){
                        echo $res;


                        if(strpos($res,'<div class="error">error: in progress')===0){

                            $this->mainoptions['dzsvg_purchase_code_binded']='on';
                            update_option($this->dboptionsname,$this->mainoptions);
                        }
                    }else{

                        file_put_contents(dirname(__FILE__).'/update.zip', $res);
                        if(class_exists('ZipArchive')){
                            $zip = new ZipArchive;
                            $res = $zip->open(dirname(__FILE__).'/update.zip');
                            //test
                            if ($res === TRUE) {
//                echo 'ok';
                                $zip->extractTo(dirname(__FILE__));
                                $zip->close();


                                $this->mainoptions['dzsvg_purchase_code_binded']='on';
                                update_option($this->dboptionsname,$this->mainoptions);


                            } else {
                                echo 'failed, code:' . $res;
                            }
                            echo __('Update done.');
                        }else{

                            echo __('ZipArchive class not found.');
                        }

                    }
                }
            }





            ?>
            </form>
        </div>
        <?php
    }

    function admin_page() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <form class="dzs-container" enctype="multipart/form-data" action="" method="POST">
                            <div class="one-half">
                                <h3>Import Database</h3>
                                <input name="dzsvg_importdbupload" type="file" size="10"/><br />
                            </div>
                            <div class="one-half  alignright">
                                <input class="button-secondary" type="submit" name="dzsvg_importdb" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>


                        <form class="dzs-container" enctype="multipart/form-data" action="" method="POST">
                            <div class="one-half">
                                <h3>Import Slider</h3>
                                <input name="importsliderupload" type="file" size="10"/><br />
                            </div>
                            <div class="one-half  alignright">
                                <input class="button-secondary" type="submit" name="dzsvg_importslider" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>

                        <div  class="dzs-container">
                            <div class="one-half">
                                <h3>Export Database</h3>
                            </div>
                            <div class="one-half  alignright">
                                <form action="" method="POST"><input class="button-secondary" type="submit" name="dzsvg_exportdb" value="Export"/></form>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('Video Gallery Admin','dzsvg'); ?>&nbsp; <span class="version-number" style="font-size:13px; font-weight: 100;">version <span class="now-version"><?php echo DZSVG_VERSION; ?></span></span> <img alt="" style="visibility: visible;" id="main-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/></h2>
            <noscript><?php _e('You need javascript for this.','dzsvg'); ?></noscript>
            <?php
            if (current_user_can($this->capability_admin)) {
                ?><div class="top-buttons">
                <a href="<?php echo $this->thepath; ?>readme/index.html" class="button-secondary action"><?php _e('Documentation','dzsvg'); ?></a>
                <a href="<?php echo admin_url('admin.php?page=dzsvg-dc'); ?>" target="_blank" class="button-secondary action"><?php _e('Go to Designer Center','dzsvg'); ?></a>
                <div class="super-select db-select dzsvg"><button class="button-secondary btn-show-dbs">Current Database - <span class="strong currdb"><?php
                            if ($this->currDb == '') {
                                echo 'main';
                            } else {
                                echo $this->currDb;
                            }
                            ?></span></button>
                    <select class="main-select hidden"><?php
                        //print_r($this->dbs);

                        if (is_array($this->dbs)) {
                            foreach ($this->dbs as $adb) {
                                $params = array('dbname' => $adb);
                                $newurl = esc_url(add_query_arg($params,dzs_curr_url()));
                                echo '<option'.' data-newurl="'.$newurl.'"'.'>'.$adb.'</option>';
                            }
                        } else {
                            $params = array('dbname' => 'main');
                            $newurl = esc_url(add_query_arg($params,dzs_curr_url()));
                            echo '<option'.' data-newurl="'.$newurl.'"'.' selected="selected"'.'>'.$adb.'</option>';
                        }
                        ?></select><div class="hidden replaceurlhelper"><?php
                        $params = array('dbname' => 'replaceurlhere');
                        $newurl = esc_url(add_query_arg($params,dzs_curr_url()));
                        echo $newurl;
                        ?></div>
                </div>
                </div><?php
            }
            ?><table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col"><?php echo __('ID','dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Edit','dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Embed','dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Export','dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Duplicate','dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Delete','dzsvg'); ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $items = $this->mainitems;
            //echo count($items);

            $aux =  (remove_query_arg('deleteslider',dzs_curr_url()));

            $nextslidernr = count($items);
            if ($nextslidernr < 1) {
                $nextslidernr = 1;
            }
            $params = array('currslider' => $nextslidernr);


//            echo 'curr link - ' . $aux;
            $url_add = esc_url(add_query_arg($params,$aux));
            ?>
            <a class="button-secondary add-slider" href="<?php echo $url_add; ?>"><?php _e('Add Slider','dzsvg'); ?></a>
            <form class="master-settings">
            </form>
            <div class="block">
                <div class="extra-options">
                    <h3><?php echo __('Import','dzsvg'); ?></h3>
                    <!-- demo/ playlist: ADC18FE37410D250, user: digitalzoomstudio, vimeo: 5137664 -->
                    <input type="text" name="import_inputtext" id="import_inputtext" value="digitalzoomstudio"/>
                    <div class="sidenote"><?php _e('Import here feed from a YT Playlist, YT User Channel or Vimeo User Channel - you just have to enter the 
                        id of the playlist / user id in the box below and select the correct type from below','dzsvg').'. Remember to set the <strong>Feed From</strong> field to <strong>Normal</strong> after your videos have been imported.'; ?></div>
                    <a href="#" id="importytplaylist" class="button-secondary">YouTube Playlist</a>
                    <a href="#" id="importytuser" class="button-secondary">YouTube User Channel</a>
                    <a href="#" id="importvimeouser" class="button-secondary">Vimeo User Channel</a>
                    <br/>
                    <span class="import-error" style="display:none;"></span>
                </div>
            </div>
            <div class="dzs-multi-upload">
                <h3>Choose file(s)</h3>
                <div>
                    <input class="files-upload multi-uploader" name="file_field" type="file" multiple>
                </div>
                <div class="droparea">
                    <div class="instructions">drag & drop files here</div>
                </div>
                <div class="upload-list-title">The Preupload List</div>
                <ul class="upload-list">
                    <li class="dummy">add files here from the button or drag them above</li>
                </ul>
                <button class="primary-button upload-button">Upload All</button>
            </div>
            <div class="notes">
                <div class="curl">Curl: <?php echo function_exists('curl_version') ? 'Enabled' : 'Disabled'.'<br />'; ?>
                </div>
                <div class="fgc">File Get Contents: <?php echo ini_get('allow_url_fopen') ? "Enabled" : "Disabled"; ?>
                </div>
                <div class="sidenote"><?php _e('If neither of these are enabled, only normal feed will work. 
                    Contact your host provider on how to enable these services to use the YouTube User Channel 
                    or YouTube Playlist feed.','dzsvg'); ?>
                </div>
            </div>
            <div class="saveconfirmer"><?php _e('Loading...','dzsvg'); ?></div>
            <a href="#" class="button-primary master-save"></a> <img alt="" style="position:fixed; bottom:18px; right:125px; visibility: hidden;" id="save-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save"><?php echo __('Save All Galleries','dzsvg'); ?></a>
            <a href="#" class="button-primary slider-save"><?php echo __('Save Gallery','dzsvg'); ?></a>
        </div>
        <script>
            <?php
            //$jsnewline = '\\' + "\n";
            if (isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir'] == 'on') {
                echo "window.dzs_upload_path = '".site_url('wp-content')."/upload/';
";
                echo "window.dzs_phpfile_path = '".site_url('wp-content')."/upload.php';
";
            } else {
                echo "window.dzs_upload_path = '".$this->thepath."admin/upload/';
";
                echo "window.dzs_phpfile_path = '".$this->thepath."admin/upload.php';
";
            }

            //        print_r($items);

            $aux = str_replace(array("\r","\r\n","\n"),'',$this->sliderstructure);
            if(isset($_GET['currslider']) && isset($items[$_GET['currslider']]) && $items[$_GET['currslider']]['settings']){

                $aux = str_replace(array("theidofthegallery"),$items[$_GET['currslider']]['settings']['id'],$aux);
            }
            echo "var sliderstructure = '".$aux."';
";
            $aux = str_replace(array("\r","\r\n","\n"),'',$this->itemstructure);
            echo "var itemstructure = '".$aux."';
";
            $aux = str_replace(array("\r","\r\n","\n"),'',$this->videoplayerconfig);
            echo "var videoplayerconfig = '".$aux."';
";
            ?>
            jQuery(document).ready(function($) {
                sliders_ready();
                if (jQuery.fn.multiUploader) {
                    jQuery('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainitems;
                for ($i = 0; $i < count($items); $i++) {
//print_r($items[$i]);
                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];
                        $aux2= $items[$i]['settings']['id'];
                        $aux2 = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$aux2);
                        $aux2 = str_replace(array('"'),"'",$aux2);
                        $aux = '{ name: "'.$aux2.'"}';
                    }
                    echo "sliders_addslider(".$aux.");";
                }
                if (count($items) > 0){
                    echo 'sliders_showslider(0);';
                }


                for ($i = 0; $i < count($items); $i++) {
//echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(".$jsi.");";
                        }

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$subvalue);
                                        $subvalue = str_replace(array("'"),'"',$subvalue);

                                        //--- compatibility with older versions
                                        if($sublabel=='feedfrom'){
                                            if($subvalue=='youtube playlist'){
                                                $subvalue='ytplaylist';
                                            }
                                        }
                                        if($sublabel=='youtubefeed_playlist'){
                                            $sublabel='ytplaylist_source';
                                        }
                                        echo 'sliders_change('.$jsi.', "settings", "'.$sublabel.'", '."'".$subvalue."'".');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$subvalue);
                                        $subvalue = str_replace(array("'"),'"',$subvalue);
                                        if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change('.$jsi.', '.$label.', "'.$sublabel.'", '."'".$subvalue."'".');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsvg_settings.is_safebinding == "on") {
                    jQuery('.master-save').remove();
                    if (dzsvg_settings.addslider == "on") {
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php
    }

    function admin_page_vpc() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="dzs-container">
                                <div class="one-half">
                                    <h3>Import Database</h3>
                                    <input name="dzsvg_importdbupload" type="file" size="10"/><br />
                                </div>
                                <div class="one-half  alignright">
                                    <input class="button-secondary" type="submit" name="dzsvg_importdb" value="Import" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>


                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="dzs-container">
                                <div class="one-half">
                                    <h3>Import Slider</h3>
                                    <input name="importsliderupload" type="file" size="10"/><br />
                                </div>
                                <div class="one-half  alignright">
                                    <input class="button-secondary" type="submit" name="dzsvg_importslider" value="Import" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>

                        <div class="dzs-container">
                            <div class="one-half">
                                <h3>Export Database</h3>
                            </div>
                            <div class="one-half  alignright">
                                <form action="" method="POST"><input class="button-secondary" type="submit" name="dzsvg_exportdb" value="Export"/></form>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('Video Gallery Admin','dzsvg'); ?> <img alt="" style="visibility: visible;" id="main-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/></h2>
            <noscript><?php _e('You need javascript for this.','dzsvg'); ?></noscript>
            <div class="top-buttons">
                <a href="<?php echo $this->thepath; ?>readme/index.html" class="button-secondary action"><?php _e('Documentation','dzsvg'); ?></a>
                <a href="<?php echo $this->thepath; ?>deploy/designer/index.php" target="_blank" class="button-secondary action"><?php _e('Go to Designer Center','dzsvg'); ?></a>

            </div>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('ID','dzsvg'); ?></th>
                    <th class="column-edit">Edit</th>
                    <th class="column-edit">Embed</th>
                    <th class="column-edit">Export</th>
                    <?php
                    if ($this->mainoptions['is_safebinding'] != 'on') {
                        ?>
                        <th class="column-edit">Duplicate</th>
                        <?php
                    }
                    ?>
                    <th class="column-edit">Delete</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $url_add = '';
            $items = $this->mainvpconfigs;
            //echo count($items);
            //print_r($items);

            $aux = remove_query_arg('deleteslider',dzs_curr_url());
            $params = array('currslider' => count($items));
            $url_add = esc_url(add_query_arg($params,$aux));
            ?>
            <a class="button-secondary add-slider" href="<?php echo $url_add; ?>"><?php _e('Add Slider','dzsvg'); ?></a>
            <form class="master-settings only-settings-con mode_vpconfigs">
            </form>
            <div class="saveconfirmer"><?php _e('Loading...','dzsvg'); ?></div>
            <a href="#" class="button-primary master-save-vpc"></a> <img alt="" style="position:fixed; bottom:18px; right:125px; visibility: hidden;" id="save-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save-vpc"><?php _e('Save All Configs','dzsvg'); ?></a>
            <a href="#" class="button-secondary slider-save-vpc"><?php _e('Save Config','dzsvg'); ?></a>
        </div>
        <script>
            <?php
            //$jsnewline = '\\' + "\n";
            if (isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir'] == 'on') {
                echo "window.dzs_upload_path = '".site_url('wp-content')."/upload/';
";
                echo "window.dzs_phpfile_path = '".site_url('wp-content')."/upload.php';
";
            } else {
                echo "window.dzs_upload_path = '".$this->thepath."admin/upload/';
";
                echo "window.dzs_phpfile_path = '".$this->thepath."admin/upload.php';
";
            }
            $aux = str_replace(array("\r","\r\n","\n"),'',$this->sliderstructure);
            echo "var sliderstructure = '".$aux."';
";
            $aux = str_replace(array("\r","\r\n","\n"),'',$this->itemstructure);
            echo "var itemstructure = '".$aux."';
";
            $aux = str_replace(array("\r","\r\n","\n"),'',$this->videoplayerconfig);
            echo "var videoplayerconfig = '".$aux."';
";
            ?>
            jQuery(document).ready(function($) {
                sliders_ready();
                if (jQuery.fn.multiUploader) {
                    jQuery('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainvpconfigs;
                for ($i = 0; $i < count($items); $i++) {
//print_r($items[$i]);
                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];
                        $aux2 = $items[$i]['settings']['id'];

                        $aux2 = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$aux2);
                        $aux2 = str_replace(array("'"),'"',$aux2);
                        $aux = '{ name: \''.$aux2.'\'}';
                    }
                    echo "sliders_addslider(".$aux.");";
                }
                if (count($items) > 0)
                    echo 'sliders_showslider(0);';
                for ($i = 0; $i < count($items); $i++) {
//echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(".$jsi.");";
                        }

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$subvalue);
                                        $subvalue = str_replace(array("'"),'"',$subvalue);
                                        echo 'sliders_change('.$jsi.', "settings", "'.$sublabel.'", '."'".$subvalue."'".');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r","\r\n","\n",'\\',"\\"),'',$subvalue);
                                        $subvalue = str_replace(array("'"),'"',$subvalue);
                                        if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change('.$jsi.', '.$label.', "'.$sublabel.'", '."'".$subvalue."'".');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsvg_settings.is_safebinding == "on") {
                    jQuery('.master-save-vpc').remove();
                    if (dzsvg_settings.addslider == "on") {
                        //console.log(dzsvg_settings.addslider)
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php
    }

    function post_options() {
        //// POST OPTIONS ///

        if (isset($_POST['dzsvg_exportdb'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbitemsname.='-'.$currDb;
                $this->mainitems = get_option($this->dbitemsname);
            }
            //===setting up the db END

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="'."dzsvg_backup.txt".'"');
            echo serialize($this->mainitems);
            die();
        }
        if (isset($_POST['dzsvg_dismiss_limit_notice']) && $_POST['dzsvg_dismiss_limit_notice']=='dismiss') {
            $this->mainoptions['settings_limit_notice_dismissed'] = 'on';

//            print_r($this->mainoptions);

            update_option($this->dboptionsname, $this->mainoptions);
        }

        if (isset($_POST['dzsvg_exportslider'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbitemsname.='-'.$currDb;
                $this->mainitems = get_option($this->dbitemsname);
            }
            //===setting up the db END
            //print_r($currDb);

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="'."dzsvg-slider-".$_POST['slidername'].".txt".'"');
            //print_r($_POST);
            echo serialize($this->mainitems[$_POST['slidernr']]);
            die();
        }


        if (isset($_POST['dzsvg_importdb'])) {
            $file_data = file_get_contents($_FILES['dzsvg_importdbupload']['tmp_name']);
            $this->mainitems = unserialize($file_data);
            update_option($this->dbitemsname,$this->mainitems);
        }

        if (isset($_POST['dzsvg_importslider'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
            $auxslider = unserialize($file_data);
            //dzs_replace_in_matrix('http://localhost/wpmu/eos/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //dzs_replace_in_matrix('http://eos.digitalzoomstudio.net/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //echo 'ceva';
            //print_r($auxslider);
            $this->mainitems = get_option($this->dbitemsname);
            //print_r($this->mainitems);
            $this->mainitems[] = $auxslider;

            update_option($this->dbitemsname,$this->mainitems);
        }

        if (isset($_POST['dzsvg_saveoptions'])) {
            if ($_POST['use_external_uploaddir'] == 'on') {
                copy(dirname(__FILE__).'/admin/upload.php',dirname(dirname(dirname(__FILE__))).'/upload.php');
                $mypath = dirname(dirname(dirname(__FILE__))).'/upload';
                if (is_dir($mypath) === false && file_exists($mypath) === false) {
                    mkdir($mypath,0755);
                }
            }


            //$this->mainoptions['embed_masonry'] = $_POST['embed_masonry'];
            update_option($this->dboptionsname,$this->mainoptions);
        }
    }

    function post_save_mo() {

        $auxarray_defs = array(
            'disable_api_caching' => 'off',
            'disable_fontawesome' => 'off',
            'tinymce_enable_preview_shortcodes' => 'off',
            'force_file_get_contents' => 'off',
            'debug_mode' => 'off',
            'settings_trigger_resize' => 'off',
            'replace_wpvideo' => 'off',
            'usewordpressuploader' => 'on',
            'dzsvp_enable_visitorupload' => 'off',
        );
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);

        $auxarray = array_merge($auxarray_defs,$auxarray);

        if (isset($auxarray['use_external_uploaddir']) && $auxarray['use_external_uploaddir'] == 'on') {

            $path_uploadfile = dirname(dirname(dirname(__FILE__))).'/upload.php';
            if (file_exists($path_uploadfile) === false) {
                copy(dirname(__FILE__).'/admin/upload.php',$path_uploadfile);
            }
            $path_uploaddir = dirname(dirname(dirname(__FILE__))).'/upload';
            if (is_dir($path_uploaddir) === false) {
                mkdir($path_uploaddir,0777);
            }
        }

        $auxarray = array_merge($this->mainoptions, $auxarray);
        print_r($auxarray);;

        update_option($this->dboptionsname,$auxarray);
        die();
    }

    function post_save_options_dc() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);
        print_r($auxarray);


        update_option($this->dbdcname,$auxarray);
        die();
    }
    function post_save_options_dc_aurora() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);
        print_r($auxarray);


        update_option($this->dbname_dc_aurora,$auxarray);
        die();
    }

    function post_save() {
        //---this is the main save function which saves gallery
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);


        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbitemsname.='-'.$this->currDb;
        }

        //echo $this->dbitemsname;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbitemsname);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-',$label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-',$label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
//        echo $this->dbitemsname; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);
        update_option($this->dbitemsname,$mainarray);
        echo 'success';
        die();
    }

    function post_get_db_gals() {

        if (isset($_POST['postdata'])) {
            $this->currDb = $_POST['postdata'];
        }



        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbitemsname.='-'.$this->currDb;
        }


        $mainarray = get_option($this->dbitemsname);

        $i = 0;
        foreach ($mainarray as $gal) {
            if ($i > 0) {
                echo ';';
            }

            echo $gal['settings']['id'];

            $i++;
        }


        //echo 'success';
        die();
    }

    function post_save_vpc() {
        //---this is the main save function which saves item
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'],$auxarray);


        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbvpconfigsname.='-'.$this->currDb;
        }
        //echo $this->dbitemsname;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbvpconfigsname);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-',$label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-',$label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
        //echo $this->dbitemsname; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);
        update_option($this->dbvpconfigsname,$mainarray);
        echo 'success';
        die();
    }

    function post_importytplaylist() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        //echo $aux;
        $yf_maxi = 100;
        $i = 0;
        $its = array();

        $str_apikey = '';

        if ($this->mainoptions['youtube_api_key'] != '') {
            $str_apikey = '&key='.$this->mainoptions['youtube_api_key'];
        }

        $target_file = $this->httpprotocol."://gdata.youtube.com/feeds/api/playlists/".$pd."?alt=json&start-index=1&max-results=40".$str_apikey;
        $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);
        //print_r($idar);
        if ($idar == false) {
            echo 'error: '.'check the id';
        } else {
            foreach ($idar->feed->entry as $ytitem) {
                $cache = $ytitem;
                $aux = array();
                $auxtitle = '';
                $auxcontent = '';
                //print_r($cache);
                //print_r(get_object_vars($cache->title));
                foreach ($cache->title as $hmm) {
                    $auxtitle = $hmm;
                    break;
                }
                foreach ($cache->content as $hmm) {
                    $auxcontent = $hmm;
                    break;
                }
                //print_r($aux2);
                //print_r(parse_str($cache->title));
                parse_str($ytitem->link[0]->href,$aux);
                //print_r($aux);

                $its[$i]['source'] = $aux[$this->httpprotocol.'://www_youtube_com/watch?v'];
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";
                $its[$i]['title'] = $auxtitle;
                $its[$i]['menuDescription'] = $auxcontent;
                $its[$i]['description'] = $auxcontent;

                //print_r($ytitem);
                $aux2 = get_object_vars($ytitem->title);
                $aux = ($aux2['$t']);
                $lb = array("\r\n","\n","\r","&","-","`",'???',"'",'-');
                $aux = str_replace($lb,' ',$aux);

                /*
                  $aux = $ytitem->description;
                  $lb   = array("\r\n", "\n", "\r", "&" ,"-", "`", '???', "'", '-');
                  $aux = str_replace($lb, ' ', $aux);
                  $its['settings']['description'] = $aux;
                 */
                $i++;
                if ($i > $yf_maxi)
                    break;
            }
        }

        if (count($its) == 0) {
            echo 'error: '.'<a href="'.$target_file.'">this</a> is what the feed returned '.$ida;
            die();
        }
        for ($i = 0; $i < count($its); $i++) {

        }
        $sits = json_encode($its);
        echo $sits;



        die();
    }

    function post_importytuser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        //echo 'ceva';


        $sw = false;
        //print_r($idar);
        //print_r($idar);
        //print_r(count($idar->data->items));
        $i = 0;
        $yf_maxi = 100;

        //echo $ida;



        $target_file = $this->httpprotocol."://gdata.youtube.com/feeds/api/users/".$pd."/uploads?v=2&alt=jsonc";
        $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);

        if ($ida == 'yt:quotatoo_many_recent_calls') {
            echo 'error: too many recent calls - YouTube rejected the call';
            $sw = true;
        }
        //print_r($idar);

        if ($idar == false) {
            echo 'error: '.'check the id ';
            print_r($ida);
            die();
        } else {

            foreach ($idar->data->items as $ytitem) {
                //print_r($ytitem);
                $its[$i]['source'] = $ytitem->id;
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";

                $aux = $ytitem->title;
                $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                $aux = str_replace($lb,' ',$aux);
                $its[$i]['title'] = $aux;

                $aux = $ytitem->description;
                $lb = array("\r\n","\n","\r","&",'???');
                $aux = str_replace($lb,' ',$aux);
                $lb = array('"');
                $aux = str_replace($lb,'&quot;',$aux);
                $lb = array("'");
                $aux = str_replace($lb,'&#39;',$aux);
                $its[$i]['description'] = $aux;

                $i++;
                if ($i > $yf_maxi + 1)
                    break;
            }
        }
        if (count($its) == 0) {
            echo 'error: '.'this is what the feed returned '.$ida;
            die();
        }
        $sits = json_encode($its);
        echo $sits;



        die();
    }

    function ajax_import_galleries() {


        if ($this->mainitems == '') {
            $this->mainitems = array();
        }


        $mainitems_default_ser = file_get_contents(dirname(__FILE__).'/sampledata/sample_items.txt');
        $aux = unserialize($mainitems_default_ser);

//        print_r($aux);
        foreach($aux as $lab => $val){
//            print_r($val);

            $seekid = $val['settings']['id'];


            $sw = false;
            foreach($this->mainitems as $lab2 => $val2){

                if($seekid === $val2['settings']['id']){

                    $sw = true;
                    break;
                }

            }

            if($sw){
                unset($aux[$lab]);
            }
        }
//        print_r($aux);
        $this->mainitems = array_merge($this->mainitems, $aux);
        update_option($this->dbitemsname,$this->mainitems);


        echo 'success - '.__('galleries imported for sample data use');
        die();
    }

    function ajax_get_vimeothumb() {
        $id = $_POST['postdata'];



        if ( $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {


            if (!class_exists('VimeoAPIException')) {
                require_once(dirname(__FILE__) . '/vimeoapi/vimeo.php');
            }


            $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
            $consumer_key = $this->mainoptions['vimeo_api_client_id'];
            $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
            $token = $this->mainoptions['vimeo_api_access_token'];

            // Do an authentication call
            $vimeo = new Vimeo($consumer_key, $consumer_secret);
            $vimeo->setToken($token); //,$token_secret





            $vimeo_response = $vimeo->request('/videos/' . $id . '/pictures');

            if ($vimeo_response['status'] != 200) {
//                        throw new Exception($channel_videos['body']['message']);
                echo 'error - vimeo error';

                print_r($vimeo_response);
            }

            $ida = '';
            if (isset($vimeo_response['body']['data'])) {
                $ida = $vimeo_response['body']['data'];
            }


//            print_r($ida);

            if($ida && $ida[0]){

                $vimeo_quality_ind = 2;

                if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                    $vimeo_quality_ind = 3;
                }

                if($this->mainoptions['vimeo_thumb_quality']=='high'){

                    $vimeo_quality_ind = 4;
                }

                echo $ida[0]['sizes'][$vimeo_quality_ind]['link'];

            }

        }




        die();
    }

    function post_importvimeouser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        $target_file = "http://vimeo.com/api/v2/".$pd."/videos.json";
        $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);
        $i = 0;
        if ($idar == false) {
            echo 'error: '.'check the id ';
            print_r($ida);
            die();
        } else {
            foreach ($idar as $item) {
                $its[$i]['source'] = $item->id;
                $its[$i]['thethumb'] = $item->thumbnail_small;


                $its[$i]['type'] = "vimeo";

                $aux = $item->title;
                $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                $aux = str_replace($lb,' ',$aux);
                $its[$i]['title'] = $aux;

                $aux = $item->description;
                $lb = array("\r\n","\n","\r","&",'???');
                $aux = str_replace($lb,' ',$aux);
                $lb = array('"');
                $aux = str_replace($lb,'&quot;',$aux);
                $lb = array("'");
                $aux = str_replace($lb,'&#39;',$aux);
                $its[$i]['description'] = $aux;
                $i++;
            }
        }
        if (count($its) == 0) {
            echo 'error: '.'this is what the feed returned '.$ida;
            die();
        }

        $sits = json_encode($its);
        echo $sits;


        die();
    }

    function filter_attachment_fields_to_edit($form_fields,$post) {


        $vpconfigsstr = '';
        $the_id = $post->ID;
        $post_type = get_post_mime_type($the_id);
        //print_r($this->mainvpconfigs);

        if (strpos($post_type,"video") === false) {
            return $form_fields;
        }


        foreach ($this->mainvpconfigs as $vpconfig) {
            //print_r($vpconfig);
            $vpconfigsstr .='<option value="'.$vpconfig['settings']['id'].'">'.$vpconfig['settings']['id'].'</option>';
        }

        $html_sel = '<select class="styleme" id="attachments-'.$post->ID.'-video-player-config" name="attachments['.$post->ID.'][video-player-config]">';
        $html_sel.=$vpconfigsstr;
        $html_sel .='</select>';
        $form_fields['video-player-config'] = array(
            'label' => 'Video Player Config',
            'input' => 'html',
            'html' => $html_sel,
            'helps' => 'choose a configuration for the player / edit in Video Gallery > Player Configs',
        );

        $form_fields['video-player-height'] = array(
            'label' => 'Force Height',
            'input' => 'html',
            'html' => '<input type="text" id="attachments-'.$post->ID.'-video-player-height" name="attachments['.$post->ID.'][video-player-height]"/>',
            'helps' => 'force a height',
        );





        return $form_fields;
    }

    function show_generator_export_slider() {
        ?>Please note that this feature uses the last saved data. Unsaved changes will not be exported.
        <form action="<?php echo site_url().'/wp-admin/options-general.php?page=dzsvg_menu'; ?>" method="POST">
            <input type="hidden" class="hidden" name="slidernr" value="<?php echo $_GET['slidernr']; ?>"/>
            <input type="hidden" class="hidden" name="slidername" value="<?php echo $_GET['slidername']; ?>"/>
            <input type="hidden" class="hidden" name="currdb" value="<?php echo $_GET['currdb']; ?>"/>
            <input class="button-secondary" type="submit" name="dzsvg_exportslider" value="Export"/>
        </form>
        <?php
    }

}

//add_filter( 'script_loader_attrs', 'my_function' );
//
//function my_function( $attrs ) {
//    $attrs = array('async' => 'async', 'charset' => 'utf8'); // whatever attributes you want
//
//   // alternatively, eliminate type='text/javascript' by emptying $attrs:
//   // $attrs = '';
//
//   return $attrs;
//}
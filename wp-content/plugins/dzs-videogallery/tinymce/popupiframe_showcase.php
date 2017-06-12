<?php




function dzsvg_shortcode_showcase_builder()
{

    global $dzsvg;

    $url_admin = get_admin_url();
//<script src="<?php echo site_url(); "></script>

    $categories = get_terms( 'dzsvideo_category', 'orderby=count&hide_empty=0' );


//    print_r($categories);

    
    $cats_checkboxes = '';

    if(count($categories)>0){
        foreach($categories as $cat){
//            print_r($cat);
            $cats_checkboxes .='<input type="checkbox" name="cat[]" id="cat'.$cat->term_id.'" value="'.$cat->term_id.'"> <label for="cat'.$cat->term_id.'">'.$cat->name.'</label><br><br> ';
        }
    }

    ?>
<div class="sc-con sc-con-for-showcase-builder">
    <div class="sc-menu">


        <div class="main-type-container">


            <div class="setting  mode-any">
                <h3><?php echo __("Type"); ?></h3>
                <?php


                $lab = "type";


                $arr_opts = array(
                    'video_items',
                    'youtube',
                    'vimeo',
                    'featured',
                    'scroller',
                    'scrollmenu',
                );


                echo DZSHelpers::generate_select($lab, array(
                    'options'=>$arr_opts,
                    'class'=>'dzs-style-me opener-listbuttons',
                    'seekval'=>'',
                ));

                ?>
                <ul class="dzs-style-me-feeder">
                    <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>tinymce/img/type1.png"/><span class="option-label"><?php echo __("Video Items"); ?></span></span></li>
                    <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>tinymce/img/type2.png"/><span class="option-label"><?php echo __("YouTube Feed"); ?></span></span></li>
                    <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>tinymce/img/type3.png"/><span class="option-label"><?php echo __("Vimeo Feed"); ?></span></span></li>
                </ul>
            </div>



<!--            <div class="setting type-any ">-->
<!--                <h3>--><?php //echo __("Type"); ?><!--</h3>-->
<!--                --><?php
//
//
//                $lab = "type";
//
//
//                $arr_opts = array(
//                    array(
//                        'lab'=>__('Latest Videos'),
//                        'val'=>'latest',
//                    ),
//                    array(
//                        'lab'=>__('Most Viewed'),
//                        'val'=>'mostviewed',
//                    ),
//                    array(
//                        'lab'=>__('Most Liked'),
//                        'val'=>'mostliked',
//                    ),
//                    array(
//                        'lab'=>__("Playlist"),
//                        'val'=>'mostliked',
//                    ),
//                );
//
//
//                echo DZSHelpers::generate_select($lab, array(
//                    'options'=>$arr_opts,
//                    'class'=>'dzs-style-me skin-beige',
//                ));
//
//                ?>
<!--            </div>-->
            <?php if($cats_checkboxes) { ?>
                <div class="setting type-video_items ">
                    <h3><?php echo __("Category"); ?></h3>
                    <?php echo $cats_checkboxes; ?>
                </div>
            <?php } ?>



            <div class="setting type-youtube ">
                <h3><?php echo __("Link"); ?></h3>
                <input class="regular-text" name="youtube_link" value=""/>
                <div class="sidenote"><?php printf(__('ie. %1$s - for a user channel feed').'<br>','<strong>https://www.youtube.com/user/digitalzoomstudio</strong>');
                    printf(__('ie. %1$s - for a playlist feed').'<br>','<strong>https://www.youtube.com/playlist?list=PLBsCKuJJu1pbD4ONNTHgNsVebK4ughuch</strong>');
                    printf(__('ie. %1$s - for a search feed').'<br>','<strong>https://www.youtube.com/results?search_query=cat+funny</strong>'); ?></div>
            </div>

            <div class="setting type-youtube ">
                <h3><?php echo __("Max. Videos"); ?></h3>
                <input class="regular-text" name="max_videos" value=""/>
            </div>




            <div class="setting type-vimeo ">
                <h3><?php echo __("Link"); ?></h3>
                <input class="regular-text" name="vimeo_link" value=""/>
                <div class="sidenote"><?php printf(__('ie. %1$s - for a user channel feed').'<br>','<strong>https://vimeo.com/user5137664</strong>');
                    printf(__('ie. %1$s - for a channel feed').'<br>','<strong>https://vimeo.com/channels/636900</strong>');
                    printf(__('ie. %1$s - for a album feed').'<br>','<strong>https://vimeo.com/album/2633720</strong>'); ?></div>
            </div>


            <div class="setting  type-video_items">
                <h3><?php echo __("Order By"); ?></h3>
                <?php


                $lab = "order_by";


                $arr_opts = array(
                    array(
                        'value'=>'date',
                        'label'=>__("Date"),
                    ),
                    array(
                        'value'=>'views',
                        'label'=>__("Views"),
                    ),
                    array(
                        'value'=>'similar',
                        'label'=>__("Similar"),
                    ),
                );


                echo DZSHelpers::generate_select($lab, array(
                    'options'=>$arr_opts,
                    'class'=>'dzs-style-me skin-beige',
                    'seekval'=>'',
                ));
                ?>
            </div>

            <div class="setting  type-video_items">
                <h3><?php echo __("Order"); ?></h3>
                <?php


                $lab = "order";


                $arr_opts = array(
                    array(
                        'value'=>'desc',
                        'label'=>__("Descending"),
                    ),
                    array(
                        'value'=>'asc',
                        'label'=>__("Ascending"),
                    ),
                );


                echo DZSHelpers::generate_select($lab, array(
                    'options'=>$arr_opts,
                    'class'=>'dzs-style-me skin-beige',
                    'seekval'=>'',
                ));
                ?>
            </div>



            <!-- end type-container-->
        </div>
        <div class="setting  mode-any">
            <h3><?php echo __("Mode"); ?></h3>
            <?php


            $lab = "mode";


            $arr_opts = array(
                'ullist',
                'list',
                'list-2',
                'featured',
                'scroller',
                'scrollmenu',
                'zfolio',
                'gallery_view',
            );


            echo DZSHelpers::generate_select($lab, array(
                'options'=>$arr_opts,
                'class'=>'dzs-style-me opener-listbuttons',
                'seekval'=>'',
            ));

            ?>
            <ul class="dzs-style-me-feeder">
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_ullist.svg"/><span class="option-label">UL LIST</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_list.svg"/><span class="option-label">LIST</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_list-2.svg"/><span class="option-label">LIST 2</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_featured.svg"/><span class="option-label">FEATURED</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_scroller.svg"/><span class="option-label">SCROLLER</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/scrollmenu.svg"/><span class="option-label">SCROLL MENU</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_zfolio.svg"/><span class="option-label">ZFOLIO</span></span></li>
                <li ><span class="option-con"><img src="<?php echo $dzsvg->thepath; ?>assets/svg/style_zfolio.svg"/><span class="option-label">GALLERY VIEW</span></span></li>
            </ul>
        </div>

        <div class="setting  mode-scrollmenu">
            <h4><?php echo __("Scroll Menu Height");?></h4>
            <input class="regular-text" name="mode_scrollmenu_height" value="300"/>


        </div>



        <div class="setting  mode-zfolio">
            <h3><?php echo __("Skin"); ?></h3>
            <?php


            $lab = "mode_zfolio_skin";


            $arr_opts = array(
                array(
                    'value'=>'skin-forwall',
                    'label'=>__("Skin Forwall"),
                ),
                array(
                    'value'=>'skin-alba',
                    'label'=>__("Skin Alba"),
                ),
            );


            echo DZSHelpers::generate_select($lab, array(
                'options'=>$arr_opts,
                'class'=>'dzs-style-me skin-beige',
                'seekval'=>'',
            ));
            ?>
        </div>

        <div class="setting  mode-zfolio">
            <h3><?php echo __("Gap Size"); ?></h3>
            <?php


            $lab = "mode_zfolio_gap";


            $arr_opts = array(
                array(
                    'value'=>'30px',
                    'label'=>__("30px"),
                ),
                array(
                    'value'=>'1px',
                    'label'=>__("1px"),
                ),
            );


            echo DZSHelpers::generate_select($lab, array(
                'options'=>$arr_opts,
                'class'=>'dzs-style-me skin-beige',
                'seekval'=>'',
            ));
            ?>
        </div>

        <div class="setting  mode-zfolio">
            <h3><?php echo __("Layout"); ?></h3>
            <?php


            $lab = "mode_zfolio_layout";


            $arr_opts = array(
                array(
                    'value'=>'3columns',
                    'label'=>__("3 Columns"),
                ),
                array(
                    'value'=>'5columns',
                    'label'=>__("5 Columns"),
                ),
            );


            echo DZSHelpers::generate_select($lab, array(
                'options'=>$arr_opts,
                'class'=>'dzs-style-me skin-beige',
                'seekval'=>'',
            ));
            ?>
        </div>

        <div class="setting  mode-zfolio">
            <h3><?php echo __("Enable Special Layout"); ?></h3>
            <?php


            $lab = "mode_zfolio_enable_special_layout";


            ?><div class="dzscheckbox skin-nova"><?php
            echo DZSHelpers::generate_input_checkbox($lab,array('id' => $lab, 'val' => 'on'));
            ?>
                <label for="<?php echo $lab; ?>"></label>
            </div>
        </div>


            <br>


        <link href='https://fonts.googleapis.com/css?family=Open+Sans:700' rel='stylesheet' type='text/css'>
        <style id="dzstabs_accordio_styling"></style>
        <div id="dzstabs_accordio" class="dzs-tabs auto-init skin-melbourne tab-menu-content-con---no-padding" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '10000'    
,refresh_tab_height: '2000'
,design_tabswidth: 'fullwidth'
,toggle_type: 'accordion'}">

            <div class="dzs-tab-tobe">
                <div class="tab-menu "><?php echo __("Linking Settings"); ?></div>
                <div class="tab-content">

                    <div class="sidenote" style="font-size:14px;"><?php echo __('Choose what clicking on the video item does','dzsvg'); ?></div>

                    <div class="linking_type-con">
                        <div class="setting  linking_type-all">
                            <h3><?php echo __("Link Type"); ?></h3>
                            <?php


                            $lab = "linking_type";


                            $arr_opts = array(
                                array(
                                    'value'=>'default',
                                    'label'=>__("Default"),
                                ),
                                array(
                                    'value'=>'zoombox',
                                    'label'=>__("Zoombox"),
                                ),
                                array(
                                    'value'=>'direct_link',
                                    'label'=>__("Direct Link"),
                                ),
                                array(
                                    'value'=>'vg_change',
                                    'label'=>__("Change Video Player"),
                                ),
                            );


                            echo DZSHelpers::generate_select($lab, array(
                                'options'=>$arr_opts,
                                'class'=>'dzs-style-me skin-beige',
                                'seekval'=>'',
                            ));
                            ?>
                            <div class="sidenote" style=";"><?php echo __('<strong>Default</strong> - means that the item click action will depend on the mode you chose and choose its default mode.  <br><strong>Zoombox</strong> - open the video in a lightbox. <br><strong>Direct Link</strong> - clicking will get the user to the video page.  <br><strong>Change Video Player</strong> - clicking will change a player current video.  ','dzsvg'); ?></div>
                        </div>



                        <div class="setting  linking_type-vg_change">
                            <h3><?php echo __("ID of Target Gallery");?></h3>
                            <input name="gallery_target" value="default"/>

                            <div class="sidenote" style=";"><?php echo __('','dzsvg'); ?></div>
                        </div>



                    </div>

                    <br>
                    <br>





                </div>
            </div>




            <div class="dzs-tab-tobe">
                <div class="tab-menu "><?php echo __("Description Settings"); ?></div>
                <div class="tab-content">

                    <div class="sidenote" style="font-size:14px;"><?php echo __('Use these settings to control how many characters get shown from the video content.','dzsvg'); ?></div>

                    <div class="setting  mode-any">
                        <h3><?php echo __("Number of Characters");?></h3>
                        <input name="desc_count" value="default"/>

                        <div class="sidenote" style=";"><?php echo __('Leave this to <strong>default</strong> in order for the number of characters to get best displayed based on the Mode.. ','dzsvg'); ?></div>
                    </div>

                    <br>
                    <br>





                </div>
            </div>

            <div class="dzs-tab-tobe ">
                <div class="tab-menu ">
                    <?php echo __("Pagination Settings"); ?>
                </div>
                <div class="tab-content">
                    <div class="sidenote" style="font-size:14px;"><?php echo __('Useful if you have many videos and you want to separate them somehow.','dzsvg'); ?></div>

                    <!--                <div class="setting  mode-any">-->
                    <!--                    <h3>--><?php //echo __("Select a Pagination Method"); ?><!--</h3>-->
                    <!--                    <select class="styleme" name="dzsvg_settings_separation_mode">-->
                    <!--                        <option>normal</option>-->
                    <!--                        <option>pages</option>-->
                    <!--                        <option>scroll</option>-->
                    <!--                        <option>button</option>-->
                    <!--                    </select>-->
                    <!---->
                    <!--                </div>-->
                    <div class="setting  mode-any">
                        <h3><?php echo __("Select Number of Items per Page");?></h3>
                        <input name="count" value="5"/>


                    </div>
                    <br>
                    <br>
                </div>
            </div>



            <div class="dzs-tab-tobe">
                <div class="tab-menu ">
                    <?php echo __("Sample Data"); ?>
                </div>
                <div class="tab-content">

                    <div class="sidenote" style="font-size:14px;"><?php echo __('Import any of these examples with one click. ','dzsvg'); ?><form class="no-style import-sample-galleries" method="post"><button name="action" value="dzsvg_import_galleries"><?php echo ("Import sample galeries"); ?></button></form></div>

                    <div class="dzs-container">
                        <div class="one-fourth ">
                            <div class="feat-sample-con  import-sample import-sample-1">

                                <img class="feat-sample " src="<?php echo $dzsvg->thepath; ?>img/sample_1.jpg"/>
                                <h4><?php echo __("Sample Wall"); ?></h4>
                            </div>
                        </div>
                        <div class="one-fourth ">
                            <div class="feat-sample-con  import-sample import-sample-2">

                                <img class="feat-sample " src="<?php echo $dzsvg->thepath; ?>img/sample_2.jpg"/>
                                <h4><?php echo __("YouTube Channel"); ?></h4>
                            </div>
                        </div>


                        <div class="one-fourth ">
                            <div class="feat-sample-con  import-sample import-sample-3">

                                <img class="feat-sample " src="<?php echo $dzsvg->thepath; ?>img/sample_3.jpg"/>
                                <h4><?php echo __("Ad Before Video"); ?></h4>
                            </div>
                        </div>
                        <div class="one-fourth ">
                            <div class="feat-sample-con  import-sample import-sample-4">

                                <img class="feat-sample " src="<?php echo $dzsvg->thepath; ?>img/sample_4.jpg"/>
                                <h4><?php echo __("Balne Layout"); ?></h4>
                            </div>
                        </div>
                    </div>



                </div>
            </div>


        </div>
        <div class="clear"></div>
        <br/>
        <br/>
        <button id="insert_tests" class="button-primary insert-tests"><?php echo __("Insert Gallery"); ?></button>
        <div class="shortcode-output"></div>
    </div>
    <div class="feedbacker"><i class="fa fa-circle-o-notch fa-spin"></i> Loading... </div>
</div><?php
}
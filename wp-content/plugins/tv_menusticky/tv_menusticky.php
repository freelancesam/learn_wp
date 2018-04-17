<?php
/*
  Plugin Name: MenuSticky
  Description: A simple MenuSticky plugin.
  Author: Tran Trong Thang
  Version: 1.0
  Plugin URI:
  Author URI:
  Donate link:
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/*
 * Created on : Apr 5, 2018, 11:02:45 PM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */

add_action('admin_menu', function() {
    add_options_page('Menusticky settings', 'Menusticky', 'manage_options', 'menu-sticky-plugin', 'menu_sticky_plugin_page');
});


add_action('admin_init', function() {
    register_setting('menu-sticky-plugin-settings', 'tv_active');
    register_setting('menu-sticky-plugin-settings', 'tv_text');
    register_setting('menu-sticky-plugin-settings', 'tv_button');
});

function menu_sticky_plugin_page() {
    ?>
    <div class="wrap">
        <form action="options.php" method="post">

            <?php
            settings_fields('menu-sticky-plugin-settings');
            do_settings_sections('menu-sticky-plugin-settings');
            ?>
            <table>
                <tr>
                    <th>Active menu</th>
                    <td>
                        <label>
                            <input type="checkbox" name="tv_active" <?php echo esc_attr(get_option('tv_active')) == 'on' ? 'checked="checked"' : ''; ?> />
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>Menu text</th>
                    <td><textarea placeholder="Your text" name="tv_text" rows="20" cols="100"><?php echo esc_attr(get_option('tv_text')); ?></textarea></td>
                </tr>
                <tr>
                    <th>Button header</th>
                    <td><textarea placeholder="Your button" name="tv_button" rows="20" cols="100"><?php echo esc_attr(get_option('tv_button')); ?></textarea></td>
                </tr>
                <tr>
                    <td><?php submit_button(); ?></td>
                </tr>

            </table>

        </form>
    </div>
    <?php
}

add_action('wp_footer', 'my_footer_scripts');

function my_footer_scripts() {
    if (esc_attr(get_option('tv_active')) == 'on') {
        ob_start();
        ?>
        <aside class="buttons">
            <?php echo (get_option('tv_text')); ?>
        </aside>
        <style>
            aside.buttons{
                position:fixed;
                top:30%;
                right:-50px;
                -webkit-transform:translateY(-30%);
                -khtml-transform:translateY(-30%);
                -moz-transform:translateY(-30%);
                -ms-transform:translateY(-30%);
                -o-transform:translateY(-30%);
                transform:translateY(-30%);
                background:transparent;
                width:50px;
                height:100px;
                color:#fff;
                font-size:3.125rem;
                cursor:pointer;
                -webkit-transition:right 0.5s ease-out 1s;
                -khtml-transition:right 0.5s ease-out 1s;
                -moz-transition:right 0.5s ease-out 1s;
                -ms-transition:right 0.5s ease-out 1s;
                -o-transition:right 0.5s ease-out 1s;
                transition:right 0.5s ease-out 1s;
                z-index:9999999999999999;
                cursor:grab
            }
            aside.buttons a{
                color:#b9dc9b
            }
            aside.buttons .oppettider{
                position:absolute;
                top:0;
                left:0px;
                width:300px;
                height:50px;
                float:left;
                background:#222;
                border-top-left-radius:25px;
                border-bottom-left-radius:25px;
                -webkit-transition:left 0.5s ease-in 0.2s;
                -khtml-transition:left 0.5s ease-in 0.2s;
                -moz-transition:left 0.5s ease-in 0.2s;
                -ms-transition:left 0.5s ease-in 0.2s;
                -o-transition:left 0.5s ease-in 0.2s;
                transition:left 0.5s ease-in 0.2s
            }
            aside.buttons .oppettider.book{
                top:70px
            }
            aside.buttons .oppettider.live{
                top:140px
            }
            aside.buttons .oppettider .icon-bowlit{
                font-size:40px;
                padding-left:4px;
                padding-top:4px
            }
            aside.buttons .oppettider .info{
                position:absolute;
                top:50px;
                left:50px;
                background-color:#5a5a5a;
                width:220px;
                height:auto;
                font-size:0.875rem;
                padding:16px;
                -webkit-transform:translateY(-40px);
                -khtml-transform:translateY(-40px);
                -moz-transform:translateY(-40px);
                -ms-transform:translateY(-40px);
                -o-transform:translateY(-40px);
                transform:translateY(-40px);
                opacity:0;
                z-index:-1;
                -webkit-transition:all 0.3s ease-out 0s;
                -khtml-transition:all 0.3s ease-out 0s;
                -moz-transition:all 0.3s ease-out 0s;
                -ms-transition:all 0.3s ease-out 0s;
                -o-transition:all 0.3s ease-out 0s;
                transition:all 0.3s ease-out 0s
            }
            aside.buttons .oppettider .info a,
            aside.buttons .oppettider .info p{
                font-size:0.875rem
            }
            aside.buttons .oppettider .info table{
                width:100%
            }
            aside.buttons .oppettider .info table th,
            aside.buttons .oppettider .info table tr,
            aside.buttons .oppettider .info table td{
                padding:3px 3px;
                background-color:transparent
            }
            aside.buttons .oppettider .info table th:hover,
            aside.buttons .oppettider .info table th.today,
            aside.buttons .oppettider .info table tr:hover,
            aside.buttons .oppettider .info table tr.today,
            aside.buttons .oppettider .info table td:hover,
            aside.buttons .oppettider .info table td.today{
                background-color:#969696
            }
            aside.buttons .oppettider:hover{
                left:-220px;
                -webkit-transition:left 0.5s ease-in 0s;
                -khtml-transition:left 0.5s ease-in 0s;
                -moz-transition:left 0.5s ease-in 0s;
                -ms-transition:left 0.5s ease-in 0s;
                -o-transition:left 0.5s ease-in 0s;
                transition:left 0.5s ease-in 0s
            }
            aside.buttons .oppettider:hover .info{
                left:25px;
                opacity:1;
                -webkit-transform:translateY(0);
                -khtml-transform:translateY(0);
                -moz-transform:translateY(0);
                -ms-transform:translateY(0);
                -o-transform:translateY(0);
                transform:translateY(0);
                -webkit-transition:-webkit-transform 0.6s ease-out 0.4s,opacity 0.6s ease-out 0.4s;
                -moz-transition:-moz-transform 0.6s ease-out 0.4s,opacity 0.6s ease-out 0.4s;
                transition:transform 0.6s ease-out 0.4s,opacity 0.6s ease-out 0.4s
            }
            aside.buttons span{
                /*font-family:"Knockout-HTF48-Featherweight","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;*/
                font-size:1.30rem;
                position:absolute;
                top:50%;
                left:60px;
                -webkit-transform:translateY(-50%);
                -khtml-transform:translateY(-50%);
                -moz-transform:translateY(-50%);
                -ms-transform:translateY(-50%);
                -o-transform:translateY(-50%);
                transform:translateY(-50%)
            }
            aside.buttons i{
                float:left;
                font-size: 2.5rem;
            }
            aside.buttons i:before{
                float:left
            }
            aside.buttons{
                right:0px
            }
            .button_header{
                color: #fff;
                position: fixed;
                right: 10%;
                top: 15px;
                z-index: 99999999;
            }
            .button_header a{
                color: #fff;
            }
        </style>
        <?php
        if (trim(get_option('tv_button')) != '') {
            ?>
            <span class="button_header">
                <?php echo get_option('tv_button'); ?>
            </span>
            <?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }
}
?>
<!--<div class="oppettider book">
    <i class="fa fa-calendar"></i>
    <span>BOKA BOWLING</span>
    <div class="info">
        <p>Boka Online, eller ring!</p><table>

            <tbody><tr>
                    <td>Online:</td> 
                    <td><a href="http://birkabowling.se/boka">Bokningsformulär</a></td>
            <tr
                </tr><tr>
                    <td>Tel:</td> 
                    <td>
                        <a href="tel:08&ndash;30 50 10">08&ndash;30 50 10</a>
                    </td>
            <tr
                </tr></tbody></table>
    </div>
</div>-->
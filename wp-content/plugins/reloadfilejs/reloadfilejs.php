<?php

/*
 * Plugin Name: Remove file js befor load page
 * Author: Trong Thang
 * Description:  Remove file js befor load page
 * Version: 1.0
 * Author URI: 
 * Text Domain: reloadfilejs
 */
/*
  Created on : Jan 6, 2017, 2:32:40 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */

# case-insensitive in_array() wrapper

function reloadfilejs_min_in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

# process defering
if (!is_admin()) {
    add_filter('script_loader_tag', 'removefilejs_js', 10, 3);
}
/*
 * Load bo mot file js da duoc dinh nghia de load
 */
function removefilejs_js($tag, $handle, $src) {
    if (!strpos($tag, 'filejs.js')) {
        return $tag;
    }
}

function reloadfilejs_js($tag, $handle, $src) {
    $ischeck = true;
    $tvtag = '';
    if (!strpos($src, 'reize.com.au/')) {
        $ischeck = false;
        $tvtag = $tag;
    }
    if ($ischeck) {
        global $ignore, $exclude_defer_jquery, $enable_defer_js, $defer_for_pagespeed;
        if (stripos($src, '?v') !== false) {
            $src = stristr($src, '?v', true);
        } # no query strings
# if defer is enable, add jQuery to the ignore list (if required)
        if ($exclude_defer_jquery == '1' && $enable_defer_js == '1' && (stripos($src, '/jquery.js') !== false || stripos($src, '/jquery.min.js') !== false)) {
            if (!reloadfilejs_min_in_arrayi($src, $ignore)) {
                $ignore[] = $src;
            }
        }

# when to defer, order matters
        $defer = 0;
        if ($enable_defer_js == 1) {
            $defer = 1;
        }
        if (reloadfilejs_min_in_arrayi($src, $ignore)) {
            $defer = 0;
        }
        if (reloadfilejs_min_in_arrayi($src, $ignore) && $exclude_defer_jquery == 1) {
            $defer = 0;
        }

# skip the ignore list by default, defer the rest
        if ($defer == 0) {

# remove defer and async tags
            $tag = str_ireplace(array('async="async"', 'defer="defer"', ' async ', ' defer '), '', $tag);

# no defer
            if ($defer_for_pagespeed != 1) {
                return $tag;
            } else {

# defer for pagespeed insights only
                $deferinsights = <<<EOF
<script type="text/javascript">
if(navigator.userAgent.match(/Speed/i)) { 
document.write('<scr'+'ipt type="text/javascript" defer="defer" src="$src"></scr'+'ipt>');
} else { document.write('<scr'+'ipt type="text/javascript" src="$src"></scr'+'ipt>'); }
</script>
EOF;

# return code
                return preg_replace('#<script(.*?)>(.*?)</script>#is', $deferinsights, $tag);
            }

# normal defer enabled
        } else {
            return str_ireplace(' src=', ' defer="defer" src=', $tag);
        }
    } else {
        return $tvtag;
    }
}

/** refresh **/
jQuery.noConflict();

jQuery(document).ready(function($) {
"use strict";

function watchblog() {

$(".masonry_ctn").isotope({
  // options
  itemSelector : '.ajax_post',
  layoutMode : 'masonry'
});

}

setInterval(watchblog, 100);
});
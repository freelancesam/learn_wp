jQuery(document).ready(function ($) {
    "use strict";
    //post filter
    $('#post-formats-select input[type="radio"]').live('change',function () {
        var val = $(this).val();
        var video_option = $('#tn_video_option');
        var audio_option = $('#tn_audio_option');
        var gallery_option = $('#tn_gallery_option');

        video_option.hide();
        audio_option.hide();
        gallery_option.hide();

        if ('gallery' == val) {
            gallery_option.show();
        } else if ('video' == val) {
            video_option.show();
        } else if ('audio' == val) {
            audio_option.show();
        }
    }).filter(':checked').trigger('change');

    //review post
    var score_wrap = $('#tn_review .inside .rwmb-meta-box > div:gt(0)');
    score_wrap.wrapAll('<div class="tn-enabled-review">').hide();
    var tn_review_checkbox = $('#tn_enable_review');

    if (tn_review_checkbox.is(":checked")) {
        score_wrap.show();
    }
    tn_review_checkbox.click(function () {
        score_wrap.toggle();
    });

    function tn_agv_score() {
        var i = 0;
        var tn_cs1 = 0, tn_cs2 = 0, tn_cs3 = 0, tn_cs4 = 0, tn_cs5 = 0, tn_cs6 = 0;

        var tn_cd1 = $('input[name=tn_cd1]').val();
        var tn_cd2 = $('input[name=tn_cd2]').val();
        var tn_cd3 = $('input[name=tn_cd3]').val();
        var tn_cd4 = $('input[name=tn_cd4]').val();
        var tn_cd5 = $('input[name=tn_cd5]').val();
        var tn_cd6 = $('input[name=tn_cd6]').val();
        if (tn_cd1) {
            i += 1;
            tn_cs1 = parseFloat($('input[name=tn_cs1]').val());
        } else {
            tn_cd1 = null;
        }
        if (tn_cd2) {
            i += 1;
            tn_cs2 = parseFloat($('input[name=tn_cs2]').val());
        } else {
            tn_cd2 = null;
        }
        if (tn_cd3) {
            i += 1;
            tn_cs3 = parseFloat($('input[name=tn_cs3]').val());
        } else {
            tn_cd3 = null;
        }
        if (tn_cd4) {
            i += 1;
            tn_cs4 = parseFloat($('input[name=tn_cs4]').val());
        } else {
            tn_cd4 = null;
        }
        if (tn_cd5) {
            i += 1;
            tn_cs5 = parseFloat($('input[name=tn_cs5]').val());
        } else {
            tn_cd5 = null;
        }
        if (tn_cd6) {
            i += 1;
            tn_cs6 = parseFloat($('input[name=tn_cs6]').val());
        } else {
            tn_cd6 = null;
        }
        var tn_as = $("#tn_as");
        var tnTempTotal = (tn_cs1 + tn_cs2 + tn_cs3 + tn_cs4 + tn_cs5 + tn_cs6);
        var tnTotal = Math.round((tnTempTotal / i) * 10) / 10;
        tn_as.val(tnTotal);
        if (isNaN(tnTotal)) {
            tn_as.val('');
        }
    }

    $('.rwmb-input').on('change', tn_agv_score);
    $('#tn_cs1, #tn_cs2, #tn_cs3, #tn_cs4, #tn_cs5, #tn_cs6').on('slidechange', tn_agv_score);
})
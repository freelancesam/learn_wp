var coll_buffer=0;
var func_output='';



function htmlEncode(arg){
    return jQuery('<div/>').text(arg).html();
}

function htmlDecode(value){
    return jQuery('<div/>').html(arg).text();
}

function get_shortcode_attr(arg, argtext){

    var regex_aattr = new RegExp(arg+'="(.*?)"');

    //console.log(regex_aattr, argtext);

    var aux = regex_aattr.exec(argtext);

    if(aux){
        var foutobj = {'full' : aux[0], 'val' : aux[1]};
        return foutobj;
    }



    return false;
}


jQuery(document).ready(function($){



    if(typeof(dzsvg_settings)!='undefined' && dzsvg_settings.startSetup!=''){
        top.dzsvg_startinit = dzsvg_settings.startSetup;
    }

    console.info('startinit is '+top.dzsvg_startinit);

    var coll_buffer=0;
    var fout='';





    // console.warn(top.dzsvg_startinit);
    // ---- some custom code for initing the generator ( previous values )
    if(typeof top.dzsvg_startinit!='undefined' && top.dzsvg_startinit!=''){


        var arr_settings = ['mode','cat', 'desc_count', 'linking_type'];

        $('.dzsvg-admin').append('<div class="misc-initSetup"><h5>Start Setup</h5></h5><p>'+htmlEncode(top.dzsvg_startinit)+'</p></div>');


        var res;
        var lab='';
        for(key in arr_settings){

            lab = arr_settings[key];
            res = get_shortcode_attr(lab, top.dzsvg_startinit);
           // console.info(res, lab, top.dzsp_startinit);
            if(res){
                if(lab=='id'){
                    lab = 'dzsvg_selectid';
                }
                if(lab=='db'){
                    lab = 'dzsvg_selectdb';
                }
                if(lab=='cat'){
                    var res_arr = String(res['val']).split(',');


                    $('*[name="'+lab+'[]"').each(function(){
                        var _t2 = $(this);

                        // console.warn(_t2, _t2.val(), res_arr);
                        for(var ij in res_arr){

                            // console.info(ij);

                            if(_t2.val()==res_arr[ij]){
                                _t2.prop('checked',true);
                                _t2.trigger('change');
                            }
                        }
                        _t2.parent().attr('data-init_categories',res['val']);
                    })


                }else{

                    $('*[name="'+lab+'"]').val(res['val']);
                    $('*[name="'+lab+'"]').trigger('change');
                }
            }
        }
    }



    var _feedbacker = $('.feedbacker');

    _feedbacker.fadeOut("slow");
    setTimeout(reskin_select, 10);
    $('#insert_tests').unbind('click');
    $('#insert_tests').bind('click', click_insert_tests);

    $(document).delegate('.import-sample', 'click', handle_mouse);
    $(document).delegate('form.import-sample-galleries', 'submit', handle_submit);
    $(document).delegate('select[name=mode],select[name=type],select[name=linking_type]', 'change', handle_submit);


    $('select[name=dzsvg_selectdb]').bind('change', change_selectdb);



    $('select[name=mode],select[name=type],select[name=linking_type]').trigger('change');

    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='click'){
            console.info(_t);

            if(_t.hasClass('import-sample')){

                var fout = '';
                if(_t.hasClass('import-sample-1')){

                     fout = '[dzs_videogallery id="sample_wall" db="main" settings_separation_mode="pages" settings_separation_pages_number="6"]';
                }
                if(_t.hasClass('import-sample-2')){

                     fout = '<div style="float:left; width: 66%;"> [videogallery id="sample_youtube_channel"] </div> <div style="float:left; width: 33%; padding-left: 2%; box-sizing: border-box;"> [dzsvg_secondcon id="sample_youtube_channel" skin="oasis" extraclasses=""] </div> <div style="clear:both;"></div> <div> [dzsvg_outernav id="sample_youtube_channel" skin="oasis" extraclasses=""] </div>';
                }
                if(_t.hasClass('import-sample-3')){

                     fout = '[dzs_videogallery id="sample_ad_before_video" db="main"]';
                }
                if(_t.hasClass('import-sample-4')){

                     fout = '[dzs_videogallery id="sample_balne_setup" db="main"][dzsvg_secondcon id="sample_balne_setup" extraclasses="skin-balne" enable_readmore="on" ] [dzsvg_outernav id="sample_balne_setup" skin="balne" extraclasses="" layout="layout-one-third" thumbs_per_page="9"]';
                }
                tinymce_add_content(fout);
                return false;
            }
        }
    }
    function handle_submit(e){
        var _t = $(this);

        if(e.type=='change'){
            // console.info(_t);
            if(_t.attr('name')=='mode'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('mode-scrollmenu mode-list mode-ullist mode-featured mode-scroller mode-list-2');

                _con.addClass('mode-'+_t.val());
            }
            if(_t.attr('name')=='type'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('type-video_items type-youtube type-vimeo');

                _con.addClass('type-'+_t.val());
            }
            if(_t.attr('name')=='linking_type'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('linking_type-default linking_type-zoombox linking_type-direct_link linking_type-vg_change');

                _con.addClass('linking_type-'+_t.val());
            }
        }
        if(e.type=='submit'){
            // console.info(_t);

            if(_t.hasClass('import-sample-galleries')){

                var data = {
                    action: 'dzsvg_import_galleries'
                    ,postdata: _t.serialize()
                };


                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }

                        //console.info(response);
                        show_notice(response);


                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                    }
                });

                return false;
            }
        }
    }



    function show_notice(response){


        if(response.indexOf('error -')==0){
            _feedbacker.addClass('is-error');
            _feedbacker.html(response.substr(7));
            _feedbacker.fadeIn('fast');

            setTimeout(function(){

                _feedbacker.fadeOut('slow');
            },1500)
        }
        if(response.indexOf('success -')==0){
            _feedbacker.removeClass('is-error');
            _feedbacker.html(response.substr(9));
            _feedbacker.fadeIn('fast');

            setTimeout(function(){

                _feedbacker.fadeOut('slow');
            },1500)
        }
    }
});
function change_selectdb(e){
    var _t = jQuery(this);

    //console.info(_t.val());



    jQuery('#save-ajax-loading').css('opacity', '1');
    var mainarray = _t.val();
    var data = {
        action: 'dzsvg_get_db_gals',
        postdata: mainarray
    };
    jQuery('.saveconfirmer').html('Options saved.');
    jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    jQuery.post(ajaxurl, data, function(response) {
        if(window.console !=undefined ){  console.log('Got this from the server: ' + response); }
        jQuery('#save-ajax-loading').css('opacity', '0');

        var aux = '';
        var auxa = response.split(';');
        for(i=0;i<auxa.length;i++){
            aux+='<option>'+auxa[i]+'</option>'
        }
        $('select[name=dzsvg_selectid]').html(aux);
        $('select[name=dzsvg_selectid]').trigger('change');

    });

    return false;

}


function tinymce_add_content(arg){
    //console.log('tinymce_add_content()', arg);
    if(top==window){

        jQuery('.shortcode-output').text(arg);
    }else{


        if(top.dzsvg_widget_shortcode){
            top.dzsvg_widget_shortcode.val(arg);

            top.dzsvg_widget_shortcode = null;

            console.info(top.close_zoombox2);
            if(top.close_zoombox2){
                top.close_zoombox2();
            }
        }else{

            console.info(top.dzsvg_receiver);
            if(typeof(top.dzsvg_receiver)=='function'){
                top.dzsvg_receiver(arg);
            }
        }

    }

}

function click_insert_tests(e){

    //console.info('click_insert_tests');
    //console.log(jQuery('#mainsettings').serialize());
    prepare_fout();
    tinymce_add_content(fout);
    return false;
}

function prepare_fout(){
    var $ = jQuery.noConflict();
    fout='';
    fout+='[dzs_videoshowcase';
    var _c
        ,_c2
        ,lab=''
        ;
    /*
     _c = $('input[name=settings_width]');
     if(_c.val()!=''){
     fout+=' width=' + _c.val() + '';
     }
     _c = $('input[name=settings_height]');
     if(_c.val()!=''){
     fout+=' height=' + _c.val() + '';
     }
     */

        
    lab = 'type';
    _c = $('select[name='+lab+']');
    if(_c.val()!='' && _c.val()!='main'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }


    lab = 'mode';
    _c = $('select[name='+lab+']');
    if(_c.val()!='' && _c.val()!='main'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    lab = 'cat[]';
    _c = $('*[name="'+lab+'"]');


    var str_cat = '';

    _c.each(function(){
        var _t = $(this);

        // console.info(_t);

        if(_t.prop('checked')){

            str_cat+=_t.val()+',';
        }

    });

    if(str_cat){
        fout+=' cat="'+str_cat+'"';
    }

    lab = 'count';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }



    lab = 'desc_count';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    lab = 'youtube_link';
    _c = $('*[name='+lab+']');
    if(_c.val()!='' && _c.val()!='main'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }


    lab = 'vimeo';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    lab = 'max_videos';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'linking_type';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'mode_scrollmenu_height';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'mode_zfolio_skin';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'mode_zfolio_gap';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'mode_zfolio_layout';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }


    lab = 'mode_zfolio_enable_special_layout';
    _c = $('*[name='+lab+']');
    if(_c.prop('checked')){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    // if($('select[name=dzsvg_settings_separation_mode]').val()!='normal'){
    //     _c = $('select[name=dzsvg_settings_separation_mode]');
    //     if(_c.val()!=''){
    //         fout+=' settings_separation_mode="' + _c.val() + '"';
    //     }
    //     _c = $('input[name=dzsvg_settings_separation_pages_number]');
    //     if(_c.val()!=''){
    //         fout+=' settings_separation_pages_number="' + _c.val() + '"';
    //     }
    // }

    fout+=']';
}

function sc_toggle_change(){
    var $ = jQuery.noConflict();
    //var $t = $(this);

    var type = 'toggle';
    var params = '?type=' + type;
    for(i=0;i<$('.sc-toggle').length;i++){
        var $cach = $('.sc-toggle').eq(i);
        var val = $cach.val();
        if($cach.hasClass('color'))
            val = val.substr(1);
        params+='&opt' + (i+1) + '=' + val;
    }
    // console.log(params);
    $('.sc-toggle-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

}
function sc_boxes_change(){
    //var $t = $(this);

    var type = 'box';
    var params = '?type=' + type;
    for(i=0;i<$('.sc-box').length;i++){
        var $cach = $('.sc-box').eq(i);
        var val = $cach.val();
        params+='&opt' + (i+1) + '=' + val;
    }
    //console.log(params);
    $('.sc-box-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

}



function reskin_select(){
    for(i=0;i<jQuery('select').length;i++){
        var _cache = jQuery('select').eq(i);
        //console.log(_cache.parent().attr('class'));

        if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
            continue;
        }
        var sel = (_cache.find(':selected'));
        _cache.wrap('<div class="select-wrapper"></div>')
        _cache.parent().prepend('<span>' + sel.text() + '</span>')
    }
    //jQuery('.select-wrapper select').unbind();
    jQuery('.select-wrapper select').unbind('change',change_select);
    jQuery('.select-wrapper select').bind('change',change_select);
}

function change_select(){
    var selval = (jQuery(this).find(':selected').text());
    jQuery(this).parent().children('span').text(selval);
}
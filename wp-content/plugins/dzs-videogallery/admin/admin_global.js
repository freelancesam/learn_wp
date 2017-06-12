jQuery(document).ready(function($){
    //return;
     // Create the media frame.

    setTimeout(reskin_select, 10);
    $(document).undelegate(".select-wrapper select", "change");
    $(document).delegate(".select-wrapper select", "change",  change_select);


    $(document).on('change','.wpb-input[name="db"]', handle_input);



    function handle_input(e){
        var _t = $(this);


        if(e.type=='change'){
            if(_t.hasClass('wpb-input')){

                var mainarray = _t.val();
                var data = {
                    action: 'dzsvg_get_db_gals',
                    postdata: mainarray
                };
                jQuery.post(ajaxurl, data, function(response) {
                    if(window.console !=undefined ){  console.log('Got this from the server: ' + response); }
                    jQuery('#save-ajax-loading').css('opacity', '0');

                    var aux = '';
                    var auxa = response.split(';');
                    for(i=0;i<auxa.length;i++){
                        aux+='<option>'+auxa[i]+'</option>'
                    }
                    jQuery('.wpb-input[name=id]').html(aux);
                    jQuery('.wpb-input[name=id]').trigger('change');

                });
            }
        }
    }






    $('.dzsvg-wordpress-uploader').unbind('click');
    $('.dzsvg-wordpress-uploader').bind('click', function(e){
        var _t = $(this);
        _targetInput = _t.prev();

        var searched_type = '';

        if(_targetInput.hasClass('upload-type-audio')){
            searched_type = 'audio';
        }
        if(_targetInput.hasClass('upload-type-video')){
            searched_type = 'video';
        }
        if(_targetInput.hasClass('upload-type-image')){
            searched_type = 'image';
        }


        frame = wp.media.frames.dzsp_addimage = wp.media({
            title: "Insert Media",
            library: {
                type: searched_type
            },

            // Customize the submit button.
            button: {
                // Set the text of the button.
                text: "Insert Media",
                close: true
            }
        });

        // When an image is selected, run a callback.
        frame.on( 'select', function() {
            // Grab the selected attachment.
            var attachment = frame.state().get('selection').first();

            //console.log(attachment.attributes.url);
            var arg = attachment.attributes.url;
            _targetInput.val(arg);
            _targetInput.trigger('change');
//            frame.close();
        });

        // Finally, open the modal.
        frame.open();

        e.stopPropagation();
        e.preventDefault();
        return false;
    });



    function change_select(){
        var selval = ($(this).find(':selected').text());
        $(this).parent().children('span').text(selval);
    }
    function reskin_select(){
        for(i=0;i<$('select').length;i++){
            var _cache = $('select').eq(i);
            //console.log(_cache.parent().attr('class'));

            if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
                continue;
            }
            var sel = (_cache.find(':selected'));
            _cache.wrap('<div class="select-wrapper"></div>')
            _cache.parent().prepend('<span>' + sel.text() + '</span>')
        }



    }


    var aux =window.location.href;


    if(aux.indexOf('plugins.php')>-1){



        setTimeout(function(){
            jQuery.get( "http://zoomthe.me/cronjobs/cache/dzsvg_get_version.static.html", function( data ) {

//            console.info(data);
                var newvrs = Number(data);
                if(newvrs > Number(dzsvg_settings.version)){
                    jQuery('.version-number').append('<span class="new-version info-con" style="width: auto;"> <span class="new-version-text">/ new version '+data+'</span><div class="sidenote">Download the new version by going to your CodeCanyon accound and accessing the Downloads tab.</div></div> </span>')

                    if($('#the-list > #dzs-video-gallery').next().hasClass('plugin-update-tr')==false){
                        $('#the-list > #dzs-video-gallery').addClass('update');
                        $('#the-list > #dzs-video-gallery').after('<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">There is a new version of DZS Video Gallery available. <form action="admin.php?page=dzsvg-autoupdater" class="mainsettings" method="post"> &nbsp; <br> <button class="button-primary" name="action" value="dzsvg_update_request">Update</button></form></td></tr>');
                    }
                }
            });
        }, 300);
    }

    if(aux.indexOf('&dzsvg_purchase_remove_binded=on')>-1){

        aux = aux.replace('&dzsvg_purchase_remove_binded=on','');
        var stateObj = { foo: "bar" };
        if(history){

            history.pushState(stateObj, null, aux);
        }
    }

});
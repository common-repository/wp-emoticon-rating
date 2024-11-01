jQuery(function(){
    jQuery('#is-enable-for-post').click( function () {
       if( 'undefined' != typeof jQuery('#is-enable-for-post').attr( 'checked' ) ) {
           jQuery('#emoticon-post-setting').show();
       } else {
           jQuery('#emoticon-post-setting').hide();
       }
    });

    jQuery('#is-enable-for-page').click( function () {
        if( 'undefined' != typeof jQuery('#is-enable-for-page').attr( 'checked' ) ) {
            jQuery('#emoticon-page-setting').show();
        } else {
            jQuery('#emoticon-page-setting').hide();
        }
    });

});
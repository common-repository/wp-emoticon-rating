
jQuery(function($){
	var emo_flag = 'emo_rating_' + $('#emoticon-container').data('post_id');
	$('#' + getCookie( emo_flag ) ).attr('style' , 'background-color: #ffe0b3;');
	
	$('.emo-icon').click(function(e){
		if(getCookie( emo_flag  ).length != 0) {
			$('#' + getCookie( emo_flag ) ).attr('style' , 'background-color: #ffe0b3;');
		} else {
			var emotion = $(this).attr('id');
			jQuery.post(
				    ajaxurl, 
				    {
				        'action': 	'emo_clicked',
				        'emotion':	emotion,
				        'post_id':  $('#emoticon-container').data('post_id'),
				        'security': ajaxnounce,
				        
				    }, 
				    function(response){
				    	setCookie( emo_flag, emotion, 7 );
						$('#' + getCookie( emo_flag ) ).attr('style' , 'background-color: #ffe0b3;');
						$('#' + emotion + ' > span').html( parseInt( $( '#' + emotion + ' > span').html() ) + 1 );
				    }
				);
		}
	});
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
} 
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
} 

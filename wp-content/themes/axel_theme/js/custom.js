jQuery(document).ready(function($) {

/* blog */

$('body').on('click', '.ajax_posts', function(){
	
	btn = $(this);
	str = 'sheet='+$('[name="sheet"]').val();
	
	$.ajax({
		
		type : 'POST',
		url : myajax.url,
		data : str+'&action=getposts',
		success : function( newposts ) {

			page = $('[name="sheet"]').val()*1+1;
			
			$('[name="sheet"]').val(page);

			if(newposts!=''){
				
				$('.materials__content-list').append(newposts);
				
			} else $('.ajax_posts').remove();
		}

	});
		
	return false;
});

})










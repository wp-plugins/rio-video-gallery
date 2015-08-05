	
/*===========================================================
 *Custom Functions
 *=========================================================== */
jQuery(document).ready(function($){
	
	if($('#vpost_order').is(':checked'))
		{
        	//$('#tbl_image_settings').toggle();
			$('#tbl_vpostorder_settings').show();
		}
		else
		{
			$('#tbl_vpostorder_settings').hide();
		}
	
	//toggle postorder asc and desc..
	  $('#vpost_order').click(function(){
		if($(this).is(':checked'))
		{
        	//$('#tbl_image_settings').toggle();
			$('#tbl_vpostorder_settings').show();
		}
		else
		{
			$('#tbl_vpostorder_settings').hide();
		}
		
    });
	
	$( "#sel_shortcode" ).change(function() { //shortcode writing..
	  var value = $(this).val();
	  $('#shortcodeDisplay').val('[videogallery view="'+value+'"]');
	});
	
});
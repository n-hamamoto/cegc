jQuery(function($) {
	$('#inputForm').submit(function(event) {
		event.preventDefault();
		var $form = $(this);
		var $button = $form.find('button');
		$.ajax({
			url: $form.attr('action'),
			    type: $form.attr('method'),
			    data: $form.serialize() + '&delay=1',
			    timeout: 10000,
			    beforeSend: function(xhr, settings){
			    $button.attr('disabled',true);
			},
			    complete: function(xhr, textStatus){
			    $button.attr('disabled',false);
			},
			    success: function(result, textStatus, xhr){
			    $form[0].reset();
			    $('#resultField').html(result);
			},
			    error: function(xhr, textStatus, error){}
		    });
	    });
    });

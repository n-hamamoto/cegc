$(function(){
	$('#userId').change(function(){
		//選択した値を取得
		var userId = $(this).val();
		$('#groupField').html(userId);

		$.ajax({
			url: "group/admin/js-searchGroup.php",
			    type: "POST",
			    data: "userId=" + userId,
			    timeout: 10000,
			    success: function(result, textStatus, xhr){
			    $('#groupField').html(result);
			}

		    });
	    });
    });

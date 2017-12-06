$(function(){
	$('#groupIds').change(function(){
		//選択した値を取得
		var groupId = $(this).val();
		//		$('#groupId').html(groupId);
		//		alert(groupId);
		$.ajax({
			url: "group/modify/js-showGroupMembers.php",
			    type: "POST",
			    data: "groupId=" + groupId,
			    timeout: 10000,
			    success: function(result, textStatus, xhr){
			    $('#memberField').html(result);
			}

		    });

		$('#button').removeAttr("disabled");
	    });
    });


// 折り畳みリンクの実装
// class=opAndClToggle をクリックすると opAndClblockが開く　
// class=opAndClToggle id=xxx_tをクリックすると id=xxx_bが開く　
$(function(){
	$(".opAndClToggle").click(function(){

		var opAndClTarget;
		
		if($(this).attr("id")){
		    opAndClTarget = $("#" + $(this).attr("id").replace(/_t$/, '_b'));
		    
		}else{
		    opAndClTarget = $(this).nextAll(".opAndClblock").eq(0);
		    
		}
		opAndClTarget.slideToggle();
		$(this).children("img").toggleClass("open");
		
    })

// Ajax対応フォーム
// #inputFormの結果を#resultFieldに返す
	$('#inputForm').submit(function(event) {
		event.preventDefault();
		var $form = $(this);
		var $button = $form.find('button');
		$('#resultField').html('Searcing DB Records...');
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
			    //			    $form[0].reset();
			    $('#resultField').html(result);
			},
			    error: function(xhr, textStatus, error){}
		    });
	    });


	$("select#year").change(function() {
		$("#groupIds").children().remove();
		year = $("select#year option:selected").val();
		groupIdSearch();
	    }).change();

    });
//
function groupIdSearch() {
    $.ajax({
	    url: 'search/group/print-group.php',
		type: "POST",
		data: '&year='+year,
		timeout: 10000,
		beforeSend: function(xhr, settings){
		$('input#button').attr('disabled',true);
	    },
		complete: function(xhr, textStatus){
		$('input#button').attr('disabled',false);
	    },
		success: function(result, textStatus, xhr){
		$('#groupIds').append(result);
	    },
		error: function(xhr, textStatus, error){alert(error);}
	});
}

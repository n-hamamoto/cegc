
// 折り畳みリンクの実装
// class=opAndClToggle をクリックすると opAndClblockが開く　
// class=opAndClToggle id=xxx_tをクリックすると id=xxx_bが開く　
$(function(){
	$("#resultField .opAndClToggle").click(function(){

		var opAndClTarget;
		
		if($(this).attr("id")){
		    opAndClTarget = $("#" + $(this).attr("id").replace(/_t$/, '_b'));
		    
		}else{
		    opAndClTarget = $(this).nextAll(".opAndClblock").eq(0);
		    
		}
		opAndClTarget.slideToggle();
		$(this).children("img").toggleClass("open");
		
	    })
	    .prepend("<img src=\"https://mdl2.media.gunma-u.ac.jp/GU/cyberethics-log/img/opandcl_arrow.png\" width=\"14\" height=\"14\">");

    });

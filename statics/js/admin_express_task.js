// JavaScript Document
;(function (app, $) {
    app.admin_express_task = {
            init: function () {
                $('.online-triangle').click(function(e) {
                	var div = ($(".express-user-list").hasClass("in"));
                	if (div) {
            			$(".on-tran").addClass("triangle1");
                		$(".on-tran").removeClass("triangle");
                		$(".on-tran").removeClass("triangle2");
                	} else {
                		$(".on-tran").addClass("triangle2");
                		$(".on-tran").removeClass("triangle");
                		$(".on-tran").removeClass("triangle1");
                	}
    			});
                $('.leave-trangle').click(function(e) {
                	var div = ($(".express-user-list-leave").hasClass("in"));
                	if (div) {
                		$(".leaveline").addClass("triangle1");
                		$(".leaveline").removeClass("triangle");
                		$(".leaveline").removeClass("triangle2");
                	} else {
                		$(".leaveline").addClass("triangle2");
                		$(".leaveline").removeClass("triangle");
                		$(".leaveline").removeClass("triangle1");
                	}
    			});
//              app.quickpay_info.activity_type_change();
            }
      };
    
})(ecjia.admin, jQuery);
 
// end
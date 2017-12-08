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
              app.admin_express_task.search_express_user();
            },
    
            search_express_user: function () {
                /* 配送员列表搜索 */
                $("form[name='express_searchForm'] .express-search-btn").on('click', function (e) {
                    e.preventDefault();
                    var url = $("form[name='express_searchForm']").attr('action');
                    var keyword = $("input[name='keywords']").val();
                    if (keyword != '') {
                    	url += '&keywords=' + keyword;
                    }
                    ecjia.pjax(url);
                });
		    },
      };
    
})(ecjia.admin, jQuery);
 
// end
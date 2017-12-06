// JavaScript Document
;(function (app, $) {
    app.express_list = {
        init: function () {
        	
            //筛选功能
            $('.screen-btn').on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action') + '&work_type=' + $("#select-work option:selected").val();
                ecjia.pjax(url);
            })
            
            /* 列表搜索传参 */
            $("form[name='searchForm'] .search_express").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var keyword = $("input[name='keyword']").val();
                if (keyword != '') {
                	url += '&keyword=' + keyword;
                }
                ecjia.pjax(url);
            });
        },
    }

    app.express_info = {
            init: function () {
                app.express_info.submit_form();
            },

    	    submit_form: function (formobj) {
    	        var $form = $("form[name='theForm']");
    	        var option = {
    	            rules: {
    	            	name: {
    	                    required: true
    	                },
    	                mobile: {
    	                    required: true
    	                }
    	            },
    	            messages: {
    	            	name: {
    	                	required: "请输入配送员名称"
    	                },
    	                mobile: {
    	                    required: "请输入手机号码"
    	                }
    	            },
    	            submitHandler: function () {
    	                $form.ajaxSubmit({
    	                    dataType: "json",
    	                    success: function (data) {
    	                        ecjia.admin.showmessage(data);
    	                    }
    	                });
    	            }
    	        }
    	        var options = $.extend(ecjia.admin.defaultOptions.validate, option);
    	        $form.validate(options);
    	    }
      };
    
})(ecjia.admin, jQuery);
 
// end
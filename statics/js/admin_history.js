// JavaScript Document
;(function (app, $) {
    app.history_list = {
        init: function () {
        	 $(".date").datepicker({
                 format: "yyyy-mm-dd",
                 container : '.main_content',
             });
        	 
            //筛选功能
            $("form[name='searchForm'] .search_history").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var start_date = $("input[name='start_date']").val();
                var end_date   = $("input[name='end_date']").val();
                var work_type  = $("#select-work option:selected").val();
                var keyword    = $("input[name='keyword']").val();
                if (start_date > end_date && (start_date != '' && end_date != '')) {
                    var data = {
                        message: "请选择正确的时间范围进行筛选",
                        state: "error",
                    };
                    ecjia.admin.showmessage(data);
                    return false;
                }
                if (start_date != '') url += '&start_date=' + start_date;
                if (end_date != '') url += '&end_date=' + end_date;
                if (work_type != '') url += '&work_type=' + work_type;
                if (keyword != '') url += '&keyword=' + keyword;
                ecjia.pjax(url);
            });
            app.history_list.detail();
        },
        
        detail :function(){
            $("a[data-toggle='modal']").on('click', function (e) {
            	e.preventDefault();
                var $this = $(this);
                var express_id = $this.attr('express-id');
                var url = $this.attr('express-url');
                $.post(url, {'express_id': express_id}, function (data) {
                	app.history_list.ajax_event_data(data);
                }, 'json');
			})
        },
        
        ajax_event_data :function(data){
        	var express_order ='<span>配送单号：<font class="ecjiafc-red">'+ data.content.express_sn +'</font></span><span>配送状态：<font class="ecjiafc-red"> 已完成   </font></span><span>取货距离：<font class="ecjiafc-red">'+ data.content.distance +'</font>米</span><span>运费：<font class="ecjiafc-red">¥'+ data.content.commision +'</font>元</span>';
            $(".express_order").html(express_order);
        	
        	var pickup_info ='<ul><li><h3>取货信息</h3></li><li>商家名称：<span>'+ data.content.merchants_name +'</span></li><li>商家电话：<span>'+ data.content.contact_mobile +'</span></li><li>下单时间：<span>'+ data.content.add_time +'</span></li><li>取货地址：<span>'+ data.content.all_address+'<br>'+ data.content.address +'</span></li></ul>';
            $(".pickup_info").html(pickup_info);
              	
          	var delivery_info ='<ul><li><h3>送货信息</h3></li><li>用户名称：<span>'+ data.content.user_name +'</span></li><li>用户电话：<span>'+ data.content.mobile_phone +'</span></li><li>期望送达时间：<span>'+ data.content.expect_shipping_time +'</span></li><li>送货地址：<span>'+ data.content.express_all_address+'<br>'+ data.content.eoaddress +'</span></li></ul>';
          	$(".delivery_info").html(delivery_info);
          	
        	var shipping_info ='<ul><li><h3>配送信息</h3></li><li>配送员名称：<span>'+ data.content.express_user +'</span></li><li>配送员电话：<span>'+ data.content.express_mobile +'</span></li><li>任务类型：<span>'+ data.content.from +'</span></li><li>完成时间：<span>'+ data.content.signed_time +'</span></li></ul>';
        	$(".shipping_info").html(shipping_info);
        	
        	$('.goodslist').html('');
        	for (var i = 0; i < data.list.length; i++) {
				var goods = '<div class="goods-info"><div class="info-left" ><img src="'+data.list[i].image+'" width="50" height="50" /></div><div class="info-right"><span>'+ data.list[i].goods_name +'</span><span class="goods_number">数量：X'+ + data.list[i].goods_number +'</span><p>¥'+ data.list[i].goods_price +'元 </p></div></div>'
				$('.goodslist').append(goods);
			};
			
        	if(data.content.postscript) {
        		$("#postscript").html(data.content.postscript);
        	} else {
        		$("#postscript").html('暂无订单信息');
        	}
        	
        },  
    };
    
})(ecjia.admin, jQuery);
 
// end
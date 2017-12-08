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
            
            //列表快速审核触发
            $("a[data-toggle='modal']").on('click', function (e) {
                var $this = $(this);
                var lng = $this.attr('exlng');
                var lat = $this.attr('exlat');
                var name  =$this.attr('exname');
                var mobile=$this.attr('exmobile');
                
             	//腾讯地图加载
             	var map, markersArray = [];
             	var latLng = new qq.maps.LatLng(lat, lng);
             	var map = new qq.maps.Map(document.getElementById("allmap"),{
             	    center: latLng,
             	    zoom: 18
             	});
             	
         		//创建一个Marker(自定义图片)
         	    var marker = new qq.maps.Marker({
         	        position: latLng, 
         	        map: map
         	    });
         	    
         	    //设置Marker自定义图标的属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
                var anchor = new qq.maps.Point(0, 39),
                    size   = new qq.maps.Size(50,50),
                    origin = new qq.maps.Point(0, 0),
                    icon   = new qq.maps.MarkerImage(
                        "content/apps/express/statics/images/ex_user.png",
                        size,
                        origin,
                        anchor
                    );
                marker.setIcon(icon);

                //创建描述框
             	var Label = function(opts) {
                    qq.maps.Overlay.call(this, opts);
               	}
               	//继承Overlay基类
                Label.prototype = new qq.maps.Overlay();
                //定义construct,实现这个接口来初始化自定义的Dom元素
                Label.prototype.construct = function() {
                     this.dom = document.createElement('div');
                     this.dom.style.cssText =
                          'background:url("content/apps/express/statics/images/lable_text.png") no-repeat;width:130px;height:60px;margin-top:-98px;margin-left:-38px;' +
                          'text-align:left;color:white;padding-left:25px;padding-top:8px;';
                     this.dom.innerHTML = name +'<br>'+mobile;
                     //将dom添加到覆盖物层，overlayLayer的顺序为容器 1，此容器中包含Polyline、Polygon、GroundOverlay等
                     this.getPanes().overlayLayer.appendChild(this.dom);

                }
	            var label = new Label({
	                 map: map,
	                 position: latLng
	            });
 			})
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
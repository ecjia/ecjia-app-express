// JavaScript Document
;(function (app, $) {
    app.admin_express_order_list = {
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
                
              app.admin_express_order_list.search_express_user();
              app.admin_express_order_list.order_list_search();
              app.admin_express_order_list.express_order_detail();
              app.admin_express_order_list.order_current_location();
              app.admin_express_order_list.order_reassign_detail();
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
//                    ecjia.pjax(url);
//                    alert(url);
                    $.post(url, {'express_id': 1}, function (data) {
                    	//$('.testaa').hide();
                    	$('.testaa').css("display","none");
                    	$('.testbb').html(data.data);
                    }, 'json');
                });
		    },
            
	        order_list_search: function() {
				$("form[name='searchForm']").on('submit', function(e) {
					e.preventDefault();
					var url = $("form[name='searchForm']").attr('action');
					
					var keywords = $("input[name='keywords']").val(); //关键字
					
					if (keywords == 'undefind') keywords = '';
					
					if (keywords != '') {
						url += '&keywords=' + keywords;
					}
					
					ecjia.pjax(url);
				});
			},
		
		express_order_detail :function(){
            $(".express-order-modal").on('click', function (e) {
            	e.preventDefault();
                var $this = $(this);
                var express_id = $this.attr('express-id');
                var url = $this.attr('express-order-url');
                $.post(url, {'express_id': express_id}, function (data) {
                	$('.order-detail').html(data.data);
                }, 'json');
			})
        },
        
        order_current_location :function(){
        	 $(".express-location").on('click', function (e) {
             	e.preventDefault();
                 var $this = $(this);
                 var express_id = $this.attr('express-id');
                 var url = $this.attr('express-location-url');
                 $.post(url, {'express_id': express_id}, function (data) {
                 	$('.express-location-modal').html(data.data);
                 	app.admin_express_order_list.map();
                 }, 'json');
 			})
        },
        
        order_reassign_detail :function(){
       	 $(".express-reassign-click").on('click', function (e) {
            	e.preventDefault();
                var $this = $(this);
                var express_id = $this.attr('express-id');
                var url = $this.attr('express-reassign-url');
                $.post(url, {'express_id': express_id}, function (data) {
                	$('.express-reassign-modal').html(data.data);
                	app.admin_express_order_list.map();
                }, 'json');
			})
       },
        
        map: function () {
	        var map, 
	        directionsService = new qq.maps.DrivingService({
	            complete : function(response){
	                var start = response.detail.start,
	                    end = response.detail.end;
	                
	                var anchor = new qq.maps.Point(6, 6),
	                    size = new qq.maps.Size(24, 36),
	                    start_icon = new qq.maps.MarkerImage(
	                        'content/apps/express/statics/images/busmarker.png', 
	                        size, 
	                        new qq.maps.Point(0, 0),
	                        anchor
	                    ),
	                    end_icon = new qq.maps.MarkerImage(
	                        'content/apps/express/statics/images/busmarker.png', 
	                        size, 
	                        new qq.maps.Point(24, 0),
	                        anchor
	                        
	                    );
	                start_marker && start_marker.setMap(null); 
	                end_marker && end_marker.setMap(null);
	                clearOverlay(route_lines);
	                
	                start_marker = new qq.maps.Marker({
	                      icon: start_icon,
	                      position: start.latLng,
	                      map: map,
	                      zIndex:1
	                });
	                end_marker = new qq.maps.Marker({
	                      icon: end_icon,
	                      position: end.latLng,
	                      map: map,
	                      zIndex:1
	                });
	               directions_routes = response.detail.routes;
	               var routes_desc=[];
	               //所有可选路线方案
	               for(var i = 0;i < directions_routes.length; i++){
	                    var route = directions_routes[i],
	                        legs = route; 
	                    //调整地图窗口显示所有路线    
	                    map.fitBounds(response.detail.bounds); 
	                    //所有路程信息            
	                    //for(var j = 0 ; j < legs.length; j++){
	                        var steps = legs.steps;
	                        route_steps = steps;
	                        polyline = new qq.maps.Polyline(
	                            {
	                                path: route.path,
	                                strokeColor: '#3893F9',
	                                strokeWeight: 6,
	                                map: map
	                            }
	                        )  
	                        route_lines.push(polyline);
	                         //所有路段信息
	                        for(var k = 0; k < steps.length; k++){
	                            var step = steps[k];
	                            //路段途经地标
	                            directions_placemarks.push(step.placemarks);
	                            //转向
	                            var turning  = step.turning,
	                                img_position;; 
	                            var turning_img = '&nbsp;&nbsp;<span'+
	                                ' style="margin-bottom: -4px;'+
	                                'display:inline-block;background:'+
	                                'url(img/turning.png) no-repeat '+
	                                img_position+';width:19px;height:'+
	                                '19px"></span>&nbsp;' ;
	                            var p_attributes = [
	                                'onclick="renderStep('+k+')"',
	                                'onmouseover=this.style.background="#eee"',
	                                'onmouseout=this.style.background="#fff"',
	                                'style="margin:5px 0px;cursor:pointer"'
	                            ].join(' ');
	                            routes_desc.push('<p '+p_attributes+' ><b>'+(k+1)+
	                            '.</b>'+turning_img+step.instructions);
	                        }
	                    //}
	               }
	               //方案文本描述
	               var routes=document.getElementById('routes');
	               routes.innerHTML = routes_desc.join('<br>');
	            }
	        }),
	        directions_routes,
	        directions_placemarks = [],
	        directions_labels = [],
	        start_marker,
	        end_marker,
	        route_lines = [],
	        step_line,
	        route_steps = [];
	        
	        var ex_lng_cen 			= $('.nearest_exuser_lng').val();
	        var ex_lat_cen 			= $('.nearest_exuser_lat').val();
	        var cen_latLng = new qq.maps.LatLng(ex_lat_cen, ex_lng_cen);
	        
//	    function map_init() {
        map = new qq.maps.Map(document.getElementById("allmap"), {
            // 地图的中心地理坐标。
        	center: cen_latLng,
            zoom: 15
        });
        calcRoute();
//	    }
	    function calcRoute() {
	        var start_name = document.getElementById("start").value.split(",");
	        var end_name = document.getElementById("end").value.split(",");
	        var policy = document.getElementById("policy").value;
	        route_steps = [];
	            
	        directionsService.setLocation("北京");
	        directionsService.setPolicy(qq.maps.DrivingPolicy[policy]);
	        directionsService.search(new qq.maps.LatLng(start_name[0], start_name[1]), 
	            new qq.maps.LatLng(end_name[0], end_name[1]));
	    }
	    //清除地图上的marker
	    function clearOverlay(overlays){
	        var overlay;
	        while(overlay = overlays.pop()){
	            overlay.setMap(null);
	        }
	    }
	    function renderStep(index){   
	        var step = route_steps[index];
	        //clear overlays;
	        step_line && step_line.setMap(null);
	        //draw setp line      
	        step_line = new qq.maps.Polyline(
	            {
	                path: step.path,
	                strokeColor: '#ff0000',
	                strokeWeight: 6,
	                map: map
	            }
	        )
	    }
	    //配送员位置start
	    var ex_name 		= $('.nearest_exuser_name').val();
        var ex_mobile 		= $('.nearest_exuser_mobile').val();
        var ex_lng 			= $('.nearest_exuser_lng').val();
        var ex_lat 			= $('.nearest_exuser_lat').val();
        var ex_user_latLng 	= new qq.maps.LatLng(ex_lat, ex_lng);
 		//创建一个Marker(自定义图片)
 	    var ex_user_marker = new qq.maps.Marker({
 	        position: ex_user_latLng, 
 	        map: map
 	    });
 	    
 	    //设置Marker自定义图标的属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
        var ex_user_anchor = new qq.maps.Point(0, 39),
            size   = new qq.maps.Size(50,50),
            origin = new qq.maps.Point(0, 0),
            icon   = new qq.maps.MarkerImage(
                "content/apps/express/statics/images/ex_user.png",
                size,
                origin,
                ex_user_anchor
            );
        ex_user_marker.setIcon(icon);

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
                  'background:url("content/apps/express/statics/images/lable_text.png") no-repeat;width:130px;height:60px;margin-top:-98px;margin-left:-38px;position:absolute;' +
                  'text-align:left;color:white;padding-left:25px;padding-top:8px;';
             this.dom.innerHTML = ex_name +'<br>'+ex_mobile;
             //将dom添加到覆盖物层，overlayLayer的顺序为容器 1，此容器中包含Polyline、Polygon、GroundOverlay等
             this.getPanes().overlayLayer.appendChild(this.dom);

        }
        
        //绘制和更新自定义的dom元素
        Label.prototype.draw = function() {
            //获取地理经纬度坐标
            var position = this.get('position');
            if (position) {
                //根据经纬度坐标计算相对于地图外部容器左上角的相对像素坐标
                //var pixel = this.getProjection().fromLatLngToContainerPixel(position);
                //根据经纬度坐标计算相对于地图内部容器原点的相对像素坐标
                var pixel = this.getProjection().fromLatLngToDivPixel(position);
                this.dom.style.left = pixel.getX() + 'px';
                this.dom.style.top = pixel.getY() + 'px';
            }
        }

        Label.prototype.destroy = function() {
            //移除dom
            this.dom.parentNode.removeChild(this.dom);
        }
        var label = new Label({
             map: map,
             position: ex_user_latLng
        });
        //配送员位置end
	 },
  }
    app.serach_user_list = {
    		init : function() {
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
                app.serach_user_list.search_user();
    		},
    		
    		search_user: function () {
    		      /* 配送员列表搜索 */
                $("form[name='express_searchForm'] .express-search-btn").on('click', function (e) {
                    e.preventDefault();
                    var url = $("form[name='express_searchForm']").attr('action');
                    var keyword = $("input[name='keywords']").val();
                    if (keyword != '') {
                    	url += '&keywords=' + keyword;
                    }
//                    ecjia.pjax(url);
                    $.post(url, {'express_id': 1}, function (data) {
                    	//$('.testaa').hide();
                    	$('.testaa').css("display","none");
                    	$('.testbb').html(data.data);
                    }, 'json');
                });
            }
    };
    
})(ecjia.admin, jQuery);
 
// end
// JavaScript Document
;(function (app, $) {
    app.express_order_list = {
        init: function () {   
            $("form[name='searchForm'] .search_express_order").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var keyword = $("input[name='keyword']").val();
                if (keyword != '') {
                	url += '&keyword=' + keyword;
                }
                ecjia.pjax(url);
            });
            
            app.express_order_list.location();
        },
        
        location:function(){
            /* 列表查看配送员当前位置 */
            $("a[data-toggle='modal']").on('click', function (e) {
            	
                var $this = $(this);
                var home_url = $this.attr('home_url');
                var ex_user_img    =  home_url + "/content/apps/express/statics/images/ex_user.png";
                var lable_text_img =  home_url + "/content/apps/express/statics/images/lable_text.png";
                var busmarker_img  =  home_url + "/content/apps/express/statics/images/busmarker.png";
                
                //商家
                var sflng    = $this.attr('sflng');
                var sflat 	 = $this.attr('sflat');
                
                //用户
                var userlng  = $this.attr('userlng');
                var userlat  = $this.attr('userlat');
                
                //加载地图路线
    	        var map, 
    	        directionsService = new qq.maps.DrivingService({
    	            complete : function(response){
    	                var start 	= new qq.maps.LatLng(sflat, sflng);
    	                	end     = new qq.maps.LatLng(userlat, userlng);
    	                	
    	                var anchor = new qq.maps.Point(6, 6),
    	                    size = new qq.maps.Size(24, 36),
    	                    start_icon = new qq.maps.MarkerImage(
    	                    	busmarker_img, 
    	                        size, 
    	                        new qq.maps.Point(0, 0),
    	                        anchor
    	                    ),
    	                    end_icon = new qq.maps.MarkerImage(
    	                    	busmarker_img, 
    	                        size, 
    	                        new qq.maps.Point(24, 0),
    	                        anchor
    	                    );
    	                start_marker && start_marker.setMap(null); 
    	                end_marker && end_marker.setMap(null);
    	                clearOverlay(route_lines);
    	                
    	                start_marker = new qq.maps.Marker({
    	                      icon: start_icon,
    	                      position: start,
    	                      map: map,
    	                      zIndex:1
    	                });
    	                
    	                end_marker = new qq.maps.Marker({
    	                      icon: end_icon,
    	                      position: end,
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
    	        
//                var ex_lng_cen 	 = $this.attr('exlng');
//	            var ex_lat_cen    = $this.attr('exlat');
//    	        var cen_latLng = new qq.maps.LatLng(ex_lat_cen, ex_lng_cen);
//    	        
	
	           
	            calcRoute();
	           
	    	    function calcRoute() {
	    	        var policy = 'LEAST_TIME';
	    	        route_steps = [];
	    	        directionsService.setLocation("北京");
	    	        directionsService.setPolicy(qq.maps.DrivingPolicy[policy]);
	    	        directionsService.search(new qq.maps.LatLng(sflat, sflng), 
	    	        new qq.maps.LatLng(userlat, userlng));
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
	            var exmobile = $this.attr('exmobile');
	            var exname   = $this.attr('exname');
	            var exlng 	 = $this.attr('exlng');
	            var exlat    = $this.attr('exlat');
	            var ex_latLng 	= new qq.maps.LatLng(exlat, exlng);
	        	
	            map = new qq.maps.Map(document.getElementById("allmap"), {
	                // 地图的中心地理坐标。
	            	center: ex_latLng,
	                zoom: 18
	            });
	            
	     		//创建一个Marker(自定义图片)
	     	    var ex_user_marker = new qq.maps.Marker({
	     	        position: ex_latLng, 
	     	        map: map
	     	    });
	     	    
	     	    //设置Marker自定义图标的属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
	            var ex_user_anchor = new qq.maps.Point(0, 39),
	                size   = new qq.maps.Size(50,50),
	                origin = new qq.maps.Point(0, 0),
	                icon   = new qq.maps.MarkerImage(
	                	ex_user_img,
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
	                	 'background:url("'+ lable_text_img +'") no-repeat;width:130px;height:60px;margin-top:-98px;margin-left:-38px;position:absolute;z-index:1;' +
	                     'text-align:left;color:white;padding-left:25px;padding-top:8px;';
	                 this.dom.innerHTML = exname +'<br>'+ exmobile;
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
	                 position: ex_latLng
	            });
 			})
        }
    }
    
})(ecjia.merchant, jQuery);
 
// end
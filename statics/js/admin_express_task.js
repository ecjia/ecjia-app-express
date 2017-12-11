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
              app.admin_express_task.map();
              app.admin_express_task.click_order();
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

//		    function map_init() {
	        map = new qq.maps.Map(document.getElementById("allmap"), {
	            // 地图的中心地理坐标。
	            center: new qq.maps.LatLng(39.916527,116.397128)
	        });
	        calcRoute();
//		    }
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
		 },
		 
		 click_order: function () {
			  $(".order-div").on('click', function (e) {
                  e.preventDefault();
                  var $this = $(this);
                 
                  starts = $this.attr('express_start');
                  ends = $this.attr('express_end');
               
                  $("#start").val(starts);
                  $("#end").val(ends);
                  
                  app.admin_express_task.map();
              });
		 },
      };
    
})(ecjia.admin, jQuery);
 
// end
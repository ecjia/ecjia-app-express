// JavaScript Document

;(function(app, $) {
	app.express = {
		info : function() {
			app.express.expressForm();
			app.express.choose_area();
			app.express.selected_area();
			app.express.quick_search();
			app.express.delete_area();
		},
		expressForm : function() {
			$("form[name='expressForm']").on('submit', function(e){
				e.preventDefault();
				$(this).ajaxSubmit({
					dataType:"json",
					success:function(data){
						ecjia.merchant.showmessage(data);
					}
				});
			});
		},
		
		choose_area: function () {
            $('.ms-elem-selectable').off('click').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    val = $this.attr('data-val'),
                    url = $this.parent().attr('data-url'),
                    $next = $('.' + $this.parent().attr('data-next'));
                	$next_attr = $this.parent().attr('data-next');
                /* 如果是县乡级别的，不触发后续操作 */
                if ($this.parent().hasClass('selStreets')) {
                    $this.siblings().removeClass('disabled');
                    if (val != 0) $this.addClass('disabled');
                    return;
                }
                /* 如果是0的选项，则后续参数也设置为0 */
                if (val == 0) {
                    var $tmp = $('<li class="ms-elem-selectable" data-val="0"><span>没有可选择的地区</span></li>');
                    $next.html($tmp);
                    $tmp.trigger('click');
                    return;
                }
                /* 请求参数 */
                $.get(url, {
                    parent: val
                }, function (data) {
                    $this.siblings().removeClass('disabled');
                    $this.addClass('disabled');
                    var html = '';
                    /* 如果有返回参数，则赋值并触发下一级别的选中 */
                    if (data.regions) {
                    	  for (var i = 0; i <= data.regions.length - 1; i++) {
                              html += '<li class="ms-elem-selectable select_hot_city" data-val="' + data.regions[i].region_id + '"><span>' +
                              	data.regions[i].region_name + '</span>';
                              if ($next_attr == 'selCities') {
                                  html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                              }
                              if ($next_attr == 'selDistricts') {
                                  html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                              }
                              if ($next_attr == 'selStreets') {
                                  html += '<span class="edit-list"><a href="javascript:;">添加</a></span>';
                              }
                              html += '</li>';
                          };
                        $next.html(html);
                        app.express.quick_search();
                        app.express.choose_area();
                        app.express.selected_area();
                    } else {
                        var $tmp = $('<li class="ms-elem-selectable" data-val="0"><span>没有可选择的地区</span></li>');
                        $next.html($tmp);
                        $tmp.trigger('click');
                        return;
                    }
                }, 'json');
            });
        },
 
        selected_area: function () {
            $('.ms-elem-selectable .edit-list a').off('click').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var bool = true;
                var $this = $(this),
                    $parent = $this.parents('li.ms-elem-selectable'),
                    val = $parent.attr('data-val'),
                    name = $parent.find('span').eq(0).text(),
                    $tmp = $('<li><input type="hidden" value="' + val + '" name="regions[]" id="regions_' + val + '"/>'+ name +'<span class="delete_area">x</span></li>');
                
                $('.select-region input').each(function (i) {
                    if ($(this).val() == val) {
//                        var data = {
//                            message: '该地区已被选择',
//                            state: "error",
//                        };
//                        ecjia.merchant.showmessage(data);
                        bool = false;
                        return false;
                    }
                });
                if (bool) {
                	$this.hide();
                    $('.select-region').append($tmp);
                    app.express.delete_area();
                }
            });
        },
 
        quick_search: function () {
            var opt = {
                onAfter: function () {
                    $('.ms-group').each(function (index) {
                        $(this).find('.isShow').length ? $(this).css('display', 'block') : $(this).css('display', 'none');
                    });
                    return;
                },
                show: function () {
                    this.style.display = "";
                    $(this).addClass('isShow');
                },
                hide: function () {
                    this.style.display = "none";
                    $(this).removeClass('isShow');
                },
            };
            $('#selCountry').quicksearch($('.selCountry .ms-elem-selectable'), opt);
            $('#selProvinces').quicksearch($('.selProvinces .ms-elem-selectable'), opt);
            $('#selCities').quicksearch($('.selCities .ms-elem-selectable'), opt);
            $('#selDistricts').quicksearch($('.selDistricts .ms-elem-selectable'), opt);
            $('#selStreets').quicksearch($('.selStreets .ms-elem-selectable'), opt);
        },
        
        delete_area: function() {
        	$('.delete_area').off('click').on('click', function() {
        		var $this = $(this);
        		var val = $this.parent('li').find('input').val();
        		$('.ms-elem-selectable').each(function() {
        			if ($(this).attr('data-val') == val) {
        				$(this).find('a').show();
        			}
        		})
        		$this.parent('li').remove();
        	});
        }
        
	}
	
})(ecjia.merchant, jQuery);

// end

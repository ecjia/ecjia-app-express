<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_express_task.init();
</script>

<!-- {/block} -->

<!-- {block name="main_content"} -->

<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<div class="wait-grab-order-detail">
	<div class="modal hide fade" id="myModal1" style="height:650px;"></div>
</div>


<!-- 批量操作和搜索 -->
<div class="row-fluid batch" >
	<ul class="nav nav-pills" style="margin-bottom:5px;">
		<li class="{if $type eq 'wait_grab'}active{/if}"><a class="data-pjax" href='{url path="express/admin/init" args="type=wait_grab"}'>待抢单 <span class="badge badge-info">{if $express_order_count.wait_grab}{$express_order_count.wait_grab}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'wait_pickup'}active{/if}"><a class="data-pjax" href='{url path="express/admin/wait_pickup" args="type=wait_pickup"}'>待取货 <span class="badge badge-info">{if $express_order_count.wait_pickup}{$express_order_count.wait_pickup}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'sending'}active{/if}"><a class="data-pjax" href='{url path="express/admin/wait_pickup" args="type=sending"}'>配送中 <span class="badge badge-info">{if $express_order_count.sending}{$express_order_count.sending}{else}0{/if}</span> </a></li>
		
		<li class="map-change-remark map-exp-order" style="float:right;margin-top:8px;">注：配送单号&nbsp;&nbsp;<span class="mark order">[{$first_express_order.express_sn}]</span>&nbsp;&nbsp;位置</li>
		<li class="map-change-remark map-exp-user" style="float:right;margin-top:8px;">注：配送员&nbsp;&nbsp;<span class="mark user">[{$express_info.name}]</span>&nbsp;&nbsp;位置</li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span12 express-task">
		<div class="row-fluid ditpage-rightbar-new editpage-rightbar">
			<div class="left-bar1">
				<div class="left-bar move-mod">
					<div class="foldable-list move-mod-group">
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle acc-in move-mod-head"><strong>待抢单列表</strong></a>
							</div>
							<div class="accordion-body in collapse" style="height:567px;overflow:auto;">
								<!-- {foreach from=$wait_grab_list.list item=wait_grab} -->
									<div class="accordion-inner order-div" express_id="{$wait_grab.express_id}" express_sn="{$wait_grab.express_sn}" express_start="{$wait_grab.sf_latitude},{$wait_grab.sf_longitude}" express_end="{$wait_grab.latitude},{$wait_grab.longitude}" sf_lng="{$wait_grab.sf_longitude}" sf_lat="{$wait_grab.sf_latitude}" data-url='{url path="express/admin/get_nearest_exuser"}'>
										<div class="control-group control-group-small border-bottom-line">
											<div class="margin-label">配送单号：{$wait_grab.express_sn}</div>
										</div>
										<div class="control-group control-group-small">
											<div class="margin-label"><span class="takeing"></span><span class="margin-icon">{$wait_grab.from_address}</span></div>
										</div>
										<div class="control-group control-group-small border-bottom-line">
											<div class="margin-label"><span class="sending"></span><span class="margin-icon">{$wait_grab.to_address}</span></div>
										</div>
										<div class="control-group control-group-small">
											<div class="margin-label">距离：{if $wait_grab.distance}{$wait_grab.distance}&nbsp;m{/if}</div>
										</div>
										<div class="control-group control-group-small">
											<div class="margin-label">下单时间：{$wait_grab.format_add_time}</div>
										</div>
										<div class="control-group control-group-small">
											<div class="margin-label btn-a">
											  	 <a class="btn btn-gebo express-order-modal" style="background:#058DC7;" data-toggle="modal" href="#myModal1" express-id="{$wait_grab.express_id}" express-order-url='{url path="express/admin/express_order_detail" args="express_id={$wait_grab.express_id}{if $type}&type={$type}{/if}"}'  title="查看详情">查看详情</a>
								    	  	</div>
										</div>
											<input type="hidden" class="nearest_exuser_name" value="{$express_info.name}"/>
											<input type="hidden" class="nearest_exuser_mobile" value="{$express_info.mobile}"/>
											<input type="hidden" class="nearest_exuser_lng" value="{$express_info.longitude}"/>
											<input type="hidden" class="nearest_exuser_lat" value="{$express_info.latitude}"/>
									</div>
								<!-- {foreachelse} -->
									<div class="norecord">暂无任何记录!</div>
								<!-- {/foreach} -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="middle-bar">
				<div class="control-group">
        			<div class="">
        				<div class="span6" id="allmap" style="height:600px;width:100%;"></div>
        			</div>
        		</div>
			</div>
			<div class="right-bar move-mod" style="height:600px;border:1px solid #dcdcdc;border-radius:4px;">
				<div class="foldable-list move-mod-group">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle move-mod-head"><strong>配送员列表</strong></a>
						</div>
						<div class="accordion-body">
							<div class="accordion-inner right-scroll">
								<div class="control-group control-group-small">
									<div class="margin-label">
									     <form id="form-privilege" class="form-horizontal" name="express_searchForm" action="{$search_action}" method="post" >
											 <input name="keywords" class="span9" type="text" placeholder="请输入配送员名称" value="{$smarty.get.keywords}" />
											 <input id="start" class="span9" type="hidden" value="{$start}"/>
											 <input id="end" class="span9" type="hidden" value="{$end}"/>
											 <input id="policy" class="span9" type="hidden" value="LEAST_TIME"/>
											 <input id="routes" class="span9" type="hidden" ></input>
											 <button class="btn btn-gebo express-search-btn" style="background:#058DC7;padding:4px 7px;" type="button">搜索</button>
										 </form>
									</div>
								</div>
								<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
									<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">在线 （{$express_count.online}）<a class="accordion-toggle acc-in move-mod-head online-triangle" data-toggle="collapse" data-target="#online"><b class="triangle on-tran"></b></a></div>
									<div class="online open">
									<div class="express-user-list accordion-body in in_visable collapse" id="online">
										<!-- {foreach from=$express_user_list.list item=list} -->
											{if $list.online_status eq '1'}
												<div class="express-user-info exuser_div"  longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
													<div class="imginfo-div">
			        		                			<div class="express-img">{if $list.avatar}<img src="{$list.avatar}">{else}<img src="{$app_url}/touxiang.png">{/if}</div>
			        		                			<div class="expressinfo">{$list.name}<br>{$list.mobile}</div>
													</div>
													<div class="express-order-div">
														<div class="waitfor-pickup">
															待取货<span class="ecjia-red">{$list.wait_pickup_count}单</span>
														</div>
														<div class="wait-sending">
															待配送<span class="ecjia-red">{$list.sending_count}单</span>
														</div>
													</div>
													<div class="assign-div">
														 <a class="assign"  data-msg="是否确定让  【{$list.name}】  去配送？" data-href='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}"}'  >
							                       			<button class="btn" type="button" style="background:#F6A618;text-shadow:none;"><span style="color:#fff;">指派给他</span></button>  
	               										 </a> 
														<input type="hidden" class="selected-express-id" value="{$first_express_order.express_id}"/>
													</div>
												</div>
											{/if}
										<!-- {foreachelse} -->
											<div class="">暂无任何记录!</div>
										<!-- {/foreach} -->
									</div>
								</div>
								</div>
								<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
									<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">离线 （{$express_count.offline}）<a class="accordion-toggle acc-in  move-mod-head collapsed leave-trangle" data-toggle="collapse" data-target="#leave"><b class="triangle1 leaveline"></b></a></div>
									<div class="leaveline-express">
									<div class="express-user-list-leave accordion-body collapse" id="leave">
										<!-- {foreach from=$express_user_list.list item=list} -->
											{if $list.online_status eq '4'}
												<div class="express-user-info exuser_div" longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
													<div class="imginfo-div">
			        		                			<div class="express-img">{if $list.avatar}<img src="{$list.avatar}">{else}<img src="{$app_url}/touxiang.png">{/if}</div>
			        		                			<div class="expressinfo">{$list.name}<br>{$list.mobile}</div>
													</div>
													<div class="express-order-div">
														<div class="waitfor-pickup">
															待取货<span class="ecjia-red">{$list.wait_pickup_count}单</span>
														</div>
														<div class="wait-sending">
															待配送<span class="ecjia-red">{$list.sending_count}单</span>
														</div>
													</div>
													<div class="assign-div">
														 <a class="assign"  data-msg="你确定让  【{$list.name}】  去配送？" data-href='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}"}'  >
							                       			<button class="btn" type="button" style="background:#F6A618;text-shadow:none;"><span style="color:#fff;">指派给他</span></button>  
	               										 </a> 
													</div>
												</div>
											{/if}
										<!-- {foreachelse} -->
											<div class="">暂无任何记录!</div>
										<!-- {/foreach} -->
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<!-- {/block} -->
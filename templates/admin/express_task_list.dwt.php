<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.admin_express_task.init();
</script>

<script type="text/javascript">
	setInterval(function(){
		window.location.reload();
	}, 300000);
</script>

<style>
.breadCrumb{
	margin:0 0 10px;
}
</style>
<!-- {/block} -->

<!-- {block name="main_content"} -->

<div class="task-heading">
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<div class="wait-grab-order-detail">
	<div class="modal hide fade" id="myModal1" style="height:590px;"></div>
</div>


<!-- 批量操作和搜索 -->
<div class="row-fluid batch">
	<ul class="nav nav-pills " style="margin-bottom:0px;">
		<li class="{if $type eq 'wait_grab'}active{/if}"><a class="data-pjax" href='{url path="express/admin/init" args="type=wait_grab"}'>待抢单 <span class="badge badge-info">{if $express_order_count.wait_grab}{$express_order_count.wait_grab}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'wait_pickup'}active{/if}"><a class="data-pjax" href='{url path="express/admin/wait_pickup" args="type=wait_pickup"}'>待取货 <span class="badge badge-info">{if $express_order_count.wait_pickup}{$express_order_count.wait_pickup}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'sending'}active{/if}"><a class="data-pjax" href='{url path="express/admin/wait_pickup" args="type=sending"}'>配送中 <span class="badge badge-info">{if $express_order_count.sending}{$express_order_count.sending}{else}0{/if}</span> </a></li>
		
		<li class="map-change-remark map-exp-order" style="float:right;margin-top:8px;">注：配送单号&nbsp;&nbsp;<span class="mark order">[{$first_express_order.express_sn}]</span>&nbsp;&nbsp;位置</li>
		<li class="map-change-remark map-exp-user" style="float:right;margin-top:8px;">注：配送员&nbsp;&nbsp;<span class="mark user">[{$express_info.name}]</span>&nbsp;&nbsp;位置</li>
	</ul>
</div>

<div class="row-fluid row-fluid-new">
	<div class="span12 express-task">
		<div class="row-fluid ditpage-rightbar-new editpage-rightbar">
			<div class="left-bar1">
				<div class="left-bar move-mod">
					<div class="foldable-list move-mod-group">
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle acc-in move-mod-head"><strong>待抢单列表</strong></a>
							</div>
							<div class="accordion-body in collapse" style="height:547px;overflow:auto;">
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
										<div class="control-group control-group-s>mall">
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
        				<div class="span6" id="allmap" style="height:580px;width:100%;"></div>
        			</div>
        		</div>
			</div>
			<div class="original-user-list">
				<!-- #BeginLibraryItem "/library/waitgrablist_search_user_list.lbi" --><!-- #EndLibraryItem -->
			</div>
			<div class="new-user-list">
				
			</div>
		</div>	
	</div>
</div>
<!-- {/block} -->
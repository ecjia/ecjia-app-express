<?php defined('IN_ECJIA') or exit('No permission resources.');?> 
<script type="text/javascript">
	ecjia.admin.serachuser_list.init();
</script>
<div class="waitgrablist">
	<div class="right-bar move-mod" style="height:580px;border:1px solid #dcdcdc;border-radius:4px;">
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
						<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">在线 （{$express_count.online}）<a class="accordion-toggle acc-in move-mod-head online-click" data-toggle="collapse" data-target="#waitgrab_online"><b class="triangle on-tri"></b></a></div>
						<div class="online open">
						<div class="express-user-list-on accordion-body in collapse" id="waitgrab_online">
							<!-- {foreach from=$express_user_list.list item=list} -->
								{if $list.online_status eq '1'}
									<div class="express-user-info">
										<div class="reassign_exuser_div" longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
											<div class="imginfo-div">
	        		                			<div class="express-img">{if $list.avatar}<img src="{$list.avatar}">{else}<img src="{$app_url}/touxiang.png">{/if}</div>
	        		                			<div class="expressinfo">{$list.name}<br>{$list.mobile}</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">{if $list.wait_pickup_count}{$list.wait_pickup_count}{else}0{/if}单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">{if $list.sending_count}{$list.sending_count}{else}0{/if}单</span>
												</div>
											</div>
										</div>
										<div class="assign-div">
											 <a class="{if $express_order_count.wait_grab} re-assign {/if} "  data-msg="是否确定让  【{$list.name}】  去配送？" data-href='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}&type={$type}"}'  >
				                       			{if $express_order_count.wait_grab}
				                       				<button class="btn" type="button" style="background:#F6A618;text-shadow:none;"><span style="color:#fff;">指派给他</span></button>  
				                       			{else}
				                       				<button class="btn" type="button" disabled="disabled"><span>指派给他</span></button>  
				                       			{/if}
               								 </a> 
											<input type="hidden" class="selected-express-id" value="{$first_express_order.express_id}"/>
										</div>
									</div>
										{/if}
									<!-- {foreachelse} -->
								<div class="text-position accordion-body in in_visable collapse">暂无任何记录!</div>
							<!-- {/foreach} -->
						</div>
					</div>
					</div>
					<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
						<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">离线 （{$express_count.offline}）<a class="accordion-toggle acc-in  move-mod-head collapsed offline-click" data-toggle="collapse" data-target="#waitgrab_leave"><b class="triangle1 off-tri"></b></a></div>
						<div class="leaveline-express">
						<div class="express-user-list-off accordion-body collapse" id="waitgrab_leave">
							<!-- {foreach from=$express_user_list.list item=list} -->
								{if $list.online_status eq '4'}
									<div class="express-user-info">
										<div class="reassign_exuser_div" longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
											<div class="imginfo-div">
	        		                			<div class="express-img">{if $list.avatar}<img src="{$list.avatar}">{else}<img src="{$app_url}/touxiang.png">{/if}</div>
	        		                			<div class="expressinfo">{$list.name}<br>{$list.mobile}</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">{if $list.wait_pickup_count}{$list.wait_pickup_count}{else}0{/if}单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">{if $list.sending_count}{$list.sending_count}{else}0{/if}单</span>
												</div>
											</div>
										</div>
										
										<div class="assign-div">
											 <a class="{if $express_order_count.wait_grab} re-assign {/if}"  data-msg="你确定让  【{$list.name}】  去配送？" data-href='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}&type={$type}"}'  >
				                       			{if $express_order_count.wait_grab}
				                       				<button class="btn" type="button" style="background:#F6A618;text-shadow:none;"><span style="color:#fff;">指派给他</span></button>  
               									{else}
				                       				<button class="btn" type="button" disabled="disabled"><span>指派给他</span></button>  
				                       			{/if}
               								 </a> 
										</div>
									</div>
										{/if}
									<!-- {foreachelse} -->
								<div class="text-position accordion-body collapse">暂无任何记录!</div>
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
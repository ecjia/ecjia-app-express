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

<!-- 批量操作和搜索 -->
<div class="row-fluid batch" >
	<ul class="nav nav-pills">
		<li class="{if $type eq 'wait_grab'}active{/if}"><a class="data-pjax" href='{url path="express/admin/init" args="{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>待抢单 <span class="badge badge-info">{if $wait_grab_count}{$wait_grab_count}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'on_going'}active{/if}"><a class="data-pjax" href='{url path="quickpay/admin/init" args="type=on_going{if $filter.merchant_name}&merchant_name={$filter.merchant_name}{/if}{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>待取货 <span class="badge badge-info">{if $type_count.on_sale}{$type_count.on_sale}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'self'}active{/if}"><a class="data-pjax" href='{url path="quickpay/admin/init" args="type=self{if $filter.merchant_name}&merchant_name={$filter.merchant_name}{/if}{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>配送中 <span class="badge badge-info">{if $type_count.self}{$type_count.self}{else}0{/if}</span> </a></li>
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
							<div class="accordion-body in collapse" style="height:650px;overflow:auto;">
								<!-- {foreach from=$wait_grab_list item=wait_grab} -->
									<div class="accordion-inner order-div" express_start="{$wait_grab.sf_latitude},{$wait_grab.sf_longitude}" express_end="{$wait_grab.latitude},{$wait_grab.longitude}" sf_lng="{$wait_grab.sf_longitude}" sf_lat="{$wait_grab.sf_latitude}" data-url='{url path="express/admin/get_nearest_exuser"}'>
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
												<a class="data-pjax btn btn-gebo" style="background:#058DC7;" href='{url path="quickpay/admin/init" args="{if $filter.merchant_name}&merchant_name={$filter.merchant_name}{/if}{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>查看详情</a>
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
        				<div class="span6" id="allmap" style="height:685px;width:100%;"></div>
        			</div>
        		</div>
			</div>
			<div class="right-bar move-mod" style="height:685px;border:1px solid #dcdcdc;border-radius:4px;">
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
												<div class="express-user-info exuser_div">
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
													<div class="assigin-div">
														<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
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
									<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">离线 （{$express_count.offline}）<a class="accordion-toggle acc-in move-mod-head leave-trangle" data-val="0" data-toggle="collapse" data-target="#leave"><b class="triangle1 leaveline"></b></a></div>
									<div class="leaveline-express">
									<div class="express-user-list-leave accordion-body collapse" id="leave">
										<!-- {foreach from=$express_user_list.list item=list} -->
											{if $list.online_status eq '4'}
												<div class="express-user-info exuser_div">
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
													<div class="assigin-div">
														<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
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
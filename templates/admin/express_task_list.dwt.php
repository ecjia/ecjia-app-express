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
									<div class="accordion-inner">
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
				<div class="left-bar move-mod">
					<div class="control-group">
        				<div class="" style="overflow:hidden;">
        					<div class="span6" id="allmap"></div>
        				</div>
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
									     <form id="form-privilege" class="form-horizontal" name="theForm" action="{$form_action}" method="post" >
											 <input name="express_user_name" class="span9" type="text" placeholder="请输入配送员名称" value="{$data.start_time}" />
											 <button class="btn btn-gebo" style="background:#058DC7;padding:4px 7px;" type="submit">搜索</button>
										 </form>
									</div>
								</div>
								<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
									<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">在线 （13）<a class="accordion-toggle acc-in move-mod-head online-triangle" data-toggle="collapse" data-target="#online"><b class="triangle on-tran"></b></a></div>
									<div class="online open">
									<div class="express-user-list accordion-body in in_visable collapse" id="online">
										<div class="express-user-info">
											<div class="imginfo-div">
	        		                			<div class="express-img"><img src="http://10.10.10.47/o2o/content/apps/express/statics/images/touxiang.png"></div>
	        		                			<div class="expressinfo">一号配送员<br>15216670568</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">2单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">8单</span>
												</div>
											</div>
											<div class="assigin-div">
												<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
											</div>
										</div>
										<div class="express-user-info">
											<div class="imginfo-div">
	        		                			<div class="express-img"><img src="http://10.10.10.47/o2o/content/apps/express/statics/images/touxiang.png"></div>
	        		                			<div class="expressinfo">一号配送员<br>15216670568</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">2单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">8单</span>
												</div>
											</div>
											<div class="assigin-div">
												<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
											</div>
										</div>
									</div>
								</div>
								</div>
								<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
									<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">离线 （16）<a class="accordion-toggle acc-in move-mod-head leave-trangle" data-val="0" data-toggle="collapse" data-target="#leave"><b class="triangle1 leaveline"></b></a></div>
									<div class="leaveline-express">
									<div class="express-user-list-leave accordion-body collapse" id="leave">
										<div class="express-user-info">
											<div class="imginfo-div">
	        		                			<div class="express-img"><img src="http://10.10.10.47/o2o/content/apps/express/statics/images/touxiang.png"></div>
	        		                			<div class="expressinfo">一号配送员<br>15216670568</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">2单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">8单</span>
												</div>
											</div>
											<div class="assigin-div">
												<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
											</div>
										</div>
										<div class="express-user-info">
											<div class="imginfo-div">
	        		                			<div class="express-img"><img src="http://10.10.10.47/o2o/content/apps/express/statics/images/touxiang.png"></div>
	        		                			<div class="expressinfo">一号配送员<br>15216670568</div>
											</div>
											<div class="express-order-div">
												<div class="waitfor-pickup">
													待取货<span class="ecjia-red">2单</span>
												</div>
												<div class="wait-sending">
													待配送<span class="ecjia-red">8单</span>
												</div>
											</div>
											<div class="assigin-div">
												<a class="ajaxremove btn btn-gebo" style="background:#F6A618;text-shadow:none;" data-toggle="ajaxremove" data-msg="你确定要删除该买单规则吗？" href='{url path="quickpay/admin/remove" args="id={$quickpay.id}"}' title="删除"><span style="color:#fff;">指派给他</span></a>
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
	</div>
</div>
<!-- {/block} -->
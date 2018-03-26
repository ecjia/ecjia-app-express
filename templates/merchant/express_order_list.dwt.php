<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.express_order_list.init();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->

<div class="page-header">
	<h2 class="pull-left">
	<!-- {if $ur_here}{$ur_here}{/if} -->
	<!-- {if $action_link} -->
	<!-- {/if} -->
	</h2>
	<div class="pull-right">
		<a class="btn btn-primary" target="_blank" href="{$action_link.href}" id="sticky_a"><i class="fa fa-plus"></i><i class="fontello-icon-plus"></i> {$action_link.text}</a>
	</div>
	<div class="clearfix">
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel">
			<div class="panel-body panel-body-small">
				<ul class="nav nav-pills pull-left">
					<li class="{if $type eq 'wait_grab'}active{/if}"><a  href='{url path="express/merchant/init" args="type=wait_grab{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>待抢单 <span class="badge badge-info">{if $express_order_count.wait_grab}{$express_order_count.wait_grab}{else}0{/if}</span> </a></li>
					<li class="{if $type eq 'wait_pickup'}active{/if}"><a  href='{url path="express/merchant/init" args="type=wait_pickup{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>待取货 <span class="badge badge-info">{if $express_order_count.wait_pickup}{$express_order_count.wait_pickup}{else}0{/if}</span> </a></li>
					<li class="{if $type eq 'sending'}active{/if}"><a  href='{url path="express/merchant/init" args="type=sending{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>配送中 <span class="badge badge-info">{if $express_order_count.sending}{$express_order_count.sending}{else}0{/if}</span> </a></li>
				</ul>
				
				<form class="form-inline" method="post" action="{$search_action}{if $type}&type={$type}{/if}" name="searchForm">
					<div class="f_r form-group">
						<input type="text" name="keyword" class="form-control" value="{$smarty.get.keyword}" placeholder="请输入名称或手机号"/>
						<a class="btn btn-primary m_l5 search_express_order"><i class="fa fa-search"></i> 搜索</a>
					</div>
				</form>
			</div>
			
			<div id="actionmodal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
	                    <div class="modal-header">
		                    <button data-dismiss="modal" class="close" type="button">×</button>
		                    <h4 class="modal-title">配送员当前位置</h4>
	                    </div>
	                    <div class="modal-body">
							<div id="allmap"></div>
						</div>
                    </div>
                </div>
                <div style="display:none;" id="routes"></div>
           </div>
           
           
           
		   {if $type eq 'wait_grab'}
		   aaaaa
		   {else}
			   <div class="panel-body panel-body-small">
					<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
						<thead>
							<tr>
							  	<th class="w150">配送单号</th>
							    <th class="w200">配送员</th>
							    <th>收货人信息</th>
							    <th class="w150">任务类型</th>
							    <th class="w200">接单时间</th>
							    <th class="w100">配送状态</th>
							</tr>
						</thead>
						<tbody>
							<!-- {foreach from=$express_order_list.list item=express} -->
						    <tr>
						      	<td class="hide-edit-area">
									{$express.express_sn}
						     	  	<div class="edit-list">
									  	<a class="data-pjax" href='{url path="express/merchant/detail" args="user_id={$express.user_id}"}' title="查看详情">查看详情</a>
									  	{if $type eq 'wait_pickup'}&nbsp;|&nbsp;
										  	<a class="express-reassign-click" data-toggle="modal" data-backdrop="static" href="#myModal2" express-id="{$wait_pickup.express_id}" express-reassign-url='{url path="express/admin/express_reasign_detail" args="express_id={$wait_pickup.express_id}&store_id={$wait_pickup.store_id}{if $type}&type={$type}{/if}"}'  title="重新指派">
										  		重新指派
										  	</a>
									  	{/if}
									  	
									  	{if $express.online_status eq '1'}&nbsp;|&nbsp;
									  	<a data-toggle="modal" href="#actionmodal" home_url="{RC_Uri::home_url()}" exmobile="{$express.express_mobile}" exname="{$express.express_user}" exlng="{$express.ex_longitude}" exlat="{$express.ex_latitude}" userlng="{$express.user_longitude}" userlat="{$express.user_latitude}" sflng="{$express.sf_longitude}" sflat="{$express.sf_latitude}" title="当前位置">
									  	当前位置</a>
									  	{/if}
						    	  	</div>
						      	</td>
						      	<td>{$express.express_user}[{$express.express_mobile}]</td>
						      	<td>{$express.consignee}[{$express.consignee_mobile}]<br>地址：{$express.to_address}</td>
						      	<td>{if $express.from eq 'assign'}派单{else}抢单{/if}</td>
						      	<td>{$express.receive_time}</td>
						      	<td class="ecjiafc-red">{if $express.status eq '1'}待取货{else}配送中{/if}</td>
						    </tr>
						    <!-- {foreachelse} -->
							<tr>
								<td class="no-records" colspan="6">{lang key='system::system.no_records'}</td>
							</tr>
						<!-- {/foreach} -->
						</tbody>
					</table>
					<!-- {$express_order_count.page} -->
			   </div>
		   {/if}
		</div>
	</div>
</div>
<!-- {/block} -->
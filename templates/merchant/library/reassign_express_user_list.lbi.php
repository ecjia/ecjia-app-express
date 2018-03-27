<?php defined('IN_ECJIA') or exit('No permission resources.');?> 

<div class="right-bar move-mod">
	<input type="hidden" name="home_url" value="{RC_Uri::home_url()}"/>
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
								 <div class="col-lg-10">
						            <input name="keywords" class="form-control express-search-input" type="text" placeholder="请输入配送员名称" value="{$smarty.get.keywords}" />
						         </div>
						         <button class="btn btn-primary express-search-btn" type="button">搜索</button>
							 </form>
						</div>
					</div>
					
					<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
						<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">在线 （{$express_count.online}）<a class="acc-in move-mod-head online-triangle" data-toggle="collapse" data-target="#online"><b class="triangle on-tran"></b></a></div>
							<div class="online open">
							<div class="express-user-list assign-operate accordion-body in collapse" id="online">
								<!-- {foreach from=$express_user_list.list item=list} -->
									{if $list.online_status eq '1'}
										<div class="express-user-info ex-user-div{$list.user_id}" staff_user_id="{$list.user_id}">
											<div class="exuser_div" longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
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
				                       			<a class="assign btn btn-warning" type="button"  notice="是否确定让  【{$list.name}】  去配送？" assign-url='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}&type={$type}"}'>
				                       				指派给他
				                       			</a>  
											</div>
											<input type="hidden" class="ex-u-id" value=""/>
										</div>
									{/if}
										<!-- {foreachelse} -->
									<div class="text-position">暂无任何记录!</div>
								<!-- {/foreach} -->
							</div>
						</div>
					</div>
					
					
					<div class="control-group control-group-small press-list" style="margin-bottom:0px;">
						<div class="margin-label online-list" style="margin-top:5px;margin-bottom: 5px;">离线 （{$express_count.offline}）<a class="acc-in  move-mod-head collapsed leave-trangle" data-toggle="collapse" data-target="#leave"><b class="triangle1 leaveline"></b></a></div>
						<div class="leaveline-express">
						<div class="express-user-list-leave assign-operate accordion-body collapse" id="leave">
							<!-- {foreach from=$express_user_list.list item=list} -->
								{if $list.online_status eq '4'}
									<div class="express-user-info">
										<div longitude="{$list.longitude}" latitude="{$list.latitude}" name="{$list.name}" mobile="{$list.mobile}">
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
											 <button class="assign btn btn-warning"  type="button" style="background:#F6A618;text-shadow:none;" data-toggle="modal" href="#assignmodel" notice="是否确定让  【{$list.name}】  去配送？" assign-url='{url path="express/admin/assign_express_order" args="staff_id={$list.user_id}&type={$type}"}'>
											 	指派给他
											 </button>  
										</div>
									</div>
										{/if}
									<!-- {foreachelse} -->
								<div class="text-position">暂无任何记录!</div>
							<!-- {/foreach} -->
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- 指派开始 -->
<div class="re-assign-model">
	<div class="modal " style="width:530px;">
		 <div class="modal-body">
			<div class="control-group formSep control-group-small">
				<div class="controls">
					<div class="notice-message"></div>
				</div>
			</div>
			<div class="control-group t_c">
				<button class="btn cancel-btn">取消</button>&nbsp;&nbsp;
				<button class="btn ok-btn">确定</button>
			</div>
		  </div>
	</div>
</div>
<!--指派结束 -->

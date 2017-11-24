<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.express.info();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->
<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link}
		<a class="btn btn-primary data-pjax" href="{$action_link.href}" id="sticky_a" style="float:right;margin-top:-3px;"><i class="fa fa-reply"></i> {$action_link.text}</a>
		{/if}
		</h2>
	</div>
</div>

<div class="row">
  <div class="col-lg-12">
      <section class="panel">
          <div class="panel-body">
              <div class="form">
                  <form class="cmxform form-horizontal tasi-form" name="theForm" method="post" action="{$form_action}">
                  		<div class="form-group">
                          	<label class="control-label col-lg-2">模版名称：</label>
                           	<div class="col-lg-8">
                              	<input class="form-control" type="text" name="template_name" />
                              	<span class="help-block">该名称只在运费模板列表显示，便于管理员查找模板</span>
                          	</div>
                          	<span class="input-must">{lang key='system::system.require_field'}</span>
                       	</div>
					  	<div class="form-group">
							<label class="control-label col-lg-2">地区设置：</label>
							<div class="controls col-lg-8">
								<div class="template-info-item">
									<div class="template-info-head">
										<div class="head-left">配送至</div>
										<div class="head-right">操作</div>
									</div>
									<div class="template-info-content">
										<div class="content-area">
											<ul class="content-area-list"></ul>
											<div class="content-area-handle">
												<a data-toggle="modal" href="#chooseRegion">编辑</a> &nbsp;|&nbsp; <a class="reset_region ecjiafc-red" href="javascript:;">重置</a>
											</div>
										</div>
										<a class="btn btn-primary add_area" data-toggle="modal" href="#chooseRegion">添加地区</a>
									</div>
								</div>
							</div>
							<span class="input-must">{lang key='system::system.require_field'}</span>
					  	</div>
					  	<div class="form-group">
							<label class="control-label col-lg-2">快递方式：</label>
							<div class="controls col-lg-8">
								<div class="template-info-item">
									<div class="template-info-head">
										<div class="head-left">快递方式</div>
										<div class="head-right">操作</div>
									</div>
									<div class="template-info-shipping" data-url='{url path="express/merchant/get_shipping_list"}'></div>
									<div class="template-info-content">
										<a class="btn btn-primary add_shipping" href="javascript:;">添加快递</a>
									</div>
								</div>
							</div>
							<span class="input-must">{lang key='system::system.require_field'}</span>
					  	</div>
					  
                 		<div class="form-group">
							<div class="col-lg-offset-2 col-lg-6">
								<!-- {if $data.template_id} -->
									<input type="submit" value="更新" class="btn btn-info" />
									<input type="hidden" name='template_id' value="{$data.template_id}">
								<!-- {else} -->
									<input type="submit" value="{lang key='system::system.button_submit'}" class="btn btn-info" />
								<!-- {/if} -->
							</div>
						</div>
                  	</form>
              	</div>
          	</div>
      	</section>
  	</div>
</div>

<div class="modal fade" id="chooseRegion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">×</button>
				<h3>选择地区</h3>
			</div>
			<div class="modal-body form-horizontal">
				<ul class="select-region">
				</ul>
				<div class="form-group">
					<div class="ms-container ms-shipping" id="ms-custom-navigation">
						<div class="ms-selectable col-lg-3">
							<div class="search-header">
								<input class="form-control" type="text" placeholder="搜索省份" autocomplete="off" id="selProvinces" />
							</div>
							<ul class="ms-list nav-list-ready selProvinces" data-url="{url path='merchant/region/init' args='target=selCities&type=1'}" data-next="selCities">
								<!-- {foreach from=$provinces item=province key=key} -->
								<li class="ms-elem-selectable" data-val="{$province.region_id}"><span>{$province.region_name|escape:html}</span></li>
								<!-- {foreachelse} -->
								<li class="ms-elem-selectable" data-val="0"><span>没有可选的省份地区……</span></li>
								<!-- {/foreach} -->
							</ul>
						</div>

						<div class="ms-selectable col-lg-3">
							<div class="search-header">
								<input class="form-control" type="text" placeholder="搜索市" autocomplete="off" id="selCities" />
							</div>
							<ul class="ms-list nav-list-ready selCities" data-url="{url path='merchant/region/init' args='target=selDistricts&type=2'}" data-next="selDistricts">
								<li class="ms-elem-selectable" data-val="0"><span>请选择市</span></li>
							</ul>
						</div>
						
						<div class="ms-selectable col-lg-3">
							<div class="search-header">
								<input class="form-control" type="text" placeholder="搜索区/县" autocomplete="off" id="selDistricts" />
							</div>
							<ul class="ms-list nav-list-ready selDistricts" data-url="{url path='merchant/region/init' args='target=selStreets&type=3'}" data-next="selStreets">
								<li class="ms-elem-selectable" data-val="0"><span>{lang key='shipping::shipping_area.choose_city_first'}</span></li>
							</ul>
						</div>
						
						<div class="ms-selectable col-lg-3">
							<div class="search-header">
								<input class="form-control" type="text" placeholder="搜索街道镇" autocomplete="off" id="selStreets" />
							</div>
							<ul class="ms-list nav-list-ready selStreets">
								<li class="ms-elem-selectable" data-val="0"><span>请选择街道/镇</span></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form-group t_c">
					<a class="btn btn-primary close_model" data-dismiss="modal">确定</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addShipping">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">×</button>
				<h3>选择快递</h3>
			</div>
			<div class="modal-body">
				<form class="cmxform form-horizontal tasi-form" name="shippingForm" method="post" action="{$shipping_form_action}">
					<div class="form-group">
						<label class="control-label col-lg-3">快递方式：</label>
						<div class="controls col-lg-6">
							<select name="shipping_id" class="w300 form-control shipping_list" data-url='{url path="express/merchant/get_shipping_info"}'>
								<option value="-1">请选择快递方式...</option>
								<!-- {foreach from=$shipping item=val} -->
								<option value="{$val.shipping_id}">{$val.shipping_name}</option>
								<!-- {/foreach} -->
					        </select>
						</div>
						<span class="input-must">{lang key='system::system.require_field'}</span>
					</div>
					<div id="shipping_info">
					
					</div>
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-6">
							<input type="submit" value="{lang key='system::system.button_submit'}" class="btn btn-primary add-shipping-btn" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- {/block} -->
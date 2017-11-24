<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2>配送管理</h2>
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                	<div class="col-lg-3">
						<div class="setting-group">
					        <span class="setting-group-title">配送管理</span>
					        <ul class="nav nav-list m_t10 change">
						        <li><a class="setting-group-item data-pjax llv-active" href='{url path="express/merchant/shipping_template"}'>运费模板</a></li>
						        <li><a class="setting-group-item data-pjax" href='{url path="express/merchant/express_template"}'>快递单模版</a></li>
						        <li><a class="setting-group-item data-pjax" href='{url path="express/merchant/shipping_record"}'>配送记录</a></li>
					        </ul>
						</div>
					</div>
					
					<div class="col-lg-9">
						<div class="panel-body panel-body-small">
							<h2 class="page-header">
								{if $ur_here}{$ur_here}{/if}
								<div class="pull-right">
									<a class="btn btn-primary data-pjax" href='{RC_Uri::url("express/merchant/add_shipping_template")}'><i class="fa fa-plus"></i> 添加运费模版</a>
								</div>
							</h2>
							
							<section class="panel">
								<div class="template-item">
									<div class="template-head">
										<div class="head-left">运费模板名称1</div>
										<div class="head-right">
											<a>查看详情</a> &nbsp;|&nbsp; <a class="ecjiafc-red">删除</a>
										</div>
									</div>
									<div class="template-content">
										<div class="content-group">
											<div class="content-label">物流快递：</div>
											<div class="content-controls">
												申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通
											</div>
										</div>
										<div class="content-group">
											<div class="content-label">配送区域：</div>
											<div class="content-controls">
												上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市
											</div>
										</div>
									</div>
								</div>
								
								<div class="template-item">
									<div class="template-head">
										<div class="head-left">运费模板名称2</div>
										<div class="head-right">
											<a>查看详情</a> &nbsp;|&nbsp; <a class="ecjiafc-red">删除</a>
										</div>
									</div>
									<div class="template-content">
										<div class="content-group">
											<div class="content-label">物流快递：</div>
											<div class="content-controls">
												申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通
											</div>
										</div>
										<div class="content-group">
											<div class="content-label">配送区域：</div>
											<div class="content-controls">
												上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市
											</div>
										</div>
									</div>
								</div>
								
								<div class="template-item">
									<div class="template-head">
										<div class="head-left">运费模板名称3</div>
										<div class="head-right">
											<a>查看详情</a> &nbsp;|&nbsp; <a class="ecjiafc-red">删除</a>
										</div>
									</div>
									<div class="template-content">
										<div class="content-group">
											<div class="content-label">物流快递：</div>
											<div class="content-controls">
												申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通、申通、中通
											</div>
										</div>
										<div class="content-group">
											<div class="content-label">配送区域：</div>
											<div class="content-controls">
												上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市 | 上海市 | 北京市 | 南京市
											</div>
										</div>
									</div>
								</div>
								
								<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
									<tbody>
										<tr><td class="no-records" colspan="4">{lang key='system::system.no_records'}</td></tr>
									</tbody>
								</table>
							</section>
						</div>
					</div>
                </div>
            </div>
    </div>
</div>

<!-- {/block} -->
<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.merchant_list.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<div class="row-fluid batch" >
	<ul class="nav nav-pills">
		<li class="{if $type eq ''}active{/if}"><a class="data-pjax" href='{url path="express/admin_express/init" args="{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>全部 <span class="badge badge-info">{if $type_count.count}{$type_count.count}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'online'}active{/if}"><a class="data-pjax" href='{url path="express/admin_express/init" args="type=online{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>在线 <span class="badge badge-info">{if $type_count.online}{$type_count.online}{else}0{/if}</span> </a></li>
		<li class="{if $type eq 'offline'}active{/if}"><a class="data-pjax" href='{url path="express/admin_express/init" args="type=offline{if $filter.keyword}&keyword={$filter.keyword}{/if}"}'>离线 <span class="badge badge-info">{if $type_count.offline}{$type_count.offline}{else}0{/if}</span> </a></li>
		
		<form method="post" action="{$search_action}{if $type}&type={$type}{/if}" name="searchForm">
			<div class="choose_list f_r">
				<input type="text" name="keyword" value="{$smarty.get.keyword}" placeholder="请输入商家名称"/> 
				<button class="btn search_merchant" type="button">搜索</button>
			</div>
		</form>
	</ul>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="merchant_box">
			<ul class="merchant_list">
				<li>
					<div class="bd">
						<div class="list-top">
							<img src="{RC_Uri::admin_url('statics/images/test.png')}"><span>屈臣氏品牌店</span>
						</div>
						
						<div class="list-mid">
							<p><font class="ecjiafc-red">50</font><br>待抢单</p>
							<p><font class="ecjiafc-red">50</font><br>待取货</p>
							<p><font class="ecjiafc-red">50</font><br>配送中</p>
						</div>
						
						<div class="list-bot">
							<div><label>营业时间：</label>08:00-17:30</div>
							<div><label>商家电话：</label>021-000-0000</div>
							<div><label>商家地址：</label>上海市普陀区中山北路3553号301室</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- {/block} -->
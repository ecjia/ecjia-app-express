<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">

</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}"  id="sticky_a"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<!-- 批量操作和搜索 -->
<div class="row-fluid batch" >
	<form method="post" action="{$search_action}{if $type}&type={$type}{/if}" name="searchForm">
		<div class="btn-group f_l m_r5">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fontello-icon-cog"></i>批量操作
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a class="button_remove" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{url path=''}" data-msg="您确实要删除选中的订单吗？" data-noSelectMsg="请先选中要删除的订单！" href="javascript:;"><i class="fontello-icon-trash"></i>移除</a></li>
			</ul>
		</div>
		
		<div class="choose_list f_r">
			<input type="text" name="keyword" value="{$smarty.get.keyword}" placeholder="请输入订单号或收货人"/>
			<button class="btn search_express" type="button">搜索</button>
		</div>
	</form>
</div>
	
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped smpl_tbl table-hide-edit">
			<thead>
				<tr>
				    <th class="table_checkbox"><input type="checkbox" name="select_rows" data-toggle="selectall" data-children=".checkbox"/></th>
				    <th class="w150">订单编号</th>
				    <th class="w150">收货人</th>
				    <th class="w150">收货地址</th>
				    <th class="w100">审核状态</th>
				    <th class="w100">催单时间</th>
			  	</tr>
			</thead>
			<!-- {foreach from=$data.list item=express} -->
		    <tr>
				<td>
					<span><input type="checkbox" name="checkboxes[]" class="checkbox" value=""/></span>
				</td>
		      	<td class="hide-edit-area"><div class="edit-list"></div></td>
		      	<td></td>
		      	<td></td>
		      	<td></td>
		      	<td></td>
		    </tr>
		    <!-- {foreachelse} -->
	        <tr><td class="no-records" colspan="8">{lang key='system::system.no_records'}</td></tr>
			<!-- {/foreach} -->
            </tbody>
         </table>
         <!-- {$data.page} -->
	</div>
</div>
<!-- {/block} -->
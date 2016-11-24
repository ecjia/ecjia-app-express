<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
    ecjia.merchant.bonus_type.type_list_init();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
  	</div>
  	<div class="pull-right">
  		{if $action_link}
		<a href="{$action_link.href}" class="btn btn-primary data-pjax">
			<i class="fa fa-plus"></i> {$action_link.text}
		</a>
		{/if}
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-lg-12">
    	<div class="panel">
    		<div class="panel-body panel-body-small">
    			
    		</div>
    		<div class="panel-body panel-body-small">
		        <section class="panel">
		            <table class="table table-striped table-hover table-hide-edit">
		                <thead>
		                    <tr>
		                        <th class="w150">{lang key='express::express.express_sn'}</th>
		                        <th class="w150">{lang key='express::express.delivery_sn'}</th>
		                        <th class="w150">{lang key='express::express.consignee'}</th>
		                        <th class="w150">{lang key='express::express.mobile'}</th>
		                        <th class="w150">{lang key='express::express.address'}</th>
		                        <th class="w150">{lang key='express::express.add_time'}</th>
		                        <th class="w100">{lang key='express::express.from'}</th>
		                        <th class="w100">{lang key='express::express.express_status'}</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    <!-- {foreach from=$type_list.item item=type} -->
		                    <tr>
		                        <td class="hide-edit-area">
		                            <span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/merchant/edit_type_name')}" data-name="type_name" data-pk="{$type.type_id}" data-title="{lang key='bonus::bonus.edit_bonus_type_name'}">{$type.type_name}</span>
		                            <br/>
		                            <div class="edit-list">
		                                <a class="data-pjax" href='{RC_Uri::url("bonus/merchant/bonus_list", "bonus_type={$type.type_id}")}' title="{lang key='bonus::bonus.view_bonus'}">{lang key='bonus::bonus.view_bonus'}</a>&nbsp;|&nbsp;
		                                <a class="data-pjax" href='{RC_Uri::url("bonus/merchant/edit", "type_id={$type.type_id}")}' title="{lang key='system::system.edit'}">{lang key='system::system.edit'}</a> &nbsp;|&nbsp;
		                                <a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='bonus::bonus.remove_bonustype_confirm'}" href='{RC_Uri::url("bonus/merchant/remove","id={$type.type_id}")}' title="{lang key='system::system.remove'}">{lang key='system::system.drop'}</a>
		                                {if $type.send_type neq 2 && $type.send_type neq 4}
		                                &nbsp;|&nbsp;<a class="data-pjax" href='{RC_Uri::url("bonus/merchant/send", "id={$type.type_id}&send_by={$type.send_type}")}' title="{lang key='bonus::bonus.send_bonus'}">{lang key='bonus::bonus.send_bonus'}</a>
		                                {/if}
		                                {if $type.send_type eq 3}
		                                &nbsp;|&nbsp;<a href='{RC_Uri::url("bonus/merchant/gen_excel", "tid={$type.type_id}")}' title="{lang key='bonus::bonus.gen_excel'}">{lang key='bonus::bonus.gen_excel'}</a>
		                                {/if}
		                            </div>
		                        </td>
		                        <td>{$type.send_by}</td>
		                        <td>
		                            <span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/merchant/edit_type_money')}" data-name="type_money" data-pk="{$type.type_id}" data-title="{lang key='bonus::bonus.edit_bonus_money'}">{$type.type_money}</span>
		                        </td>
		                        <td>
		                            <span class="cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('bonus/merchant/edit_min_amount')}" data-name="min_amount" data-pk="{$type.type_id}" title="{lang key='bonus::bonus.edit_order_limit'}">{$type.min_amount}</span>
		                        </td>
		                        <td>{$type.send_count}</td>
		                        <td>{$type.use_count}</td>
		                    </tr>
		                    <!-- {foreachelse} -->
		                    <tr><td class="no-records" colspan="10">{lang key='system::system.no_records'}</td></tr>
		                    <!-- {/foreach} -->
		                </tbody>
		            </table>
		        </section>
		         <!-- {$type_list.page} -->
		      </div>
	     </div>
    </div>
</div>
<!-- {/block} -->

<?php defined('IN_ECJIA') or exit('No permission resources.'); ?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
    ecjia.admin.admin_express_order_list.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
    <h3 class="heading">
        <!-- {if $ur_here}{$ur_here}{/if} -->
        <!-- {if $action_link} -->
        <a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i
                    class="fontello-icon-plus"></i>{$action_link.text}</a>
        <!-- {/if} -->
    </h3>
</div>

<div class="wait-grab-order-detail">
    <div class="modal order-detail hide fade" id="myModal1" style="height:590px;"></div>
</div>

<div class="assign-order-detail">
    <div class="modal express-reassign-modal hide fade" id="myModal2" style="height:590px;"></div>
</div>

<!-- 批量操作和搜索 -->
<div class="row-fluid batch">
    <form method="post" action="{$search_action}{if $type}&type={$type}{/if}" name="searchForm">
<!--         <div class="btn-group f_l m_r5"> -->
<!--             <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> -->
<!--                 <i class="fontello-icon-cog"></i>批量操作<span class="caret"></span> -->
<!--             </a> -->
<!--             <ul class="dropdown-menu"> -->
<!--                 <li><a class="button_remove" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}" data-msg="您确实要删除选中的订单吗？" data-noSelectMsg="请先选中要删除的订单！" href="javascript:;"><i class="fontello-icon-trash"></i>移除</a></li> -->
<!--             </ul> -->
<!--         </div> -->
        <div class="choose_list f_r">
            <input type="text" name="keywords" value="{$smarty.get.keywords}" placeholder="请输入配送单号或收货人关键字"/>
            <button class="btn search_express" type="submit">搜索</button>
        </div>
    </form>
</div>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-striped smpl_tbl table-hide-edit">
            <thead>
            <tr>
                <th class="table_checkbox"><input type="checkbox" name="select_rows" data-toggle="selectall" data-children=".checkbox"/></th>
                <th class="w150">配送单号</th>
                <th class="w150">收货人</th>
                <th class="w150">收货地址</th>
                <th class="w100">审核状态</th>
                <th class="w100">催单时间</th>
            </tr>
            </thead>
            <!-- {foreach from=$result_list.list item=express} -->
            <tr>
                <td>
                    <span><input type="checkbox" name="checkboxes[]" class="checkbox" value="{$express.express_id}"/></span>
                </td>
                <td class="hide-edit-area">
                    {$express.express_sn}
                    <div class="edit-list">
                        <a class="express-order-modal" data-toggle="modal" data-backdrop="static" href="#myModal1"
                           express-id="{$express.express_id}"
                           express-order-url='{url path="express/admin_reminder/order_detail" args="express_id={$express.express_id}&store_id={$express.store_id}"}'
                           title="查看详情">查看详情</a>&nbsp;|&nbsp;
                           
                        <a class="express-reassign-click" data-toggle="modal" data-backdrop="static" href="#myModal2"
                           express-id="{$express.express_id}"
                           express-reassign-url='{url path="express/admin_reminder/express_detail" args="express_id={$express.express_id}&store_id={$express.store_id}"}'
                           title="重新指派">指派订单</a>
                    </div>
                </td>
                <td>
                    {$express.consignee}<br/>
                    {$express.mobile}
                </td>
                <td>{$express.address}</td>
                <td>{$express.status}</td>
                <td>{$express.confirm_time}</td>
            </tr>
            <!-- {foreachelse} -->
            <tr>
                <td class="no-records" colspan="6">{lang key='system::system.no_records'}</td>
            </tr>
            <!-- {/foreach} -->
            </tbody>
        </table>
        <!-- {$data.page} -->
    </div>
</div>
<!-- {/block} -->
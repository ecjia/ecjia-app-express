<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
use Ecjia\System\Notifications\ExpressAssign;

defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 派单提醒
 *
 */
class admin_reminder extends ecjia_admin
{

    public function __construct()
    {
        parent::__construct();

        /* 加载全局 js/css */
        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('smoke');
        RC_Style::enqueue_style('chosen');
        RC_Style::enqueue_style('uniform-aristo');
        RC_Script::enqueue_script('jquery-uniform');
        RC_Script::enqueue_script('jquery-chosen');

		RC_Script::enqueue_script('admin_express_task', RC_App::apps_url('statics/js/admin_express_task.js', __FILE__));
		RC_Script::enqueue_script('admin_express_order_list', RC_App::apps_url('statics/js/admin_express_order_list.js', __FILE__));
		RC_Style::enqueue_style('admin_express_task', RC_App::apps_url('statics/css/admin_express_task.css', __FILE__));
		RC_Script::enqueue_script('qq_map', 'https://map.qq.com/api/js?v=2.exp');
		RC_Script::localize_script('express', 'js_lang', RC_Lang::get('express::express.js_lang'));

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('派单提醒'));
    }

    /**
     * 列表
     */
    public function init()
    {
        $this->admin_priv('express_reminder_manage');

        /* 查询 */
        $db_order_reminder = RC_DB::table('express_order_reminder as e')
            ->leftJoin('express_order as o', RC_DB::raw('o.express_id'), '=', RC_DB::raw('e.express_id'))
            ->leftJoin('users as a', RC_DB::raw('o.user_id'), '=', RC_DB::raw('a.user_id'));

        $keywords = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);

        if (!empty($keywords)) {
            $db_order_reminder->whereRaw('(o.express_sn like "%' . mysql_like_quote($keywords) . '%" or o.consignee like "%' . mysql_like_quote($keywords) . '%")');
        }

        $count = $db_order_reminder->count();
        $page = new ecjia_page($count, 10, 6);

        $result = $db_order_reminder->take(10)->skip($page->start_id - 1)->get();
        $result_list = array('list' => $result, 'page' => $page->show(2), 'desc' => $page->page_desc(), 'keywords' => $keywords);

        if (!empty($result_list['list'])) {
            foreach ($result_list['list'] as $key => $val) {
                $result_list['list'][$key]['status'] = $val['status'] == 1 ? RC_Lang::get('orders::order.processed') : RC_Lang::get('orders::order.untreated');
                $result_list['list'][$key]['confirm_time'] = RC_Time::local_date(ecjia::config('time_format'), $val['confirm_time']);
            }
        }
        $this->assign('result_list', $result_list);
        $this->assign('ur_here', '派单提醒列表');
        $this->assign('form_action', RC_Uri::url('express/admin_reminder/remove&type=batch'));
        $this->assign('search_action', RC_Uri::url('express/admin_reminder/init'));

        $this->display('express_reminder_list.dwt');
    }
    
	/**
	 * 查看订单详情
	 */
	public function order_detail() {
		$this->admin_priv('express_task_manage');
	
		$express_id = intval($_GET['express_id']);
		$type = trim($_GET['type']);
		
		$express_info = RC_DB::table('express_order')->where('express_id', $express_id)->select('store_id', 'order_id', 'order_sn', 'delivery_id', 'delivery_sn', 'user_id', 'mobile', 'consignee', 'express_sn', 'distance', 'shipping_fee', 'commision','express_user','express_mobile','from','signed_time','province as eoprovince','city as eocity','district as eodistrict','street as eostreet','address as eoaddress')->first();
		$store_info = RC_DB::table('store_franchisee')->where('store_id', $express_info['store_id'])->select('merchants_name','contact_mobile','province','city','district','street','address')->first();
		//$users_info = RC_DB::table('users')->where('user_id', $express_info['user_id'])->select('user_name','mobile_phone')->first();
		$order_info = RC_DB::table('order_info')->where('order_id', $express_info['order_id'])->select('add_time','expect_shipping_time','postscript')->first();
		
		//$goods_list = RC_DB::table('order_goods')->where('order_id', $express_info['order_id'])->select('goods_id', 'goods_name' ,'goods_price','goods_number')->get();
		/*配送单对应的发货单商品*/
		$goods_list = RC_DB::table('delivery_goods')->where('delivery_id', $express_info['delivery_id'])->selectRaw('goods_id, goods_name, send_number')->get();
		
		foreach ($goods_list as $key => $val) {
			$goods_list[$key]['image']  	  			= RC_DB::table('goods')->where('goods_id', $val['goods_id'])->pluck('goods_thumb');
			$goods_list[$key]['goods_price']  			= RC_DB::table('order_goods')->where('goods_id', $val['goods_id'])->where('order_id', $express_info['order_id'])->pluck('goods_price');
			$goods_list[$key]['formated_goods_price']	= price_format($goods_list[$key]['goods_price']);
		}
		$disk = RC_Filesystem::disk();
		foreach ($goods_list as $key => $val) {
			if (!$disk->exists(RC_Upload::upload_path($val['image'])) || empty($val['image'])) {
				$goods_list[$key]['image'] = RC_Uri::admin_url('statics/images/nopic.png');
			} else {
				$goods_list[$key]['image'] = RC_Upload::upload_url($val['image']);
			}
		}
		
		$content = array_merge($express_info,$store_info,$order_info);
		
		$content['district']      		= ecjia_region::getRegionName($content['district']);
		$content['street']        		= ecjia_region::getRegionName($content['street']);
		$content['eoprovince']    		= ecjia_region::getRegionName($content['eoprovince']);
		$content['eocity']        		= ecjia_region::getRegionName($content['eocity']);
		$content['eodistrict']    		= ecjia_region::getRegionName($content['eodistrict']);
		$content['eostreet']      		= ecjia_region::getRegionName($content['eostreet']);
		$content['add_time']  	  		= RC_Time::local_date(ecjia::config('time_format'), $content['add_time']);
		$content['signed_time']   		= RC_Time::local_date('Y-m-d H:i', $content['signed_time']);
		$content['all_address'] 		= $content['district'].$content['street'];
		$content['express_all_address'] = $content['eodistrict'].$content['eostreet'];
	
		$this->assign('type', $type);
		$this->assign('content', $content);
		$this->assign('goods_list', $goods_list);
		
		$data = $this->fetch('express_order_detail.dwt');
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('data' => $data));
	}

	/**
	 * 订单重新指派
	 */
	public function express_detail() {
		$this->admin_priv('express_task_manage');
	
		$express_id = intval($_GET['express_id']);
		$store_id = intval($_GET['store_id']);
		$type = trim($_GET['type']);
	
		$express_info = RC_DB::table('express_order as eo')
		->leftJoin('express_user as eu', RC_DB::raw('eo.staff_id'), '=', RC_DB::raw('eu.user_id'))
		->where(RC_DB::raw('eo.express_id'), $express_id)
		->selectRaw('eo.express_user, eo.express_mobile, eo.longitude as u_longitude, eo.latitude as u_latitude, eu.longitude as eu_longitude, eu.latitude as eu_latitude')
		->first();
	
		$store_info =  RC_DB::table('store_franchisee')->where('store_id', $store_id)->selectRaw('longitude as sf_longitude, latitude as sf_latitude')->first();
	
		$content = array_merge($express_info, $store_info);
	
		$content['start'] =  $content['sf_latitude'].','.$content['sf_longitude'];
		$content['end']   =  $content['u_latitude'].','.$content['u_longitude'];
	
		/*配送员列表*/
		$express_user_list = $this->get_express_user_list($type);
		$this->assign('express_user_list', $express_user_list);
		$this->assign('express_count', $express_user_list['express_count']);
		$app_url =  RC_App::apps_url('statics/images', __FILE__);
		$this->assign('app_url', $app_url);
		$this->assign('type', $type);
		$this->assign('content', $content);
		$this->assign('express_id', $express_id);
	
		$this->assign('search_action', RC_Uri::url('express/admin_reminder/reassign_search_user', array('type' => $type)));
	
		$data = $this->fetch('express_order_reassign.dwt');
	
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('data' => $data));
	}
	
	/**
	 * 重新指派页搜索配送员
	 */
	public function reassign_search_user() {
		$type = $_GET['type'];
		$keywords = $_GET['keywords'];
	
		/*配送员列表*/
		$express_user_list = $this->get_express_user_list($type, $keywords);
	
		$this->assign('express_user_list', $express_user_list);
	
		$this->assign('express_count', $express_user_list['express_count']);
		$app_url =  RC_App::apps_url('statics/images', __FILE__);
		$this->assign('app_url', $app_url);
		$this->assign('type', $type);
		$this->assign('search_action', RC_Uri::url('express/admin_reminder/reassign_search_user', array('type' => $type)));
	
		$data = $this->fetch('reassign_express_user_list.dwt');
	
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('data' => $data));
	}
	
    public function remove()
    {
        /* 检查权限 */
        $this->admin_priv('express_reminder_delete', ecjia::MSGTYPE_JSON);

        $express_id = !empty($_GET['express_id']) ? $_GET['express_id'] : $_POST['express_id'];
        $express_id = explode(',', $express_id);

        /* 记录日志 */
        RC_DB::table('express_order_reminder')->whereIn('express_id', $express_id)->delete();
        return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('express/admin_reminder/init')));
    }
    
    /**
     * 配送员列表
     */
    private function get_express_user_list($type, $keywords) {
    	$keywords = $_GET['keywords'];
    	$express_user_view =  RC_DB::table('staff_user as su')
    	->leftJoin('express_user as eu', RC_DB::raw('su.user_id'), '=', RC_DB::raw('eu.user_id'));
    	$express_user_view->where(RC_DB::raw('su.store_id'), 0);
    
    	if (!empty($keywords)) {
    		$express_user_view ->whereRaw('(su.name  like  "%'.mysql_like_quote($keywords).'%")');
    	}
    
    	$db = RC_DB::table('staff_user as su')
    	->leftJoin('express_user as eu', RC_DB::raw('su.user_id'), '=', RC_DB::raw('eu.user_id'));
    	if (!empty($keywords)) {
    		$db ->whereRaw('(su.name  like  "%'.mysql_like_quote($keywords).'%")');
    	}
    
    	if (!empty($type)) {
    		if ($type == 'online') {
    			$express_user_view->where(RC_DB::raw('su.online_status'), 1);
    			$db->where(RC_DB::raw('su.online_status'), 1);
    		} elseif ($type == 'offline') {
    			$express_user_view->where(RC_DB::raw('su.online_status'), 4);
    			$db->where(RC_DB::raw('su.online_status'), 1);
    		}
    	}
    
    	$express_user_count = $db
    	->where(RC_DB::raw('su.store_id'), 0)
    	->select(RC_DB::raw('count(*) as count'),RC_DB::raw('SUM(IF(su.online_status = 1, 1, 0)) as online'),RC_DB::raw('SUM(IF(su.online_status = 4, 1, 0)) as offline'))
    	->first();
    
    	$list = $express_user_view->selectRaw('eu.*, su.mobile, su.name, su.avatar, su.online_status')->orderBy('online_status', 'asc')->get();
    	$data = array();
    	if (!empty($list)) {
    		foreach ($list as $row) {
    			$count = RC_DB::table('express_order')->where('staff_id', $row['user_id'])->select(RC_DB::raw('count(*) as count'),RC_DB::raw('SUM(IF(status = 1, 1, 0)) as wait_pickup'),RC_DB::raw('SUM(IF(status = 2, 1, 0)) as sending'))->first();
    			$row['avatar'] = empty($row['avatar']) ? '' : RC_Upload::upload_url($row['avatar']);
    			$row['wait_pickup_count'] = $count['wait_pickup'];
    			$row['sending_count'] = $count['sending'];
    			$data[] = $row;
    		}
    	}
    
    	$result = array('list' => $data, 'express_count' => $express_user_count);
    
    	return $result;
    }

}

//end
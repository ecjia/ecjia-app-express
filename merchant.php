<?php

/**
 * ECJIA 配送信息
 */

defined('IN_ECJIA') or exit('No permission resources.');

class merchant extends ecjia_merchant {
	private $express_order_db;

	public function __construct() {
		parent::__construct();

		RC_Loader::load_app_func('global');
// 		assign_adminlog_content();

		$this->express_order_db	= RC_Model::model('express/express_order_model');
		
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Script::enqueue_script('merchant_express', RC_App::apps_url('statics/js/merchant_shipping.js', __FILE__));
// 		RC_Style::enqueue_style('merchant_express', RC_App::apps_url('statics/css/merchant_shipping.css', __FILE__), array(), false, false);
		RC_Script::enqueue_script('ecjia.utils');
		RC_Script::enqueue_script('ecjia.common');
		RC_Style::enqueue_style('chosen');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Loader::load_app_class('shipping_factory', null, false);
		
		RC_Script::localize_script('merchant_shipping', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		RC_Script::localize_script('shopping_admin', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		
// 		ecjia_merchant_screen::get_current_screen()->set_parentage('shipping', 'shipping/merchant.php');
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送管理', RC_Uri::url('shipping/admin/init')));
	}

	/**
	 * 配送方式列表 
	 */
	public function init() { 
// 		$this->admin_priv('ship_merchant_manage', ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('express::express.express_list')));
		
		$this->assign('ur_here', RC_Lang::get('express::express.express_list'));
		
		$where = array('store_id' => $_SESSION['store_id']);
		$express_list = $this->express_order_db->where($where)->select();
		
		if (!empty($express_list)) {
			foreach ($express_list as $key => $val) {
				$express_list[$key]['formatted_add_time'] = RC_Time::local_date(ecjia::config('time_format'), $val['add_time']);
				if ($val['from'] == 'assign') {
					$express_list[$key]['label_from'] = RC_Lang::get('express::express.assign');
				} elseif ($val['from'] == 'grab') {
					$express_list[$key]['label_from'] = RC_Lang::get('express::express.grab');
				} else {
					$express_list[$key]['label_from'] = RC_Lang::get('express::express.wait_assign');
				}
				
				switch ($val['status']) {
					case 0 : 
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.wait_assign_express');
						break;
					case 1 : 
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.wait_pick_up');
						break;
					case 2 :
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.express_delivery');
						break;
					case 3 :
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.return_express');
						break;
					case 4 :
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.refused');
						break;
					case 5 :
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.already_signed');
						break;
					case 6 :
						$express_list[$key]['label_status'] = RC_Lang::get('express::express.has_returned');
						break;
				}
			}
		}
		
		$this->assign('express_list', $express_list);
		
		$this->display('express_list.dwt');
	}
	
	public function info() {
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('express::express.express_info')));
		
		$express_id = isset($_GET['express_id']) ? intval($_GET['express_id']) : 0;
		$where = array('store_id' => $_SESSION['store_id']);
		
		$express_info = RC_DB::table('express_order as eo')
// 			->leftjoin('delivery_order as do', RC_DB::raw('eo.delivery_id'), '=', RC_DB::raw('do.delivery_id'))
			->where(RC_DB::raw('express_id'), $express_id)
			->first();
		
		$express_info['formatted_add_time']		= RC_Time::local_date(ecjia::config('time_format'), $express_info['add_time']);
		$express_info['formatted_receive_time']	= $express_info['receive_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['receive_time']) : '';
		$express_info['formatted_express_time']	= $express_info['express_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['express_time']) : '';
		$express_info['formatted_signed_time']	= $express_info['signed_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['signed_time']) : '';
		$express_info['formatted_update_time']	= $express_info['update_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['update_time']) : '';
		
		if ($express_info['from'] == 'assign') {
			$express_info['label_from'] = RC_Lang::get('express::express.assign');
		} elseif ($val['from'] == 'grab') {
			$express_info['label_from'] = RC_Lang::get('express::express.grab');
		} else {
			$express_info['label_from'] = RC_Lang::get('express::express.wait_assign');
		}
		
		switch ($express_info['status']) {
			case 0 :
				$express_info['label_status'] = RC_Lang::get('express::express.wait_assign_express');
				break;
			case 1 :
				$express_info['label_status'] = RC_Lang::get('express::express.wait_pick_up');
				break;
			case 2 :
				$express_info['label_status'] = RC_Lang::get('express::express.express_delivery');
				break;
			case 3 :
				$express_info['label_status'] = RC_Lang::get('express::express.return_express');
				break;
			case 4 :
				$express_info['label_status'] = RC_Lang::get('express::express.refused');
				break;
			case 5 :
				$express_info['label_status'] = RC_Lang::get('express::express.already_signed');
				break;
			case 6 :
				$express_info['label_status'] = RC_Lang::get('express::express.has_returned');
				break;
		}
		
		/* 取得发货单商品 */
		$goods_list = RC_DB::table('delivery_goods')->where('delivery_id', $express_info['delivery_id'])->get();
		
		/* 取得区域名 */
		$region = RC_DB::table('express_order as eo')
							->leftJoin('region as c', RC_DB::raw('eo.country'), '=', RC_DB::raw('c.region_id'))
							->leftJoin('region as p', RC_DB::raw('eo.province'), '=', RC_DB::raw('p.region_id'))
							->leftJoin('region as t', RC_DB::raw('eo.city'), '=', RC_DB::raw('t.region_id'))
							->leftJoin('region as d', RC_DB::raw('eo.district'), '=', RC_DB::raw('d.region_id'))
							->select(RC_DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''),'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region"))
							->where(RC_DB::raw('eo.express_id'), $express_id)
							->first();
		
		$express_info['region'] = $region['region'];
		
		if ($express_info['staff_id'] > 0) {
			$express_info['staff_user'] = RC_DB::table('staff_user')->where('user_id', $express_info['staff_id'])->pluck('name');
		}
		
		$staff_list = RC_DB::table('staff_user')
						->where('store_id', $_SESSION['store_id'])
						// 								->where('online_status', 1)
						->get();
		
		$this->assign('staff_user', $staff_list);
		$this->assign('express_info', $express_info);
		$this->assign('goods_list', $goods_list);
		
		$this->assign('ur_here', RC_Lang::get('express::express.express_info'));
		$this->assign('action_link',array('href' => RC_Uri::url('express/merchant/init'),'text' => RC_Lang::get('express::express.express_list')));
		
		
		$this->display('express_info.dwt');
	}
}	

// end
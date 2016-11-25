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
		
		$where = array('store_id' => $_SESSION['store_id']);
		$express_list = $this->express_order_db->where($where)->select();
		
		
		$this->assign('ur_here', RC_Lang::get('express::express.express_info'));
		$this->assign('action_link',array('href' => RC_Uri::url('express/merchant/init'),'text' => RC_Lang::get('express::express.express_list')));
		
		
		$this->display('express_info.dwt');
	}
}	

// end
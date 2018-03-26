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
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 配送调度任务中心
 * @author songqianqian
 */
class merchant extends ecjia_merchant {
	
	public function __construct() {
		parent::__construct();
		
		/* 加载全局 js/css */		
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('bootstrap-editable-script', dirname(RC_App::app_dir_url(__FILE__)) . '/merchant/statics/assets/bootstrap-fileupload/bootstrap-fileupload.js', array());
		RC_Style::enqueue_style('bootstrap-fileupload', dirname(RC_App::app_dir_url(__FILE__)) . '/merchant/statics/assets/bootstrap-fileupload/bootstrap-fileupload.css', array(), false, false);
		RC_Script::enqueue_script('qq_map', 'https://map.qq.com/api/js?v=2.exp');
		
		
		RC_Script::enqueue_script('mh_express_order', RC_App::apps_url('statics/js/mh_express_order.js', __FILE__));
		RC_Style::enqueue_style('mh_express', RC_App::apps_url('statics/css/mh_express.css', __FILE__));

		ecjia_merchant_screen::get_current_screen()->add_nav_here(new admin_nav_here('员工管理', RC_Uri::url('staff/mh_group/init')));
		ecjia_merchant_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送任务', RC_Uri::url('express/merchant/init')));
		ecjia_merchant_screen::get_current_screen()->set_parentage('express', 'express/merchant.php');
	}
	

	//配送任务列表--默认待抢单
	public function init() {
		$this->admin_priv('mh_express_task_manage');
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送任务'));
		$this->assign('ur_here', '配送任务');
		
		$type = trim($_GET['type']);
		$this->assign('type', $type);

		$express_order_list = $this->express_order_list($type);
		$this->assign('express_order_list', $express_order_list);
		
		$this->assign('express_order_count', $express_order_list['express_order_count']);
		$this->assign('filter', $express_order_list['filter']);
		$this->assign('search_action', RC_Uri::url('express/merchant/init'));
		
		$this->display('express_order_list.dwt');
	}
	
	
	//获取配送单列表
	private function express_order_list($type){
		$db_data = RC_DB::table('express_order as eo')
		->leftJoin('express_user as eu', RC_DB::raw('eo.staff_id'), '=', RC_DB::raw('eu.user_id'))
		->leftJoin('store_franchisee as sf', RC_DB::raw('eo.store_id'), '=', RC_DB::raw('sf.store_id'));
		
		$db_data->where(RC_DB::raw('eo.store_id'), $_SESSION['store_id']);
		$db_data->where(RC_DB::raw('eo.shipping_code'), 'ship_o2o_express');
			
		$filter['keyword']	 = trim($_GET['keyword']);
		if ($filter['keyword']) {
			$db_data ->whereRaw('(eo.express_user  like  "%'.mysql_like_quote($filter['keyword']).'%"  or eo.express_mobile like "%'.mysql_like_quote($filter['keyword']).'%")');
		}
		
		
		$express_order_count = $db_data->select(RC_DB::raw('SUM(IF(eo.status = 0, 1, 0)) as wait_grab'),
				RC_DB::raw('SUM(IF(eo.status = 1, 1, 0)) as wait_pickup'),
				RC_DB::raw('SUM(IF(eo.status = 2, 1, 0)) as sending'))->first();
	
		if ($type == 'wait_grab') {
			$db_data->where(RC_DB::raw('eo.status'), 0);
		}
		
		if ($type == 'wait_pickup') {
			$db_data->where(RC_DB::raw('eo.status'), 1);
		}
		
		if ($type == 'sending') {
			$db_data->where(RC_DB::raw('eo.status'), 2);
		}
		
		$count = $db_data->count();
		$page = new ecjia_merchant_page($count, 10, 5);
	
		$field = '';
		
		$list = $db_data
		->selectRaw('eo.store_id, eo.express_id, eo.express_sn, eo.consignee, eo.mobile as consignee_mobile, eo.status,  
					eo.district, eo.street, eo.address, eo.distance, eo.add_time, eo.receive_time, eo.staff_id, eo.express_user, eo.express_mobile, eo.from,
					eo.longitude as user_longitude, eo.latitude as user_latitude,  
					eu.longitude as ex_longitude, eu.latitude as ex_latitude, 
					sf.longitude as sf_longitude, sf.latitude as sf_latitude, sf.district as sf_district, sf.street as sf_street, sf.address as sf_address')
		->orderBy(RC_DB::raw('eo.add_time'), 'desc')
		->take(10)
		->skip($page->start_id-1)
		->get();
	
		$data = array();
		if (!empty($list)) {
			foreach ($list as $row) {
				if ($type != 'wait_grab') {
					$row['online_status'] = RC_DB::table('staff_user')->where('user_id', $row['staff_id'])->pluck('online_status');
				}
				$row['add_time'] 	 = RC_Time::local_date('Y-m-d H:i:s', $row['add_time']);
				$row['receive_time'] = RC_Time::local_date('Y-m-d H:i:s', $row['receive_time']);
				$row['from_address'] = ecjia_region::getRegionName($row['sf_district']).ecjia_region::getRegionName($row['sf_street']).$row['sf_address'];
				$row['to_address']	 = ecjia_region::getRegionName($row['district']).ecjia_region::getRegionName($row['street']).$row['address'];
				$data[] = $row;
			}
		}
		$res = array('list' => $data, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'express_order_count' => $express_order_count);
		return $res;
	}

}

//end
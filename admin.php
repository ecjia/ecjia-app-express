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
 * @author zrl
 */
class admin extends ecjia_admin {
	
	public function __construct() {
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
		RC_Style::enqueue_style('admin_express_task', RC_App::apps_url('statics/css/admin_express_task.css', __FILE__));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送调度', RC_Uri::url('express/admin/init')));
	}
	
	/**
	 * 任务中心
	 */
	public function init() {
		$this->admin_priv('express_task_manage');
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('任务中心'));
		$this->assign('ur_here', '任务中心');
		
		$type = empty($_GET['type']) ? 'wait_grab' : trim($_GET['type']);
		$keywords = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		
		$this->assign('type', $type);
		
		/*待抢单列表*/
		$wait_grab_list = $this->get_wait_grab_list();
		$count = count($wait_grab_list);
		
		/*配送员列表*/
		$express_user_list = $this->get_express_user_list();
		
		$this->assign('search_action', RC_Uri::url('express/admin/init'));
		$app_url =  RC_App::apps_url('statics/images', __FILE__);
		$this->assign('app_url', $app_url);
		$this->assign('wait_grab_count', $count);
		$this->assign('wait_grab_list', $wait_grab_list);
		$this->assign('express_count', $express_user_list['express_count']);
		$this->assign('express_user_list', $express_user_list);
		$this->display('express_task_list.dwt');
	}
	
	/**
	 * 待抢单列表
	 */
	private function get_wait_grab_list(){
		$dbview = RC_DB::table('express_order as eo')->leftJoin('store_franchisee as sf', RC_DB::raw('eo.store_id'), '=', RC_DB::raw('sf.store_id'));
		$list = $dbview->where(RC_DB::raw('eo.status'), 0)
		->select(RC_DB::raw('eo.express_id, eo.express_sn, eo.country, eo.province, eo.city, eo.district, eo.street, eo.address, eo.distance, eo.add_time, sf.province as sf_province, sf.city as sf_city, sf.district as sf_district, sf.street as sf_street, sf.address as sf_address'))
		->orderBy(RC_DB::raw('eo.add_time'), 'desc')
		->get();
		$data = array();
		if (!empty($list)) {
			foreach ($list as $row) {
				$row['format_add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['start_time']);
				$row['from_address'] 	= ecjia_region::getRegionName($row['sf_province']).ecjia_region::getRegionName($row['sf_city']).ecjia_region::getRegionName($row['sf_district']).ecjia_region::getRegionName($row['sf_street']).ecjia_region::getRegionName($row['sf_address']);
				$row['to_address']		= ecjia_region::getRegionName($row['province']).ecjia_region::getRegionName($row['city']).ecjia_region::getRegionName($row['district']).ecjia_region::getRegionName($row['street']).ecjia_region::getRegionName($row['address']);
				$data[] = $row;
			}
		}
		return $data;
	}
	
	/**
	 * 配送员列表
	 */
	private function get_express_user_list() {
		$keywords = $_GET['keywords'];
		$express_user_view =  RC_DB::table('staff_user as su')
		->leftJoin('express_user as eu', RC_DB::raw('su.user_id'), '=', RC_DB::raw('eu.user_id'));
		$express_user_view->where(RC_DB::raw('su.store_id'), 0);
		
		if (!empty($keywords)) {
			$express_user_view ->whereRaw('(su.name  like  "%'.mysql_like_quote($keywords).'%")');
		}
		$express_user_count = RC_DB::table('staff_user as su')
								->leftJoin('express_user as eu', RC_DB::raw('su.user_id'), '=', RC_DB::raw('eu.user_id'))
								->where(RC_DB::raw('su.store_id'), 0)
								->select(RC_DB::raw('count(*) as count'),RC_DB::raw('SUM(IF(su.online_status = 1, 1, 0)) as online'),RC_DB::raw('SUM(IF(su.online_status = 4, 1, 0)) as offline'))
								->first();
		
		$list = $express_user_view->selectRaw('eu.*, su.mobile, su.name, su.avatar, su.online_status')->get();
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
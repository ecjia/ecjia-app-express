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
 * 商家管理
 * @author songqianqian
 */
class admin_merchant extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'), array(), false, false);
		RC_Style::enqueue_style('bootstrap-editable',RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'), array(), false, false);
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('ecjia-region');
		RC_Script::enqueue_script('qq_map', 'https://map.qq.com/api/js?v=2.exp');
		
		RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		
		RC_Script::enqueue_script('admin_merchant', RC_App::apps_url('statics/js/admin_merchant.js', __FILE__));
		RC_Style::enqueue_style('admin_express', RC_App::apps_url('statics/css/admin_express.css', __FILE__));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('商家管理', RC_Uri::url('express/admin_merchant/init')));
	}
	
	/**
	 * 商家列表管理
	 */
	public function init() {
		$this->admin_priv('express_merchant_manage');
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('商家管理'));
		$this->assign('ur_here', '商家管理');
		
		$type = trim($_GET['type']);
		$this->assign('type', $type);
		$data = $this->get_merchant_list($type);
		$this->assign('data', $data);
		$this->assign('type_count', $data['count']);
		
		$this->assign('search_action', RC_Uri::url('express/admin_merchant/init'));

		$this->display('merchant_list.dwt');
	}
	
	private function get_merchant_list($type = '') {
		$db_data = RC_DB::table('staff_user as su')
		->leftJoin('express_user as eu', RC_DB::raw('su.user_id'), '=', RC_DB::raw('eu.user_id'));
	
		$db_data->where(RC_DB::raw('su.store_id'), 0);
	
		$filter['keyword']	 = trim($_GET['keyword']);
		$filter['work_type'] = trim($_GET['work_type']);
	
		if ($filter['keyword']) {
			$db_data ->whereRaw('(su.name  like  "%'.mysql_like_quote($filter['keyword']).'%"  or su.mobile like "%'.mysql_like_quote($filter['keyword']).'%")');
		}
	
		if ($filter['work_type']) {
			$db_data ->where('work_type', $filter['work_type']);
		}
	
		$express_count = $db_data->select(RC_DB::raw('count(*) as count'),
				RC_DB::raw('SUM(IF(su.online_status = 1, 1, 0)) as online'),
				RC_DB::raw('SUM(IF(su.online_status = 4, 1, 0)) as offline'))->first();
	
		if ($type == 'online') {
			$db_data->where(RC_DB::raw('su.online_status'), 1);
		}
	
		if ($type == 'offline') {
			$db_data->where(RC_DB::raw('su.online_status'), 4);
		}
		$count = $db_data->count();
		$page = new ecjia_page($count, 10, 5);
	
		$data = $db_data
		->selectRaw('eu.*, su.user_id, su.name, su.mobile, su.add_time, su.online_status')
		->orderby(RC_DB::raw('su.user_id'), 'desc')
		->take(10)
		->skip($page->start_id-1)
		->get();
	
		$list = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['add_time']  = RC_Time::local_date('Y-m-d H:i:s', $row['add_time']);
				$list[] = $row;
			}
		}
		return array('list' => $list, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'count' => $express_count);
	}
}

//end
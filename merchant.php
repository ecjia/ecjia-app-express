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
 * ECJIA 配送信息
 */
class merchant extends ecjia_merchant
{
    public function __construct()
    {
        parent::__construct();

        RC_Loader::load_app_func('global');
        RC_Loader::load_app_class('shipping_factory', 'shipping', false);

        /* 加载全局 js/css */
        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('smoke');

        RC_Script::enqueue_script('express', RC_App::apps_url('statics/js/merchant_express.js', __FILE__));
        RC_Script::enqueue_script('ecjia.utils');

        RC_Style::enqueue_style('merchant', RC_App::apps_url('statics/css/merchant.css', __FILE__), array());

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送管理', RC_Uri::url('express/merchant/shipping_template')));
    }

    //运费模板
    public function shipping_template()
    {
        $this->admin_priv('express_manage');

        $this->assign('ur_here', '运费模版');
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('运费模版'));

        $data = $this->get_template_list();
        $this->assign('data', $data);

        $this->display('shipping_template_list.dwt');
    }

    public function add_shipping_template()
    {
        $this->admin_priv('express_manage');
        $this->assign('ur_here', '添加运费模版');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('运费模版', RC_Uri::url('express/merchant/shipping_template')));
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('添加运费模版'));
        $this->assign('action_link', array('href' => RC_Uri::url('express/merchant/shipping_template'), 'text' => '运费模版'));

        $provinces = ecjia_region::getSubarea(ecjia::config('shop_country')); //获取当前国家的所有省份
        $this->assign('provinces', $provinces);

        $shipping = $this->get_shipping_list();
        $this->assign('shipping', $shipping);

        $this->assign('shipping_form_action', RC_Uri::url('express/merchant/add_shipping'));
        $this->assign('form_action', RC_Uri::url('express/merchant/save_shipping_template'));

        $this->display('shipping_template_info.dwt');
    }

    public function edit_shipping_template()
    {
        $template_name = !empty($_GET['template_name']) ? trim($_GET['template_name']) : '';

        $this->admin_priv('express_manage');
        $this->assign('ur_here', '编辑运费模版');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('运费模版', RC_Uri::url('express/merchant/shipping_template')));
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('编辑运费模版'));
        $this->assign('action_link', array('href' => RC_Uri::url('express/merchant/shipping_template'), 'text' => '运费模版'));

        $provinces = ecjia_region::getSubarea(ecjia::config('shop_country')); //获取当前国家的所有省份
        $this->assign('provinces', $provinces);

        $data = RC_DB::table('shipping_area')
            ->where('store_id', $_SESSION['store_id'])
            ->where('shipping_area_name', $template_name)
            ->get();

        $regions = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $shipping_data = RC_DB::table('shipping as s')
                    ->leftJoin('shipping_area as a', RC_DB::raw('a.shipping_id'), '=', RC_DB::raw('s.shipping_id'))
                    ->selectRaw('s.shipping_name, s.shipping_code, s.support_cod, a.*')
                    ->where(RC_DB::raw('a.shipping_area_id'), $v['shipping_area_id'])
                    ->where(RC_DB::raw('a.store_id'), $_SESSION['store_id'])
                    ->first();
                $fields = unserialize($shipping_data['configure']);

                /* 如果配送方式支持货到付款并且没有设置货到付款支付费用，则加入货到付款费用 */
                if ($shipping_data['support_cod'] && $fields[count($fields) - 1]['name'] != 'pay_fee') {
                    $fields[] = array(
                        'name'  => 'pay_fee',
                        'value' => 0,
                    );
                }

                $shipping_handle = new shipping_factory($shipping_data['shipping_code']);
                $fields          = $shipping_handle->form_format($fields, true);
                if (!empty($fields)) {
                    foreach ($fields as $key => $val) {
                        /* 替换更改的语言项 */
                        if ($val['name'] == 'basic_fee') {
                            $val['name'] = 'base_fee';
                        }

                        if ($val['name'] == 'item_fee') {
                            $item_fee = 1;
                        }
                        if ($val['name'] == 'fee_compute_mode') {
                            $data[$k]['fee_compute_mode'] = $val['value'];
                            unset($fields[$key]);
                        } else {
                            $fields[$key]['name']  = $val['name'];
                            $fields[$key]['label'] = empty($val['label']) ? RC_Lang::get('shipping::shipping_area.' . $val['name']) : $val['label'];
                        }

                        if ($shipping_data['shipping_code'] == 'ship_o2o_express' && (in_array($val['name'], array('ship_days', 'last_order_time', 'ship_time', 'express_money')))) {
                            if ($val['name'] == 'ship_time') {
                                $o2o_shipping_time = array();
                                foreach ($val['value'] as $v) {
                                    $o2o_shipping_time[] = $v;
                                }
                                $data[$k]['o2o_shipping_time'] = $o2o_shipping_time;
                            }
                            if ($val['name'] == 'ship_days') {
                                $data[$k]['ship_days'] = $o2o_shipping_time;
                            }
                            if ($val['name'] == 'last_order_time') {
                                $data[$k]['last_order_time'] = $o2o_shipping_time;
                            }
                            if ($val['name'] == 'express_money') {
                                $data[$k]['express_money'] = $o2o_shipping_time;
                            }
                            unset($fields[$key]);
                        }
                    }
                }
                if (empty($item_fee) && !empty($fields)) {
                    $field = array(
                        'name'  => 'item_fee',
                        'value' => '0',
                        'label' => ecjia_config::has(RC_Lang::get('shipping::shipping_area.item_fee')) ? '' : RC_Lang::get('shipping::shipping_area.item_fee'),
                    );
                    array_unshift($fields, $field);
                }
                $data[$k]['fields']        = $fields;
                $data[$k]['shipping_code'] = $shipping_data['shipping_code'];
                $data[$k]['shipping_name'] = $shipping_data['shipping_name'];

                if ($k == 0) {
                    $regions = RC_DB::table('area_region as a')
                        ->leftJoin('regions as r', RC_DB::raw('a.region_id'), '=', RC_DB::raw('r.region_id'))
                        ->where(RC_DB::raw('a.shipping_area_id'), $shipping_data['shipping_area_id'])
                        ->selectRaw('r.region_name, r.region_id')
                        ->get();
                }
            }
        }
        $this->assign('regions', $regions);

        $shipping = $this->get_shipping_list();
        $this->assign('shipping', $shipping);

        $this->assign('shipping_form_action', RC_Uri::url('express/merchant/add_shipping'));
        $this->assign('form_action', RC_Uri::url('express/merchant/save_shipping_template'));

        $this->assign('data', $data);
        $this->assign('template_name', $template_name);

        $this->display('shipping_template_info.dwt');
    }

    public function get_shipping_info()
    {
        $shipping_id = !empty($_POST['shipping_id']) ? intval($_POST['shipping_id']) : 0;

        $shipping_data = RC_DB::table('shipping')
            ->select('shipping_name', 'shipping_code', 'support_cod')
            ->where('shipping_id', $shipping_id)
            ->first();

        $fields          = array();
        $shipping_handle = new shipping_factory($shipping_data['shipping_code']);
        $fields          = $shipping_handle->form_format($fields, true);

        $count                   = count($fields);
        $fields[$count]['name']  = "free_money";
        $fields[$count]['value'] = "0";
        $fields[$count]['label'] = RC_Lang::get('shipping::shipping_area.free_money');

        /* 如果支持货到付款，则允许设置货到付款支付费用 */
        if ($shipping_data['support_cod']) {
            $count++;
            $fields[$count]['name']  = "pay_fee";
            $fields[$count]['value'] = "0";
            $fields[$count]['label'] = RC_Lang::get('shipping::shipping_area.pay_fee');
        }

        $shipping_area['shipping_id']   = $shipping_id;
        $shipping_area['shipping_code'] = $shipping_data['shipping_code'];
        return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $fields, 'shipping_area' => $shipping_area));
    }

    public function add_shipping()
    {
        $shipping_id = !empty($_POST['shipping_id']) ? intval($_POST['shipping_id']) : 0;
        $temp_name   = !empty($_POST['temp_name']) ? trim($_POST['temp_name']) : '';

        $template_name = !empty($_POST['template_name']) ? trim($_POST['template_name']) : '';

        $shipping_area_id = !empty($_POST['shipping_area_id']) ? intval($_POST['shipping_area_id']) : 0;
        $regions          = !empty($_POST['regions']) ? $_POST['regions'] : '';

        if (!empty($temp_name) && empty($template_name)) {
            $count = RC_DB::table('shipping_area')
                ->where('store_id', $_SESSION['store_id'])
                ->where('shipping_area_name', $temp_name)
                ->count();
            if ($count > 0) {
                return $this->showmessage('该模版名称已存在', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
            }
        }

        if (!empty($temp_name) && !empty($template_name)) {
            $count = RC_DB::table('shipping_area')
                ->where('store_id', $_SESSION['store_id'])
                ->where('shipping_area_name', $temp_name)
                ->where('shipping_area_name', '!=', $template_name)
                ->count();
            if ($count > 0) {
                return $this->showmessage('该模版名称已存在', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
            }
        }

        $template_name = !empty($temp_name) ? $temp_name : (!empty($template_name) ? $template_name : '');

        if (!empty($shipping_area_id)) {
            $count = RC_DB::table('shipping_area')
                ->where('store_id', $_SESSION['store_id'])
                ->where('shipping_area_name', $template_name)
                ->where('shipping_id', $shipping_id)
                ->where('shipping_area_id', '!=', $shipping_area_id)
                ->count();
            if ($count > 0) {
                return $this->showmessage('该配送方式已存在', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
            }
        }
        $shipping_data = RC_DB::table('shipping')->where('shipping_id', $shipping_id)->select('shipping_name', 'shipping_code', 'support_cod')->first();

        $config          = array();
        $shipping_handle = new shipping_factory($shipping_data['shipping_code']);
        $config          = $shipping_handle->form_format($config, true);
        if (!empty($config)) {
            foreach ($config as $key => $val) {
                $config[$key]['name']  = $val['name'];
                $config[$key]['value'] = $_POST[$val['name']];
            }
        }
        $count                   = count($config);
        $config[$count]['name']  = 'free_money';
        $config[$count]['value'] = empty($_POST['free_money']) ? '0' : trim($_POST['free_money']);
        $count++;
        $config[$count]['name']  = 'fee_compute_mode';
        $config[$count]['value'] = empty($_POST['fee_compute_mode']) ? '' : trim($_POST['fee_compute_mode']);

        /* 如果支持货到付款，则允许设置货到付款支付费用 */
        if ($shipping_data['support_cod']) {
            $count++;
            $config[$count]['name']  = 'pay_fee';
            $config[$count]['value'] = make_semiangle(empty($_POST['pay_fee']) ? '0' : trim($_POST['pay_fee']));
        }

        if ($shipping_data['shipping_code'] == 'ship_o2o_express') {
            $time = array();
            foreach ($_POST['start_ship_time'] as $k => $v) {
                $time[$k]['start'] = $v;
                $time[$k]['end']   = $_POST['end_ship_time'][$k];
            }

            $config[$count]['name']  = 'express_money';
            $config[$count]['value'] = empty($_POST['express_money']) ? 0 : floatval($_POST['express_money']);
            $count++;
            $config[$count]['name']  = 'ship_days';
            $config[$count]['value'] = empty($_POST['ship_days']) ? '' : intval($_POST['ship_days']);
            $count++;
            $config[$count]['name']  = 'last_order_time';
            $config[$count]['value'] = empty($_POST['last_order_time']) ? '' : trim($_POST['last_order_time']);
            $count++;
            $config[$count]['name']  = 'ship_time';
            $config[$count]['value'] = empty($time) ? '' : $time;
        }
        $data = array(
            'shipping_area_name' => $template_name,
            'shipping_id'        => $shipping_id,
            'configure'          => serialize($config),
        );

        if (!empty($shipping_area_id)) {
            RC_DB::table('shipping_area')->where('store_id', $_SESSION['store_id'])->where('shipping_area_id', $shipping_area_id)->update($data);
        } else {
            if (isset($_SESSION['store_id'])) {
                $data['store_id'] = $_SESSION['store_id'];
            }
            RC_DB::table('shipping_area')->insert($data);
        }

        //重新设置area_region
        $area_list = RC_DB::table('shipping_area')
            ->where('store_id', $_SESSION['store_id'])
            ->where('shipping_area_name', $template_name)
            ->lists('shipping_area_id');
        RC_DB::table('area_region')->whereIn('shipping_area_id', $area_list)->delete();
        _dump($area_list);
        _dump($regions);
        foreach ($area_list as $v) {
            foreach ($regions as $val) {
                $data = array(
                    'shipping_area_id' => $v,
                    'region_id'        => $val,
                );
                RC_DB::table('area_region')->insert($data);
            }
        }
        $url = RC_Uri::url('express/merchant/edit_shipping_template', array('template_name' => $template_name));
        if (!empty($shipping_area_id)) {
            return $this->showmessage('编辑成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $url));
        } else {
            return $this->showmessage('添加成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $url));
        }
    }

    public function remove_shipping()
    {
        $shipping_area_id = !empty($_GET['id']) ? intval($_GET['id']) : 0;

        $info = RC_DB::table('shipping_area')->where('shipping_area_id', $shipping_area_id)->where('store_id', $_SESSION['store_id'])->first();

        RC_DB::table('shipping_area')->where('shipping_area_id', $shipping_area_id)->where('store_id', $_SESSION['store_id'])->delete();
        RC_DB::table('area_region')->where('shipping_area_id', $shipping_area_id)->delete();

        $count = RC_DB::table('shipping_area')->where('shipping_area_name', $info['shipping_area_name'])->where('store_id', $_SESSION['store_id'])->count();
        if ($count == 0) {
            return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('express/merchant/add_shipping_template')));
        } else {
            return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
        }
    }

    public function remove_shipping_template()
    {
        $name         = !empty($_GET['name']) ? trim($_GET['name']) : '';
        $area_id_list = RC_DB::table('shipping_area')->where('shipping_area_name', $name)->where('store_id', $_SESSION['store_id'])->lists('shipping_area_id');

        RC_DB::table('shipping_area')->where('shipping_area_name', $name)->where('store_id', $_SESSION['store_id'])->delete();
        if (!empty($area_id_list)) {
            RC_DB::table('area_region')->whereIn('shipping_area_id', $area_id_list)->delete();
        }
        return $this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
    }

    public function save_shipping_template()
    {
        $temp_name     = !empty($_POST['temp_name']) ? trim($_POST['temp_name']) : '';
        $template_name = !empty($_POST['template_name']) ? trim($_POST['template_name']) : '';

        $regions = !empty($_POST['regions']) ? $_POST['regions'] : '';
        if (empty($regions)) {
            return $this->showmessage('请添加配送地区', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        $area_list = RC_DB::table('shipping_area')
            ->where('store_id', $_SESSION['store_id'])
            ->where('shipping_area_name', $template_name)
            ->lists('shipping_area_id');
        if (empty($area_list)) {
            return $this->showmessage('请添加快递方式', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        if (!empty($template_name)) {
            $count = RC_DB::table('shipping_area')
                ->where('store_id', $_SESSION['store_id'])
                ->where('shipping_area_name', '!=', $template_name)
                ->where('shipping_area_name', $temp_name)
                ->count();
            if ($count > 0) {
                return $this->showmessage('该模版名称已存在', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
            }
        }

        RC_DB::table('area_region')->whereIn('shipping_area_id', $area_list)->delete();
        foreach ($area_list as $k => $v) {
            foreach ($regions as $key => $val) {
                $area_list = array(
                    'shipping_area_id' => $v,
                    'region_id'        => $val,
                );
                RC_DB::table('area_region')->insert($area_list);
            }
        }
        $url = RC_Uri::url('express/merchant/edit_shipping_template', array('template_name' => $template_name));

        return $this->showmessage('编辑成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $url));
    }

    /**
     * 配送记录
     */
    public function shipping_record()
    {
        $this->admin_priv('express_manage');
        $this->assign('ur_here', '配送记录');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('配送记录'));

        $count = RC_Api::api('express', 'express_order_count');

        /* 定义每页数量*/
        $filter['limit'] = 10;
        $page            = new ecjia_merchant_page($count, $filter['limit'], 5);
        $filter['skip']  = $page->start_id - 1;

        $express_list = RC_Api::api('express', 'express_order_list', $filter);

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
                    case 0:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.wait_assign_express');
                        break;
                    case 1:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.wait_pick_up');
                        break;
                    case 2:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.express_delivery');
                        break;
                    case 3:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.return_express');
                        break;
                    case 4:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.refused');
                        break;
                    case 5:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.already_signed');
                        break;
                    case 6:
                        $express_list[$key]['label_status'] = RC_Lang::get('express::express.has_returned');
                        break;
                }
            }
        }

        $this->assign('express_list', $express_list);

        $this->assign('page', $page->show(2));

        $this->display('shipping_record_list.dwt');
    }

    public function record_info()
    {
        $this->admin_priv('express_manage');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('express::express.express_info')));

        $express_id = isset($_GET['express_id']) ? intval($_GET['express_id']) : 0;
        $where      = array('store_id' => $_SESSION['store_id']);

        $express_info = RC_DB::table('express_order as eo')
            ->where(RC_DB::raw('express_id'), $express_id)
            ->first();

        $express_info['formatted_add_time']     = RC_Time::local_date(ecjia::config('time_format'), $express_info['add_time']);
        $express_info['formatted_receive_time'] = $express_info['receive_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['receive_time']) : '';
        $express_info['formatted_express_time'] = $express_info['express_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['express_time']) : '';
        $express_info['formatted_signed_time']  = $express_info['signed_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['signed_time']) : '';
        $express_info['formatted_update_time']  = $express_info['update_time'] > 0 ? RC_Time::local_date(ecjia::config('time_format'), $express_info['update_time']) : '';

        if ($express_info['from'] == 'assign') {
            $express_info['label_from'] = RC_Lang::get('express::express.assign');
        } elseif ($express_info['from'] == 'grab') {
            $express_info['label_from'] = RC_Lang::get('express::express.grab');
        } elseif ($express_info['from'] == 'grab' && $express_info['staff_id'] == 0) {
            $express_info['label_from'] = RC_Lang::get('express::express.wait_assign');
        }

        switch ($express_info['status']) {
            case 0:
                $express_info['label_status'] = RC_Lang::get('express::express.wait_assign_express');
                break;
            case 1:
                $express_info['label_status'] = RC_Lang::get('express::express.wait_pick_up');
                break;
            case 2:
                $express_info['label_status'] = RC_Lang::get('express::express.express_delivery');
                break;
            case 3:
                $express_info['label_status'] = RC_Lang::get('express::express.return_express');
                break;
            case 4:
                $express_info['label_status'] = RC_Lang::get('express::express.refused');
                break;
            case 5:
                $express_info['label_status'] = RC_Lang::get('express::express.already_signed');
                break;
            case 6:
                $express_info['label_status'] = RC_Lang::get('express::express.has_returned');
                break;
        }

        /* 取得发货单商品 */
        $goods_list = RC_DB::table('delivery_goods')->where('delivery_id', $express_info['delivery_id'])->get();

        /* 取得区域名 */
        $region = RC_DB::table('express_order as eo')
            ->leftJoin('regions as c', RC_DB::raw('eo.country'), '=', RC_DB::raw('c.region_id'))
            ->leftJoin('regions as p', RC_DB::raw('eo.province'), '=', RC_DB::raw('p.region_id'))
            ->leftJoin('regions as t', RC_DB::raw('eo.city'), '=', RC_DB::raw('t.region_id'))
            ->leftJoin('regions as d', RC_DB::raw('eo.district'), '=', RC_DB::raw('d.region_id'))
            ->select(RC_DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''),'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region"))
            ->where(RC_DB::raw('eo.express_id'), $express_id)
            ->first();

        $express_info['region'] = $region['region'];

        if ($express_info['staff_id'] > 0) {
            $express_info['staff_user'] = RC_DB::table('staff_user')->where('user_id', $express_info['staff_id'])->pluck('name');
        }

        $staff_list = RC_DB::table('staff_user')
            ->where('store_id', $_SESSION['store_id'])
//          	->where('online_status', 1)
            ->get();

        $this->assign('staff_user', $staff_list);
        $this->assign('express_info', $express_info);
        $this->assign('goods_list', $goods_list);
        $this->assign('form_action', RC_Uri::url('express/merchant/assign_express'));

        $this->assign('ur_here', RC_Lang::get('express::express.express_info'));
        $this->assign('action_link', array('href' => RC_Uri::url('express/merchant/shipping_record'), 'text' => RC_Lang::get('express::express.express_list')));

        $this->display('shipping_record_info.dwt');
    }
    
    public function assign_express()
    {
    	$this->admin_priv('express_manage', ecjia::MSGTYPE_JSON);
    
    	$staff_id   = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : 0;
    	$express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
    
    	$express_info = RC_DB::table('express_order')->where('status', '<=', 2)->where('store_id', $_SESSION['store_id'])->where('express_id', $express_id)->first();
    
    	/* 判断配送单*/
    	if (empty($express_info)) {
    		return $this->showmessage('没有相应的配送单！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
    	}
    
    	$staff_user = RC_DB::table('staff_user')->where('store_id', $_SESSION['store_id'])->where('user_id', $staff_id)->first();
    	if (empty($staff_user)) {
    		return $this->showmessage('请选择相应配送员！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
    	}
    
    	$assign_express_data = array('status' => 1, 'staff_id' => $staff_id, 'express_user' => $staff_user['name'], 'express_mobile' => $staff_user['mobile'], 'update_time' => RC_Time::gmtime());
    	RC_DB::table('express_order')->where('store_id', $_SESSION['store_id'])->where('express_id', $express_id)->update($assign_express_data);
    
    	return $this->showmessage('配送单派单成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('express/merchant/record_info', array('express_id' => $express_id))));
    }

    //快递单模板
    public function express_template()
    {
        $this->admin_priv('express_manage');
        $this->assign('ur_here', '快递单模版');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('快递单模版'));

        $this->display('shipping_template_list.dwt');
    }

    private function get_template_list()
    {
        $count = RC_DB::table('shipping_area')
            ->where('store_id', $_SESSION['store_id'])
            ->groupBy('shipping_area_name')
            ->get();
        $count = count($count);
        $page  = new ecjia_merchant_page($count, 10, 5);

        $data = RC_DB::table('shipping_area')
            ->where('store_id', $_SESSION['store_id'])
            ->take(10)
            ->select('shipping_area_name')
            ->skip($page->start_id - 1)
            ->groupBy('shipping_area_name')
            ->orderBy('shipping_area_id', 'desc')
            ->get();

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $shipping_area_list    = RC_DB::table('shipping_area')->where('shipping_area_name', $v['shipping_area_name'])->where('store_id', $_SESSION['store_id'])->get();
                $data[$k]['area_list'] = $shipping_area_list;
                if (!empty($shipping_area_list)) {
                    $shipping_name = '';
                    foreach ($shipping_area_list as $key => $val) {
                        if ($key == 0) {
                            $region_name = RC_DB::table('area_region as a')
                                ->leftJoin('regions as r', RC_DB::raw('a.region_id'), '=', RC_DB::raw('r.region_id'))
                                ->where(RC_DB::raw('a.shipping_area_id'), $val['shipping_area_id'])
                                ->lists(RC_DB::raw('r.region_name'));
                            if (!empty($region_name)) {
                                $data[$k]['shipping_area'] = implode(' | ', $region_name);
                            }
                        }
                        $shipping_name .= RC_DB::table('shipping')->where('shipping_id', $val['shipping_id'])->pluck('shipping_name') . '、';
                    }
                    $data[$k]['shipping_name'] = rtrim($shipping_name, '、');
                }
            }
        }

        return array('item' => $data, 'page' => $page->show(5), 'desc' => $page->page_desc());
    }

    private function get_shipping_list()
    {
        $db = RC_DB::table('shipping as s')
            ->leftJoin('shipping_area as a', function ($join) {
                $join->on(RC_DB::raw('s.shipping_id'), '=', RC_DB::raw('a.shipping_id'))
                    ->where(RC_DB::raw('a.store_id'), '=', $_SESSION['store_id']);
            });
        $shipping_data = $db
            ->groupBy(RC_DB::raw('s.shipping_id'))
            ->orderBy(RC_DB::raw('s.shipping_order'))
            ->selectRaw('s.*, a.shipping_area_id')
            ->where(RC_DB::raw('s.enabled'), 1)
            ->get();

        $plugins = ecjia_config::instance()->get_addon_config('shipping_plugins', true);

        $data = array();
        foreach ($shipping_data as $_key => $_value) {
            if (isset($plugins[$_value['shipping_code']])) {
                $data[$_key]['id']             = $_value['shipping_id'];
                $data[$_key]['code']           = $_value['shipping_code'];
                $data[$_key]['name']           = $_value['shipping_name'];
                $data[$_key]['desc']           = $_value['shipping_desc'];
                $data[$_key]['cod']            = $_value['support_cod'];
                $data[$_key]['shipping_order'] = $_value['shipping_order'];
                $data[$_key]['insure_fee']     = $_value['insure'];
                $data[$_key]['enabled']        = $_value['enabled'];

                /* 判断该派送方式是否支持保价 支持报价的允许在页面修改保价费 */
                $shipping_handle = new shipping_factory($_value['shipping_code']);
                $config          = $shipping_handle->configure_config();

                /* 只能根据配置判断是否支持保价  只有配置项明确说明不支持保价，才是不支持*/
                if (isset($config['insure']) && ($config['insure'] === false)) {
                    $data[$_key]['is_insure'] = false;
                } else {
                    $data[$_key]['is_insure'] = true;
                }
            }
        }

        return $data;
    }
}

// end
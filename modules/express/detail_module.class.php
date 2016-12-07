<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 配送信息列表
 * @author will.chen
 *
 */
class detail_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	
    	if ($_SESSION['admin_id'] <= 0 && $_SESSION['staff_id'] <= 0) {
            return new ecjia_error(100, 'Invalid session');
        }
    	$express_id = $this->requestData('express_id');
    	
    	if (empty($express_id)) {
    		return new ecjia_error( 'invalid_parameter', RC_Lang::get ('system::system.invalid_parameter' ));
    	}
    	$express_order = array();
    	$express_order_db = RC_Model::model('express/express_order_viewmodel');
    	$where = array('staff_id' => $_SESSION['staff_id'], 'express_id' => $express_id);
    	$express_order_info = $express_order_db->join(array('delivery_order', 'order_info'))->where($where)->find();
		
    	$express_order = array(
    			'express_id'	=> $express_order_info['express_id'],
    			'express_sn'	=> $express_order_info['express_sn'],
    			'express_type'	=> $express_order_info['from'] == 'assign' ? '系统派单' : '抢单',
    			'order_sn'		=> $express_order_info['order_sn'],
    			'payment_name'	=> $express_order_info['pay_name'],
    			'express_from_address'	=> '【'.$express_order_info['merchants_name'].'】'. $express_order_info['merchant_address'],
    			'express_from_location'	=> array(
    					'longitude' => $express_order_info['merchant_longitude'],
    					'latitude'	=> $express_order_info['merchant_latitude'],
    			),
    			'express_to_address'	=> $express_order_info['address'],
    			'express_to_location'	=> array(
    					'longitude' => $express_order_info['longitude'],
    					'latitude'	=> $express_order_info['latitude'],
    			),
    			'consignee'		=> $express_order_info['consignee'],
    			'mobile'		=> $express_order_info['mobile'],
    			'order_time'	=> RC_Time::local_date(ecjia::config('time_format'), $express_order_info['add_time']),
    			'pay_time'		=> empty($express_order_info['pay_time']) ? '' : RC_Time::local_date(ecjia::config('time_format'), $express_order_info['pay_time']),
    			'best_time'		=> empty($express_order_info['best_time']) ? '' : RC_Time::local_date(ecjia::config('time_format'), $express_order_info['best_time']),
    			'shipping_fee'	=> $express_order_info['shipping_fee'],
    			'order_amount'	=> $express_order_info['order_amount'],
    	);
    	
		return array('data' => $express_order);

	 }	
}


// end
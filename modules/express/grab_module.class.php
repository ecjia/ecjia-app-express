<?php
use Ecjia\System\Notifications\ExpressGrab;
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 配送抢单列表
 * @author will.chen
 *
 */
class grab_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	if ($_SESSION['admin_id'] <= 0 && $_SESSION['staff_id'] <= 0) {
            return new ecjia_error(100, 'Invalid session');
        }
        
		$location	= $this->requestData('location', array());
		$express_id = $this->requestData('express_id');
		
		if (empty($express_id)) {
			return new ecjia_error('invalid_parameter', RC_Lang::get ('system::system.invalid_parameter' ));
		}
		$where = array('store_id' => $_SESSION['store_id'], 'staff_id' => 0, 'express_id' => $express_id);
		
		$express_order_db = RC_Model::model('express/express_order_model');
		
		$express_order_info = $express_order_db->where($where)->find();
		
		if (!empty($express_order_info)) {
			$result = $express_order_db->where($where)->update(array('staff_id' => $_SESSION['staff_id'], 'from' => 'grab', 'status' => 1));
			
			$orm_staff_user_db = RC_Model::model('express/orm_staff_user_model');
			$user = $orm_staff_user_db->find($_SESSION['staff_id']);
			
			$express_order_viewdb = RC_Model::model('express/express_order_viewmodel');
			$where = array('staff_id' => $_SESSION['staff_id'], 'express_id' => $express_id);
			$field = 'eo.*, oi.add_time as order_time, oi.pay_time, oi.order_amount, oi.pay_name, sf.merchants_name, sf.address as merchant_address, sf.longitude as merchant_longitude, sf.latitude as merchant_latitude';
			$express_order_info = $express_order_viewdb->field($field)->join(array('delivery_order', 'order_info', 'store_franchisee'))->where($where)->find();
			
			$express_data = array(
					'title'	=> '抢单成功',
					'body'	=> '您已成功抢到配送单号，单号为：'.$express_order_info['express_sn'],
					'data'	=> array(
							'express_id'	=> $express_order_info['express_id'],
							'express_sn'	=> $express_order_info['express_sn'],
							'express_type'	=> $express_order_info['from'],
							'label_express_type'	=> $express_order_info['from'] == 'assign' ? '系统派单' : '抢单',
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
							'distance'		=> $express_order_info['distance'],
							'consignee'		=> $express_order_info['consignee'],
							'mobile'		=> $express_order_info['mobile'],
							'order_time'	=> RC_Time::local_date(ecjia::config('time_format'), $express_order_info['add_time']),
							'pay_time'		=> empty($express_order_info['pay_time']) ? '' : RC_Time::local_date(ecjia::config('time_format'), $express_order_info['pay_time']),
							'best_time'		=> empty($express_order_info['best_time']) ? '' : RC_Time::local_date(ecjia::config('time_format'), $express_order_info['best_time']),
							'shipping_fee'	=> $express_order_info['shipping_fee'],
							'order_amount'	=> $express_order_info['order_amount'],
					),
			);
			$express_grab = new ExpressGrab($express_data);
			RC_Notification::send($user, $express_grab);
			
			
			return array();
		} else {
			return new ecjia_error('grab_error', '此单已被抢！');
		}
	 }	
}
// end
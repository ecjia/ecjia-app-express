<?php
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
		
		$where = array('store_id' => $_SESSION['store_id'], 'staff_id' => 0, 'express_id' => $express_id);
		
		$express_order_db = RC_Model::model('express/express_order_model');
		
		$express_order_info = $express_order_db->where($where)->find();
		
		if (!empty($express_order_info) && empty($express_order_info['from'])) {
			$result = $express_order_db->where($where)->update(array('staff_id' => $_SESSION['staff_id'], 'from' => 'grab', 'status' => 1));
			return array();
		} else {
			return new ecjia_error('grab_error', '此单已被抢！');
		}
	 }	
}
// end
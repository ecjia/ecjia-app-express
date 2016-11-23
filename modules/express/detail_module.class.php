<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 配送信息列表
 * @author will.chen
 *
 */
class detail_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	
//     	if ($_SESSION['admin_id'] <= 0 && $_SESSION['staff_id'] <= 0) {
//             return new ecjia_error(100, 'Invalid session');
//         }
		
    	$express_order = array();
		
		return array('data' => $express_order);

	 }	
}
// end
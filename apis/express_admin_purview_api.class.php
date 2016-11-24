<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author 
 *
 */
class express_admin_purview_api extends Component_Event_Api {
    public function call(&$options) {
        $purviews = array(
        	array('action_name' => RC_Lang::get('article::article.article_manage'), 	'action_code' => 'article_manage', 		'relevance' => ''),
        	
        );
        return $purviews;
    }
}
// end
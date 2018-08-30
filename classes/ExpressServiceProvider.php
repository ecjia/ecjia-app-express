<?php

namespace Ecjia\App\Express;

use Royalcms\Component\App\AppParentServiceProvider;

class ExpressServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-express', null, dirname(__DIR__));
    }
    
    public function register()
    {
        
    }
    
    
    
}
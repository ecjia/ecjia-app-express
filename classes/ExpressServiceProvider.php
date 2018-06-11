<?php

namespace Ecjia\App\Express;

use Royalcms\Component\App\AppServiceProvider;

class ExpressServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-express');
    }
    
    public function register()
    {
        
    }
    
    
    
}
<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $savedCouponsCount = 0;
        
        if (Auth::check()) {
            $savedCouponsCount = Auth::user()->savedCoupons()->count();
        }
        
        $view->with('savedCouponsCount', $savedCouponsCount);
    }
}

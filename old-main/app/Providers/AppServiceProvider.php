<?php

namespace App\Providers;

use App\Services\OtApiService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CategoryService;

use App\Models\Coupon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
         View::share('globalCoupon', $this->getActiveCoupon());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
    private function getActiveCoupon()
{
    $coupon = Coupon::where('type', 'all_product_base')
        ->where('status', 1)
        ->first();

    if ($coupon) {
        $currentDate = \Carbon\Carbon::now(); // Get current date using Carbon
        $startDate = \Carbon\Carbon::parse($coupon->start_date); // Parse start_date with Carbon
        $endDate = \Carbon\Carbon::parse($coupon->end_date); // Parse end_date with Carbon

        // Check if the current date is within the start and end date range
        if ($currentDate->between($startDate, $endDate)) {
            return $coupon;
        }
    }
    
    return null; // Return null if no valid coupon is found
}

}

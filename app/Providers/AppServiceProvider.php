<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Tickets;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {                   
        //
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //  if (env('APP_ENV') !== 'local') {
        //     URL::forceScheme('https');
        // }

    //     View::composer('*', function ($view) {
    //     if (auth()->check()) {
    //         $openTicketCount = Tickets::where('user_id', auth()->id())
    //             ->where('status', 'Open')
    //             ->count();

    //         $view->with('openTicketCount', $openTicketCount);
    //     }
    // });
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $openTicketCount = Tickets::where('user_id', auth()->id())
                    ->where('status', 'Open')
                    ->count();

                $view->with('openTicketCount', $openTicketCount);
            }
        });
    }
      if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

    }
}



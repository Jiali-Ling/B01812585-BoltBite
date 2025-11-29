<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }
    public function boot(): void
    {
        if (request()->isSecure()) {
            URL::forceScheme('https');
        }
        
        $host = request()->getHost();

        if ($host === 'b01812585-uws24.duckdns.org') {
            URL::forceRootUrl('https://' . $host);
        } 
        elseif (str_ends_with($host, '.compute-1.amazonaws.com')) {
            URL::forceRootUrl('https://' . $host);
        }
        else {
            $appUrl = config('app.url');
            if ($appUrl && str_starts_with($appUrl, 'https://')) {
                URL::forceRootUrl($appUrl);
            }
        }
    }
}

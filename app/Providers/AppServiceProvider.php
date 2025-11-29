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
        $allowedHosts = [
            'b01812585-uws24.duckdns.org',
            'ec2-54-227-100-75.compute-1.amazonaws.com',
            'ec2-54-226-83-133.compute-1.amazonaws.com',
        ];
        
        if (in_array($host, $allowedHosts)) {
            URL::forceRootUrl('https://' . $host);
        } else {
            $appUrl = config('app.url');
            if ($appUrl && str_starts_with($appUrl, 'https://')) {
                URL::forceRootUrl($appUrl);
            }
        }
    }
}

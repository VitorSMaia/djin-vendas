<?php

namespace App\Services\Logging;

use App\Services\Logging\Providers\CloudWatchLogger;
use App\Services\Logging\Providers\LocalStorageLogger;
use Illuminate\Support\Facades\App;

class LoggerFactory
{
    public static function create(): LoggerInterface
    {
        $provider = env('LOG_PROVIDER', App::environment('production') ? 'cloudwatch' : 'local');

        return match ($provider) {
            'cloudwatch' => new CloudWatchLogger(),
            'local' => new LocalStorageLogger(),
            default => new LocalStorageLogger(),
        };
    }
}

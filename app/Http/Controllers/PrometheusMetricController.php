<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;

class PrometheusMetricController extends Controller
{
    protected $registry;

    public function __construct()
    {
        $this->registry = new CollectorRegistry(new Redis(['host' => env('REDIS_HOST', 'redis'), 'password' => env('REDIS_PASSWORD', 'redis_password')]));
    }

    public function incrementRequestCount()
    {
        $counter = $this->registry->getOrRegisterCounter('app', 'requests_total', 'Total number of requests');
        $counter->inc();
    }

    public function observeResponseTime($time)
    {
        $histogram = $this->registry->getOrRegisterHistogram('app', 'response_time_seconds', 'Response time', ['method']);
        $histogram->observe($time);
    }
}

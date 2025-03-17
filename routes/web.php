<?php

// 프로메테우스 메트릭
use App\Http\Controllers\PrometheusMetricController;
use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

Route::get('/metrics', function () {
    $registry = app(CollectorRegistry::class);
    $renderer = new RenderTextFormat();

    return response($renderer->render($registry->getMetricFamilySamples()))
        ->header('Content-Type', RenderTextFormat::MIME_TYPE);
});

Route::get('/metrics/increment', [PrometheusMetricController::class, 'incrementRequestCount']);
Route::get('/metrics/observe/{time}', [PrometheusMetricController::class, 'observeResponseTime']);

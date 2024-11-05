<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

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
    public function boot(): void
    {
        Storage::extend(
            'cloudinary',
            function ($app, $config) {

                $cloudinaryAdapter = new CloudinaryAdapter(config('cloudinary.cloud_url'));

                return new FilesystemAdapter(
                    new Filesystem($cloudinaryAdapter, $config),
                    $cloudinaryAdapter,
                    $config
                );
            }
        );
    }
}

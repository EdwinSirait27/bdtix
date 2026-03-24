<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Client;
use Google\Service\Drive;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\Filesystem;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Storage::extend('google', function ($app, $config) {
            $client = new Client();

            // Ganti dari OAuth2 ke Service Account
            $client->setAuthConfig(base_path($config['serviceAccountJson']));
            $client->setScopes([Drive::DRIVE]);

            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder']);

            return new Filesystem($adapter);
        });
    }
}
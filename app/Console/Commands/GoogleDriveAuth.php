<?php

namespace App\Console\Commands;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Console\Command;

class GoogleDriveAuth extends Command
{
    protected $signature = 'google:auth';
    protected $description = 'Generate Google Drive refresh token';

    public function handle()
    {
        $client = new Client();
        $client->setClientId(config('filesystems.disks.google.clientId'));
        $client->setClientSecret(config('filesystems.disks.google.clientSecret'));
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $authUrl = $client->createAuthUrl();
        $this->info('Buka URL ini di browser:');
        $this->line($authUrl);
        $code = $this->ask('Masukkan authorization code');
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['refresh_token'])) {
            $this->info('Refresh token berhasil didapat:');
            $this->line($token['refresh_token']);
            $this->info('Simpan ke .env: GOOGLE_DRIVE_REFRESH_TOKEN=' . $token['refresh_token']);
        } else {
            $this->error('Gagal mendapat refresh token: ' . json_encode($token));
        }
    }
}
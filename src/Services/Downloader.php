<?php

namespace ShibuyaKosuke\LaravelDompdfFont\Services;

use Composer\Downloader\ZipDownloader;
use Composer\Package\Archiver\ZipArchiver;
use Composer\Util\Zip;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;

/**
 * Class Downloader
 * @package ShibuyaKosuke\LaravelDompdfFont\Services
 */
class Downloader
{
    /**
     * @param $url
     * @param $destination
     * @return bool|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function exec($url, $destination)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() != 200) {
            return false;
        }
        $mimeType = $response->getHeaderLine('content-type');

        !File::exists($destination) && File::makeDirectory($destination);

        if ($mimeType == 'application/zip') {
            $zip = $destination . DIRECTORY_SEPARATOR . basename($url);
            File::put($zip, $response->getBody());
            return $zip;
        }
    }

    /**
     * @param string $path
     * @param string $destination
     * @param bool $clear_zip
     */
    public static function extract(string $path, string $destination, bool $clear_zip = true)
    {
        $zip = new \ZipArchive();
        $zip->open($path) && $zip->extractTo($destination) && $zip->close();

        $pathinfo = pathinfo($path);
        $directory = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'];
        $files = File::glob($directory . '/*.ttf');
        foreach ($files as $file) {
            File::move($file, $pathinfo['dirname'] . DIRECTORY_SEPARATOR . basename($file));
        }

        if ($clear_zip) {
            File::deleteDirectory($directory);
            File::delete($path);
        }
    }
}
<?php

namespace ShibuyaKosuke\LaravelDompdfFont\Console;

use Illuminate\Console\Command;
use ShibuyaKosuke\LaravelDompdfFont\Services\Downloader;

/**
 * Class DompdfFont
 * @package ShibuyaKosuke\LaravelDompdfFont\Console
 */
class DompdfFont extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:pdf-font';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Japanese fonts for DomPdf.';

    /**
     * Exec command
     */
    public function handle()
    {
        $url = config('pdf_font.fonts.ipaex');
        $destination = config('pdf_font.destination.dompdf');
        $path = Downloader::exec($url, $destination);
        if ($path !== false) {
            Downloader::extract($path, $destination);
        }
    }
}
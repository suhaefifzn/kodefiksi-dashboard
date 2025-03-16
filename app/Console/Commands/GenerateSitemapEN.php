<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

use App\Services\ArticleService;

class GenerateSitemapEN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap-en';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap EN version from api.kodefiksi.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $sitemap = Sitemap::create();
            $url = config('app.env') === 'production'
                ? 'https://kodefiksi.com/'
                : 'http://public.kodefiksi.test/';

            // articles from API
            $articleService = new ArticleService();
            $response = $articleService->getSlugs('en');
            $slugs = $response->getData('data')['data']['slugs'];

            foreach ($slugs as $slug) {
                $lastmod = Carbon::parse($slug['updated_at']);
                $sitemap->add(Url::create($url . $slug['slug'])
                    ->setLastModificationDate($lastmod)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
                );
            }

            // Simpan sitemap ke folder laravel public.kodefiksi
            $publicHtmlPath = config('app.env') === 'production'
                ? realpath(__DIR__ . '/../../../../../public_html/dashboard.kodefiksi/sitemap_test.xml')
                : realpath(__DIR__ . '/../../../public/sitemap_test.xml');
            $sitemap->writeToFile($publicHtmlPath);

            $this->info('Sitemap EN version successfully generated.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
        }
    }
}

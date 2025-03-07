<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

use App\Services\ArticleService;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for public kodefiksi.com';

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
            $response = $articleService->getSlugs();
            $slugs = $response->getData('data')['data']['slugs'];

            foreach ($slugs as $slug) {
                $lastmod = Carbon::parse($slug['updated_at']);
                $sitemap->add(Url::create($url . $slug['slug'])
                    ->setLastModificationDate($lastmod)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
                );
            }

            // Simpan sitemap ke file
            $publicHtmlPath = config('app.env') === 'production'
                ? realpath(__DIR__ . '/../../../../../public_html/sitemap-articles.xml')
                : realpath(__DIR__ . '/../../../../public.kodefiksi/public/sitemap-articles.xml');
            $sitemap->writeToFile($publicHtmlPath);

            $this->info('Sitemap generated successfully.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
        }
    }
}

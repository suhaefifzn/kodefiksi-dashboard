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
            $url = 'https://kodefiksi.com/';

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

            // statis URLs
            $sitemap->add(
                Url::create("{$url}author/suhaefi21")
                    ->setPriority(1)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
            $sitemap->add(Url::create($url)->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            $sitemap->add(
                Url::create("{$url}about")
                    ->setPriority(0.4)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
            $sitemap->add(
                Url::create("{$url}contact")
                    ->setPriority(0.4)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
            $sitemap->add(
                Url::create("{$url}disclaimer")
                    ->setPriority(0.4)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
            $sitemap->add(
                Url::create("{$url}privacy-policy")
                    ->setPriority(0.4)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
            $sitemap->add(
                Url::create("{$url}category/anime")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
            $sitemap->add(
                Url::create("{$url}category/game")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
            $sitemap->add(
                Url::create("{$url}category/pemrograman")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );

            // Simpan sitemap ke file
            $publicHtmlPath = realpath(__DIR__ . '/../../../../../public_html/sitemap.xml');
            $sitemap->writeToFile($publicHtmlPath);

            $this->info('Sitemap generated successfully.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ' on line ' . $e->getLine());
        }
    }
}

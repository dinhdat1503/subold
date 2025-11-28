<?php

namespace App\Console\Commands\Website;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generator Sitemap XML';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $sitemap = Sitemap::create();
            $sitemap->add(
                Url::create(url('/guest/home'))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
            $services = \App\Models\Service::with('social')->get();
            foreach ($services as $service) {
                if (!$service->social || !$service->slug) {
                    continue;
                }
                $url = url("/guest/{$service->social->slug}/{$service->slug}");
                $sitemap->add(
                    Url::create($url)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($service->updated_at ?? now())
                );
            }
            $sitemap->writeToFile(public_path('sitemap.xml'));
            $this->info('Sitemap đã được tạo thành công tại: public/sitemap.xml');
            return self::SUCCESS;
        } catch (\Exception $e) {
            \Log::warning('Sitemap Generation failed', [
                'error' => $e->getMessage(),
            ]);
            $this->error('Sitemap Generation failed');
            return self::FAILURE;
        }
    }
}

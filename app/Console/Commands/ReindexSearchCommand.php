<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Console\Command;

class ReindexSearchCommand extends Command
{
    protected $signature = 'search:reindex
                            {--model= : Reindex a specific model (business, product, service)}
                            {--fresh : Wipe all indexes before reindexing}';

    protected $description = 'Reindex all searchable models into the search index';

    public function handle(): int
    {
        $model = $this->option('model');
        $fresh = $this->option('fresh');

        if ($fresh) {
            $this->info('Wiping search indexes...');
            Business::removeAllFromSearch();
            Product::removeAllFromSearch();
            Service::removeAllFromSearch();
        }

        $models = match ($model) {
            'business' => ['businesses' => Business::class],
            'product' => ['products' => Product::class],
            'service' => ['services' => Service::class],
            default => [
                'businesses' => Business::class,
                'products' => Product::class,
                'services' => Service::class,
            ],
        };

        foreach ($models as $name => $class) {
            $this->info("Indexing {$name}...");

            $count = 0;
            $class::query()
                ->with(['category'])
                ->chunk(200, function ($records) use (&$count, $name) {
                    foreach ($records as $record) {
                        $record->searchable();
                        $count++;
                    }
                    $this->line("  Indexed {$count} {$name}...");
                });

            $this->info("  Done: {$count} {$name} indexed.");
        }

        $this->info('Search reindex complete.');

        return Command::SUCCESS;
    }
}

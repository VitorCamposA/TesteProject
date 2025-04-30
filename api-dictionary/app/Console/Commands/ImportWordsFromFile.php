<?php

namespace App\Console\Commands;

use App\Services\WordService;
use Illuminate\Console\Command;

class ImportWordsFromFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Words from the File of Dictionary Words';

    protected WordService $wordService;

    public function __construct(WordService $wordService)
    {
        parent::__construct();
        $this->wordService = $wordService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting word import...');

        $filePath = base_path('words_dictionary.json');
        
        if (!file_exists($filePath)) {
            $this->error('Dictionary file not found at: ' . $filePath);
            return;
        }

        $this->info('Reading dictionary file...');
        $jsonContent = file_get_contents($filePath);
        $words = json_decode($jsonContent, true);
        
        $this->info('Importing words...');
        $progressBar = $this->output->createProgressBar(count($words));
        $progressBar->start();

        $batchSize = 1000;
        $wordBatch = [];
        $importedCount = 0;

        foreach ($words as $word => $value) {
            $wordBatch[] = ['word' => $word];
            $importedCount++;

            if (count($wordBatch) >= $batchSize) {
                $this->wordService->createMultipleWords($wordBatch);
                $wordBatch = [];
                $progressBar->advance($batchSize);
            }
        }

        // Import any remaining words
        if (!empty($wordBatch)) {
            $this->wordService->createMultipleWords($wordBatch);
            $progressBar->advance(count($wordBatch));
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info("Successfully imported {$importedCount} words.");
    }
}

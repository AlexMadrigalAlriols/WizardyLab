<?php

namespace App\Console\Commands;

use App\Models\Portal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteTmpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmp_files:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all temporal files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $files = Storage::files('temp');

            foreach ($files as $file) {
                Storage::delete($file);
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}

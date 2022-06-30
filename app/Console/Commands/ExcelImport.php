<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\Import;

class ExcelImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill database with data from excel file in the root';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$this->output->title('Starting import');
		(new Import)->withOutput($this->output)->import('data.xlsx');
		$this->output->success('Import successful');
        return 0;
    }
}

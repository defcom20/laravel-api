<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteTokenUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_token:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para eliminar todas los token de los usuarios que han iniciado sesion durante el dia';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //$schedule->call(function () {
            DB::table('personal_access_tokens')->delete();
        //})->dailyAt('13:21');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Mail\RegisterationMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'new user registered';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $now=Carbon::now();
        // $users=User::where('created_at','>=',$now)->get();
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();
        $users=User::whereBetween('created_at',[$startOfDay,$endOfDay])->get();
        if ($users->isEmpty()) {
            $this->info('No new users registered today.');
            return;
        }
        foreach($users as $user){
                    Mail::to('shahshan@nextgeni.com')
            ->cc(['shahshan871@gmail.com'])
            ->send(new RegisterationMail($user));
        }
    }
}

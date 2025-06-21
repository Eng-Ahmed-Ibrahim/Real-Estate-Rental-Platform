<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Events\NotifyMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Notifications\LocationNotification;

class SendMessageNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users=User::
        // where("job_title","Sales")
        where("id",85)
        // ->where("location_updated_at",'<',now()->subHour())
        ->get();
        foreach($users as $user ){

            $user->update([
                'notification_time' => now(),
            ]);
            Http::get('https://system.tqnia.me/index.php/ApiNotifications/pushNotificationToMobile', [
                'user_id' => $user->id,
                "model_type"=>"Tracking",
                "model_id"=>110302456,
    
            ]);
            // $user->notify(new LocationNotification($data));
            // event(new NotifyMessage($data,$user->id));
        }

    }
}


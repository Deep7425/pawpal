<?php

namespace App\Console\Commands;

use App\Models\NotificationSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\SendUserNotification;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send scheduled notifications where status is 1';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = now();

        NotificationSchedule::where('status', 1)
            ->where('from_date', '<=', $now)
            ->where('to_date', '>=', $now)
            ->chunk(1000, function ($notifications) use ($now) {
                foreach ($notifications as $notification) {
                    $shouldRun = false;
                    Log::info('Processing notification', [$notification]);
                    Log::info('ina', [$notification->schedule_type]);
                    switch ($notification->schedule_type) {
                        case 1: // One Time In a Day
                            Log::info("in 1");
                            $shouldRun = !empty($notification->last_run_date) ? $now->diffInDays($notification->last_run_date) >= 1 : true;
                            break;
                        case 2: // One Time In Two Days
                            Log::info("in 2");
                            $shouldRun = $now->diffInDays($notification->last_run_date) >= 2;
                            break;
                        case 3: // One Time In Three Days
                            Log::info("in 3");
                            $shouldRun = $now->diffInDays($notification->last_run_date) >= 3;
                            break;
                        case 4: // One Time In a Week
                            Log::info("in 4");
                            $shouldRun = $now->diffInDays($notification->last_run_date) >= 7;
                            break;
                        case 5:
                            $shouldRun = !empty($notification->last_run_date) ? $now->diffInHours($notification->last_run_date) >= 12 : true;
                            break;
                    }
                    
                    Log::info('Should run:', [$shouldRun]);

                    if ($shouldRun) {
                        $notification->save();
                        // $this->dispatchNotificationJobs($notification);
						$notification->last_run_date = $now;
                        
                        
                    }
                }
            });

        $this->info('Scheduled notifications sent successfully.');
    }

    protected function dispatchNotificationJobs($notification)
    {
        $notifiedUsers = []; // Array to track notified users
        $userQuery = User::with(['UsersSubscriptions'])
            ->select(['id','pId','fcm_token','device_type'])
            ->where('parent_id', 0)
            ->whereIn('id', [595746])
            ->where('notification_status', 1)
            ->whereDate('created_at', '>=', '2022-01-01')
            ->whereNotNull('fcm_token');
        
        if ($notification->start_date && $notification->end_date) {
            $userQuery->whereDate('created_at', '>=', $notification->start_date)->whereDate('created_at', '<=', $notification->end_date);
        }
        Log::info("userQuery", [$userQuery->get()]);

        switch ($notification->notification_type) {
            case 1: // Subscribed
                $userQuery->whereHas('UsersSubscriptions', function($query) {
                    $query->where('order_status', 1);
                });
            break;
            case 2: // Unsubscribed
                $userQuery->whereHas('UsersSubscriptions', function ($query) {
                    $query->where('order_status', 0);
                });
			break;
			case 3: // Appt
                $userQuery->whereNotNull('pId');
			break;
			case 4: // No Appt
                $userQuery->whereNull('pId');
			break;
        }

        $userQuery->chunk(1000, function ($users) use ($notification, &$notifiedUsers) {
            $fcmTokenAndroid = [];
            $fcmTokenIos = [];
            foreach ($users as $user) {
                if (in_array($user->id, $notifiedUsers)) {
                    continue; // Skip if already notified
                }				
				if ($user->device_type == 1 || $user->device_type == 3) {
					$fcmTokenAndroid[] = $user->fcm_token;
				} else {
					$fcmTokenIos[] = $user->fcm_token;
				}
				$notifiedUsers[] = $user->id;
            }
            if (!empty($fcmTokenAndroid)) {
                SendUserNotification::dispatch($fcmTokenAndroid, $notification->title, $notification->content, $notification->image, 'android', $notification->application_page, $notification->type_id,$notification->n_type,$notification->id);
            }
            if (!empty($fcmTokenIos)) {
                SendUserNotification::dispatch($fcmTokenIos, $notification->title, $notification->content, $notification->image, 'ios', $notification->application_page, $notification->type_id,$notification->n_type,$notification->id);
            }
        });
		Log::info('Total Users',[count($notifiedUsers)]);
        $notification->status = 1; // Mark as processed
        $notification->save();
    }
}
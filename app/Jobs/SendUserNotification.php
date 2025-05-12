<?php
namespace App\Jobs;

use App\Services\NotificationService;
use App\Events\JobCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendUserNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fcmTokens;
    protected $title;
    protected $message;
    protected $image;
    protected $deviceType;
    protected $applicationPage;
    protected $typeId;
    protected $nType;
    // public $userId;
    // public $user;
    public $notificationId;

    public function __construct($fcmTokens, $title, $message, $image, $deviceType, $applicationPage, $typeId, $nType,$notificationId)
    {
        $this->fcmTokens = $fcmTokens;
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
        $this->deviceType = $deviceType;
        $this->applicationPage = $applicationPage;
        $this->typeId = $typeId;
        $this->nType = $nType;
		$this->notificationId = $notificationId;
        // $this->userId = $userId;
        // $this->user = $user;
    }

    public function handle()
    {
        $notificationService = new NotificationService();

        if ($this->deviceType === 'ios') {
            $result = $notificationService->sendNotificationIos($this->fcmTokens, $this->title, $this->message, $this->image, $this->deviceType, $this->applicationPage, $this->typeId, $this->nType);
        } else {
            $result = $notificationService->sendBulkNotification($this->fcmTokens, $this->title, $this->message, $this->image, $this->deviceType, $this->applicationPage, $this->typeId, $this->nType);
            
        }


        Log::info('Notification sent', ['result' => $result]);

        // Fire event with the job data and result
        event(new JobCompleted($this, $result));
    }
}
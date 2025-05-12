<?php

namespace App\Events;

use App\Models\NotificationScheduleResults;
use Carbon\Carbon;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class JobCompleted
{
    use Dispatchable, SerializesModels;

    public $job;
    public $result;

    public function __construct($job, $result)
    {
        $this->job = $job;
        $this->result = $result;
    }

    public function handle()
    {
        Log::info('result', [$this->job]);
        Log::info('result', [$this->result]);
        $job = $this->job;
        $result = $this->result;

        $resultArray = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to decode JSON result', ['result' => $result, 'error' => json_last_error_msg()]);
            return;
        }
        $results = [
            // 'user_id' => $job->userId,
            // 'user_data' => $job->user,
            'status' => $resultArray['success'] ? 'success' : 'failure',
            'error_message' => $resultArray['success'] ? null : $resultArray['error'],
            'meta_data' => $resultArray,
        ];

        Log::info('result', [$resultArray]);
        Log::info('job', [$job]);
        $today = Carbon::today()->toDateString();

        $existingResult = NotificationScheduleResults::where('notification_schedule_id', $job->notificationId)
            ->whereDate('created_at', $today)
            ->orWhereDate('updated_at', $today)
            ->first();

        if ($existingResult) {
            // Append the new result to the existing results
            $existingResultsArray = json_decode($existingResult->result, true);
            $existingResultsArray[] = $results;
            $existingResult->result = json_encode($existingResultsArray);
            $existingResult->save();
        } else {
            Log::info('Creating new NotificationScheduleResults', [
                'notification_schedule_id' => $job->notificationId,
                'result' => json_encode([$results]),
            ]);

            $data = [
                'notification_schedule_id' => $job->notificationId,
                'result' => json_encode([$results]),
            ];
            Log::info('Data to be created', $data);
            NotificationScheduleResults::create($data);
        }

        // You can store or further process $results here as needed
    }
}

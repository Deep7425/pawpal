<?php

namespace App\Listeners;

use App\Events\JobCompleted;
use App\Models\NotificationScheduleResults;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class StoreJobResult
{
    public function handle(JobCompleted $event)
    {
        Log::info('result', [$event->job]);
        Log::info('result', [$event->result]);
        $job = $event->job;
        $result = $event->result;

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

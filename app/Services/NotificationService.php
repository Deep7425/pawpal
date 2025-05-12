<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendBulkNotification($fcmTokens, $title, $message, $image, $deviceType, $applicationPage, $typeId,$nType)
    {
        // Log::info('Method reached', [
        // 'fcmTokens' => $fcmTokens,
        // 'title' => $title,
        // 'message' => $message,
        // 'deviceType' => $deviceType
        // ]);
        $msg = array(
            'body'  => $message,
            'title'  => $title,
            'vibrate' => 1,
            'lights'=>1,
            'image' =>  !empty($image) ? "https://www.healthgennie.com/public/notification-icons/".$image : null,
            'subtitle' => $subtitle ?? '',
            'tickerText' => $tickerText ?? '',
            'foreground' => 1,
            'priority'=>10,
            'icon'=>"notification_icon",
            'color'=> '#14bef0',
            'forceShow'=> 1,
            'pushNotification'=>true
        );
        // Log::info('application page android', [$applicationPage]);

        if ($applicationPage === 'doctor-detail') {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'user_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'buy-plan') {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'plan_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'feedback') {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'user_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'symptom-detail') {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'symptom_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'offer-desc') {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'lab_id'=> $typeId,
                )
            );
        }  else {
            $fields = array(
                'registration_ids'  => $fcmTokens,
                'notification'   => $msg,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType
                )
            );
        }


        $headers = array(
            'Authorization: key= AAAAKfEmcIY:APA91bFnCFD66QXU6DDdOkZ_dVGyCltf72teyb0hi5ifstB27TbIIQACNMhUDwcTx9TZLUPFzRqideyjAI1AlWWYmpS9FQl71AdkeJhHbicnrwTJA2DKMaOyNteels-sxWtMfsPOgHAP',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        Log::info('Android', [$result]);

        return strip_tags($result);
    }
    public function sendNotificationIos($fcmTokens, $title, $message, $image, $deviceType, $applicationPage, $typeId,$nType) {
        $token = implode($fcmTokens);
        // Log::info('application page IOS', [$applicationPage]);
        $notification = array(
            'body'  => $message,
            'title'  => $title,
            'vibrate' => 1,
            'lights'=>1,
            'visibility'=> 1,
            //'soundname'=> 'kaps',
            'image' =>  !empty($image) ? "https://www.healthgennie.com/public/notification-icons/".$image : null,
            'subtitle' => '',
            'tickerText' =>$tickerText ?? '',
            'foreground' => 1,
            'priority'=>10,
            "icon"=>"notification_icon",
            "color"=> '#14bef0',
            "forceShow"=> 1,
            "pushNotification"=>true
        );
//        $arrayToSend = array(
//            'to' => $fcmTokens,
//            'notification' => $notification,
//            'priority'=>'high',
//            'notification_foreground'=>true,
//            'data' => array(
//            'page'=> $applicationPage ?? '',
//            'type'=> $nType,
//            'user_id'=> 47210,
////            'user_id'=> $pageIds['user_id'] ?? null,
//            'plan_id'=> $pageIds['plan_id'] ?? null,
//            'lab_id'=> $pageIds['lab_id'] ?? null,
//            'symptom_id'=> $pageIds['symptom_id'] ?? null,
//        )
//        );
        if ($applicationPage === 'doctor-detail') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'user_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'buy-plan') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'plan_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'feedback') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'user_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'symptom-detail') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'symptom_id'=> $typeId,
                )
            );
        } elseif ($applicationPage == 'offer-desc') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,
                    'lab_id'=> $typeId,
                )
            );
        }/* elseif ($applicationPage == 'psyAppData') {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType,

                )
            );
        }*/ else {
            $arrayToSend = array(
                'to' => $token,
                'notification' => $notification,
                'priority'=>'high',
                'notification_foreground'=>true,
                'data' => array(
                    'page'=> $applicationPage ?? '',
                    'type'=> $nType
                )
            );
        }
        Log::info($arrayToSend);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: key= AAAAKfEmcIY:APA91bFnCFD66QXU6DDdOkZ_dVGyCltf72teyb0hi5ifstB27TbIIQACNMhUDwcTx9TZLUPFzRqideyjAI1AlWWYmpS9FQl71AdkeJhHbicnrwTJA2DKMaOyNteels-sxWtMfsPOgHAP',
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($arrayToSend) );
        $result = curl_exec($ch);
        curl_close($ch);
        // Log::info('IOS', [$result]);
        return strip_tags($result);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AgoraToken\RtcTokenBuilder;
use App\Events\VideoCallInitiated;
use App\Models\NotifyUserSms;
use App\Http\Controllers\FcmNotificationService;
use App\Http\Controllers\FcmNotificationServicePP;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\VideoCall;

class AgoraController extends Controller
{
    

  public function acceptVc(Request $request){
       
    $dataReq = $request->all();

    
    $userId =  base64_decode($dataReq['pId']);
    $docId = base64_decode($dataReq['doc_id']);

    // $data = VideoCall::where('caller_id' , $docId )->where('receiver_id' , $userId )->where('status' , 'calling')->latest('created_at')->first();

    $data = VideoCall::where('caller_id', $docId)
    ->where('receiver_id',  $userId )
    ->where('status', 'calling')
    ->latest('created_at')
    ->first();
    $doctors = DoctorsInfo::where('user_id', $docId)->first();

    


  

     return view('videoCall.videocall' , compact('data', 'doctors'));

}
    
    // public function acceptedCall(Request $request)
    // {
    //     $data = $request->all();


    //     $channelName = $request->input('channel');
    //     $token = $request->input('token');
  
    //     $call = VideoCall::where('channel_name', $channelName)->where('receiver_id' , $data['userId'])->where('caller_id' , $data['callerId'])->first();

    //     if ($call) {
    //         $call->status = 'accepted';
    //         $call->save();

    
    //         return response()->json(['success' => true, 'message' => 'Call status updated successfully.']);
    //     }
    //        return response()->json(['success' => false, 'message' => 'Call not found.']);
    // }

    
  public function videoCall(Request $request)
  {
    $data = $request->all();
    $appointmentId = base64_decode($data['id']);
    $patientId = base64_decode($data['pId']);
    $docId = base64_decode($data['docId']);

    $appID = '238bf66b0d5747eda944eedc110c227e';  // Replace with your Agora App ID
    $appCertificate = 'c3f56c981c0c40c1ab78405dc5d83859';  // Replace with your Agora App Certificate
    $channelName = 'agoranew';  // Use a dynamic or meaningful channel name

    // Fetch appointment details
    $appointment = Appointments::with(['Patient', 'User.DoctorInfo'])
        ->where(['id' => $appointmentId, 'delete_status' => 1])
        ->first();

    if (!$appointment) {
        return response()->json(['error' => 'Invalid appointment'], 400);
    }


    NotifyUserSms::create([
        'patient_id' => $patientId,
        'apptId' => $appointmentId,
        'doc_id' => $docId
    ]);

    

    $userData = Auth::user();
    $doc = DoctorsInfo::where('user_id', $userData->id)->first();
    $doctorName = $doc->first_name;

    // Generate a unique UID for this session
    $uid =  rand(100000, 999999);  // Ensure it's a unique integer

    // Token role: Use RolePublisher for broadcasters
    $role = RtcTokenBuilder::RolePublisher;

    // Token expiration (24 hours)
    $expireTimeInSeconds =  3600;
    $currentTimestamp = now()->timestamp;
    $privilegeExpireTs = $currentTimestamp + $expireTimeInSeconds;
   
  
$token = '007eJxTYFi7toerQyTp0j3DphVLzDK3nlA4tuLRkZKEgl8CO8pnLy9RYDAytkhKMzNLMkgxNTcxT01JtDQxSU1NSTY0NEg2MjJP/ecSm94QyMiQI72KiZEBAkF8DobE9PyixLzUcgYGAE7RIo0=';
  
    // Log for debugging
    Log::info('App ID:', ['doctorName' => $doctorName]);

    Log::info('App ID:', ['appID' => $appID]);
    Log::info('App Certificate:', ['appCertificate' => $appCertificate]);
    Log::info('Generated Token:', ['token' => $token]);



    $doctor = User::where('id', $docId)->first();
    $doc_name = @$doctor->doctorInfo->first_name . ' ' . @$doctor->doctorInfo->last_name;
    $user = Users::select(['first_name', 'last_name', 'fcm_token', 'device_type', 'mobile_no'])->where(['pId' => $patientId])->first();

    $doctorInfo = null;
    if ($appointment && $appointment->User && $appointment->User->DoctorInfo) {
      $doctorInfo = $appointment->User->DoctorInfo->where('user_id', $docId)->first();
    }
    $message = '🎥 Incoming Call 🎥 Join Now';
    $title = 'Dr. ' . @$doc_name . '';
    $subtitle = 'Incoming Call';
    $tickerText = 'Incoming Call';
    $today_date = date('Y-m-d');
    $current_time = date('H:i');
    $fcm_token = $user->fcm_token;
    $device_type = $user->device_type;
    $msg1 = 'Dear ' . @$user->first_name . ' ' . @$user->last_name . ' Dr. ' . @$doc_name . ' sent you a voice call request for consultation.Please open my appointment section in health gennie app and connect the voice call. Thanks Team health gennie.';
    // $this->sendSMS($user->mobile_no, urlencode($msg1), '1707162313087231202');
    if (!empty($fcm_token)) {
      Log::info('registrationIds======================');
      $result = $this->sendNotificationPPIn('', [$fcm_token], $message, $title, $subtitle, $tickerText, 'voice', null, '1', true);
      Log::info('===========================',[$result]);
    }

    $doctorFirstName = $doctorInfo ? $doctorInfo->first_name : 'N/A';
    $doctorLastName = $doctorInfo ? $doctorInfo->last_name : 'N/A';
    $fullName = $doctorFirstName . ' ' . $doctorLastName;

    // $this->initiateCall($patientId, $docId, $token);

    return view('videocall.videocall', compact('appointmentId', 'appID', 'token', 'channelName', 'patientId', 'doctorFirstName', 'doctorLastName'));
  }

  public function acceptCall(VideoCall $videoCall)
  {
    $videoCall->update(['status' => 'accepted']);
    $this->triggerPusherEvent($videoCall);
    return response()->json(['status' => 'accepted']);
  }

  public function rejectCall(VideoCall $videoCall)
  {
    $videoCall->update(['status' => 'rejected']);
    $this->triggerPusherEvent($videoCall);
    return response()->json(['status' => 'rejected']);
  }

  public function endCall(Request $request)
  {
    $data = $request->all();
    $videoCall = videoCall::where('token', $data['agoraToken'])->update(['status' => 'ended']);
    return 1;
  }

  public function cancelCall(Request $request)
  {
    $videoCall = VideoCall::where('caller_id', auth()->id())
      ->where('status', 'calling')
      ->first();

    if ($videoCall) {
      $videoCall->status = 'cancelled';
      $videoCall->save();

      // Notify the patient about call cancellation
      $this->sendFirebaseNotification($videoCall->receiver_id, $videoCall);
    }

    return response()->json(['success' => true]);
  }

//   private function sendFirebaseNotification11($pId, $videoCall)
//   {
//     $patient = App\Models\pp\User::where('patient_number', $pId)->first();
//     $user = Auth::user();
//     $doc = DoctorsInfo::where('user_id', $user->id)->first();
//     $fireBaseCredintal = (new Factory)->withServiceAccount(storage_path('pplivev10-firebase-adminsdk-w02bb-80fa4017ff.json'));
//     $messaging = $fireBaseCredintal->createMessaging();
//     $message = CloudMessage::fromArray([
//       'topic' => $pId,
//       'notification' => [
//         'title' => 'Incoming Video Call',
//         'body' => 'Dr. ' . @$doc->first_name . ' ' . @$doc->last_name . ' is calling you.',
//       ],
//       'data' => [
//         'video_call_id' => $videoCall->id,
//         'channel_name' => $videoCall->channel_name,
//         'token' => $videoCall->token,
//       ],
//     ]);
//     $result = $messaging->send($message);
//     Log::info('$message', [$message]);
//     Log::info('result', [$result]);
//     return ['Notification send Successfully', $result];
//   }

//   public function sendFirebaseNotification($pId, $videoCall)
//   {
//     $patineId = 'P' . $pId;
//     $patient = \App\Models\pp\Users::where('patient_number', $patineId)->first();
//     Log::info('dddddddd', [$patient]);
//     $fcm_token = $patient->fcm_token;
//     $user = Auth::user();
//     $doc = DoctorsInfo::where('user_id', $user->id)->first();
//     // Get OAuth 2.0 Bearer token
//     // $bearerToken = $this->getBearerToken();
//     $pathToServiceAccount = storage_path('pplivev10-firebase-adminsdk-w02bb-80fa4017ff.json');
//     $this->client = new Client();
//     $this->client->setAuthConfig($pathToServiceAccount);
//     $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//     // Generate OAuth2 access token
//     $this->accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];

//     // The OTP to be sent
//     // $otp = $otp; // Generate a 6-digit OTP

//     // Notification payload
//     $notification = [
//       'title' => 'Incoming Video Call',
//       'body' => 'Dr. ' . @$doc->first_name . ' ' . @$doc->last_name . ' is calling you.',
//       'image' => 'https://doc.healthgennie.com/img/web/health_gennie_logo.png'  // Add image URL here
//     ];
//     Log::info('$this->accessToken', [$this->accessToken]);
//     Log::info(' $notification', [$notification]);

//     // Data payload (ensure all values are strings)
//     $data = [
//       'calling' => 'incoming call',
//     ];

//     // FCM API URL for HTTP v1
//     $fcmUrl = 'https://fcm.googleapis.com/v1/projects/pplivev10/messages:send';

//     // Prepare the payload for FCM
//     $fcmNotification = [
//       'message' => [
//         'token' => $fcm_token,  // The FCM token of the device
//         'notification' => $notification,
//         'data' => $data,
//       ],
//     ];

//     // Headers for FCM request
//     $headers = [
//       "Authorization: Bearer $this->accessToken",
//       'Content-Type: application/json',
//     ];

//     // Initialize CURL to send the request to FCM
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $fcmUrl);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

//     // Execute CURL and get the result
//     $result = curl_exec($ch);
//     curl_close($ch);

//     if ($result === FALSE) {
//       die('FCM Send Error: ' . curl_error($ch));
//     }

//     // Log the result (optional)
//     Log::info('FCM response: ' . $result);

//     // Return the generated OTP
//     return 'success';
//   }


}

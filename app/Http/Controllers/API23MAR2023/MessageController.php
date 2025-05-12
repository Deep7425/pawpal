<?php

namespace App\Http\Controllers\API23MAR2023;
use App\Events\MessageSent;
use App\Http\Controllers\FcmNotificationService;
use App\Models\ChatbotNode;
use App\Models\ChatbotUserResponse;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ehr\AppointmentOrder;
use App\Models\ehr\Appointments;
use App\Models\ehr\User as EhrUser;
use App\Models\ehr\PatientLabs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MessageController extends APIBaseController
{
    public function getOrCreateConversation(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|integer',
            'doc_id' => 'required|integer',
            'patient_id' => 'required|integer',
        ]);
        $data = $request->only(['appointment_id', 'doc_id', 'patient_id']);
        $conversation = ChatConversation::firstOrCreate(
            [
                'appointment_id' => $data['appointment_id'],
                'doc_id' => $data['doc_id'],
                'patient_id' => $data['patient_id'],
            ],
            [
                'last_message_at' => now()
            ]
        );
        return response()->json(['conversation' => $conversation]);
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'nullable|string|max:12000',
        ]);
        if($request->image && ! $request->hasFile('image')) {
            // Extract the base64 string
            $base64Image = $request->image;
            // Parse and get the file extension
            preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension);
            $image = str_replace("data:image/{$imageExtension[1]};base64,", '', $base64Image);
            $image = str_replace(' ', '+', $image); // In case spaces are encoded
            // Generate filename
            $fileName = 'chat_' . time() . '.' . $imageExtension[1];
            // Decode and store the image
            Storage::disk('s3')->put("public/uploads/chat-media/" . $fileName, base64_decode($image));
            $imagePath = $fileName;
        }
        $message = ChatMessage::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
            'sender_type' => $request->sender_type,
            'image' => @$imagePath,
            'sent_at' => now(),
        ]);
        Log::info('$message', [$message]);
        $message->conversation->update(['last_message_at' => now()]);
        //broadcast(new MessageSent($message))->toOthers();

        $ChatConversation = ChatConversation::where('id', $message->conversation_id)->first();
        Log::info($ChatConversation);
        $receiver = EhrUser::where('id', $ChatConversation->doc_id)->first();
        Log::info($receiver);
       /** if ($receiver && $receiver->fcm_token) {
            $this->sendNotificationPPIn(
                null,
                [$receiver->fcm_token],
                $message->message,
                'New message',
                null,
                null,
                'chat',
                [
                    'user_id' => $receiver->id,
                    'conversation_id' => $message->conversation_id,
                    'sender_id' => $message->sender_id,
                ],
                'chat',
                $message->conversation_id,
                false
            );
        } **/
        return response()->json([
            'status' => true,
            'message' => 'Message sent successfully!',
            'data' => $message,
        ]);
    }


    public function getMessages(Request $request)
    {
        $data = $request->all();
        $messages = ChatMessage::where(['conversation_id'=> $data['id']])->where('status', '0')
        ->orderBy('sent_at', 'asc')
        ->get()
        ->map(function ($msg) {
            $formatted = [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'sent_at' => $this->humanReadableTime($msg->sent_at),
                'sender_type' => $msg->sender_type,
                'sender_details' => $msg->sender_details, // dynamically resolved
            ];

            if (!empty($msg->image)) {
                $formatted['image'] = getPath("public/uploads/chat-media/" . $msg->image);
            }

            return $formatted;
        });

    
    return response()->json($messages);
    
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
        ]);

        ChatMessage::where('conversation_id', $request->conversation_id)
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'Messages marked as read']);
    }

    private function humanReadableTime($datetime)
    {
        $carbon = Carbon::parse($datetime);
        if ($carbon->isToday()) {
            return 'Today ' . $carbon->format('h:i A');
        } elseif ($carbon->isYesterday()) {
            return 'Yesterday ' . $carbon->format('h:i A');
        } else {
            return $carbon->format('d M Y h:i A');
        }
    }

    public function sendNotificationPPIn($apikey = null, $registrationIds = [], $message = null, $title = null, $subtitle = null, $tickerText = null, $page = null, $pageIds = [], $nType = '1',$sender, $notificationIcon = false)
    {
        Log::info('registrationIds', [ func_get_args()]);
        if (count($registrationIds) > 0 && $message != '' && $message != null && $title != '' && $title != null) {
            
            $response = null;
            $CHATCONVERSETION = ChatConversation::where('id', $sender)->first();
            foreach ($registrationIds as $token) {
                $msg = [
                    'body'  => $message,
                    'title'  => $title,
                    'image' => $notificationIcon == true ? "https://www.healthgennie.com/img/notificationIcon.png" : null,
                ];
                $params = [
                    "message" => [
                        "notification" => $msg,
                        "android" => [
                            "priority" => "high",
                            "notification" =>
                            [
                                "channel_id" => "custom_sound_channel",
                                "sound" =>
                                "samsung_ringtone"
                            ]
                        ],
                        "token" => $token,
                        "data" => [
                            'page' => 'chat',
                            'doctor_id' => (string) $CHATCONVERSETION->doc_id,
                            'pId' => (string) $CHATCONVERSETION->patient_id,
                            'appointment_id' => (string) $CHATCONVERSETION->appointment_id,
                            'type' => (string) 1,
                            

                        ],
                    ],
                ];
                Log::info('$params', [$params]);
                $fcmService = new FcmNotificationService();
                $response = $fcmService->sendNotification($params);
                Log::info('eee',  [$response]);
            }
            return $response;
        }
        return true;
    }
    public function getInitialQuestions()
    {
        $questions = ChatbotNode::whereNull('parent_id')->get();
        return response()->json($questions);
    }

    public function getTicketStatus(Request $request) {
		$request->validate([
            'user_id' => 'required',
        ]);
		$data = $request->all();
		$userId = $data['user_id'];
		$lastChat = ChatMessage::with("Ticket")->where("sender_id",$userId)->where(["status"=>0,"is_read"=>1])->orderBy("created_at","DESC")->first();
		return response()->json($lastChat);
	}
	public function getFinishChat(Request $request) {
		$request->validate([
            'message_id' => 'required',
        ]);
		$data = $request->all();
		ChatMessage::where("id",$data['message_id'])->update(["status"=>1]);
		return response()->json(['message' => 'Chat has been closed!']);
	}
	public function getChildNodes(Request $request)
    {
        $data = $request->all();
        $userId = $data['user_id'];
        $apptId = @$data['appointment_id'];
        $docId = @$data['doc_id'];
        $pId = @$data['patient_id'];
       
		if($data['session_id'] == 5) {
            $data['session_id'] = 1;
        }
        elseif (in_array($data['session_id'], [97, 98])) {
            $data['session_id'] = 96;
        }
        elseif (in_array($data['session_id'], [54,55])) {
            $data['session_id'] = 53;
        }
        elseif (in_array($data['session_id'], [78, 79])) {
            $data['session_id'] =  77;
        }
        elseif (in_array($data['session_id'], [82, 83])) {
            $data['session_id'] =  81;
        }  
        elseif (in_array($data['session_id'], [86,87])) {
            $data['session_id'] =  85;
        }
        elseif($data['session_id'] == 90) {
            $data['session_id'] =  89;
        }
        elseif (in_array($data['session_id'], [69, 70, 71, 72, 73, 74, 75])) {
            $data['session_id'] = 68;
        }
        elseif (in_array($data['session_id'], [58, 59, 60, 61, 62, 63, 64, 65, 66])) {
            $data['session_id'] =  57;
        }
        $children = ChatbotNode::where('parent_id', $data['session_id'])->get();
        if($data['session_id'] == 3) {
            $appt = Appointments::where('id',$apptId)->first();
            if(!empty($appt) && $appt->visit_status == 1) {
                $children = ChatbotNode::whereIn('id',[95,6])->get();                
            }
            else{
                $children = ChatbotNode::whereIn('id',[4,6])->get();
            }
        }
        $newArr = [];
        if($data['session_id'] == 31 || $data['session_id'] == 17) {
            $labs = PatientLabs::with('labs')->where('appointment_id',$apptId)->get();
			if(count($labs) == 0) {
				$children = ChatbotNode::where('parent_id', 17)->orderBy('sort_order','ASC')->get();
			}
			else{
				$newArr = $labs;
			}
        }
        if (in_array($data['session_id'], [8, 9, 21, 13, 38, 37, 41, 42, 45])) {
        $ChatConversation = ChatConversation::where('appointment_id', $apptId)->first();
       
	if ($ChatConversation) {
	    $ChatMessage = ChatMessage::where([
		'conversation_id' => $ChatConversation->id,
		'status' => 0
	    ])->update(['status' => 1]);
	    
	    
	}

            $children = ChatbotNode::where('id', 99)->get();
            $conversation = ChatConversation::firstOrCreate([
                    'appointment_id' => $apptId,
                    'doc_id' => $docId,
                    'patient_id' => $pId,
                ],['last_message_at' => now()]
            );
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $userId,
                'message' => $data['input_text'],
                'sender_type' => 2,
                'sent_at' => now(),
            ]);
			$newArr['conversation_id'] = $message->conversation_id; 
			$newArr['message_id'] = $message->id; 
        }
        if($children->count()>0) {
            foreach($children as $raw) {
                if($raw->node_type == 'question') {
                    $newArr['question'] = $raw; 
                }
                else{
                    $newArr['option'][] = $raw;
                }
            }
        }
        return response()->json($newArr);
    }

    public function storeUserResponse(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|max:100',
            'node_id' => 'required|exists:chatbot_nodes,id',
            'input_text' => 'nullable|string',
        ]);
        $response = ChatbotUserResponse::create($validated);
        return response()->json([
            'message' => 'Response saved successfully!',
            'data' => $response,
        ]);
    }

    public function getUserResponses(Request $request)
    {
        $data= $request->all();
        $userId = $data['user_id'];
        $responses = ChatbotUserResponse::with('node')
            ->where('user_id', $userId)
            ->get();

        return response()->json($responses);
    }
}

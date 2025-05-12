<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketUser;
use App\Models\ehr\DoctorsInfo;
use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\ehr\Appointments;
use App\Models\ChatConversation;
use App\Models\ehr\User as EhrUser;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TicketController extends Controller
{

    public function AssignNow(Request $request)
    {
        $ticketId = $request->ticket_id;
        $userId = $request->user;
        

//        dd($request->all());
        if(is_null($ticketId)){
            throw new  HttpException(400, 'Please Select Ticket First');
        }

        // Find the ticket by ID
        $ticket = Ticket::find($ticketId);

        $ticket->assign_by = $userId;
        $ticket->save();

        // Optionally, you can return a response indicating the success of the operation
        return response()->json(['message' => 'Ticket assigned successfully']);


    }
    public function unassignTicketList(Request $request)
    {
        $page = 10;
        $query = Ticket::with(['user.files', 'comments.ticketReply'])
            ->whereNull('assign_by')
            ->orderByDesc('created_at')
            ->paginate($page);
            $users = Admin::orderBy('id', 'ASC')->get();
        return view('supportTickets.ticket-list', compact('query' , 'users'));
    }

    public function assignTicketList(Request $request)
    {
        // Start with a base query
        $data=$request->all();
        // dd($data);

        $query = Ticket::with([ 'user', 'comments.ticketReply'])
            ->whereNotNull('assign_by')
            ->orderByDesc('created_at');

        // Apply filters if they exist in the request
        if ($request->filled('ticket_no')) {
            $query->where('ticket_no', $request->ticket_no);
        }

        if ($request->filled('prefix')) {
            if($request->prefix == "OTHER") {
                $query->whereNull('ticket_no');
            }else{
                $query->where('ticket_no', 'like' ,'%'.$request->prefix.'%');
            }
 
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            if ($request->department_id == 3) {
                $query->whereNull('case_type');
            } else {
                $query->where('case_type', $request->department_id);
            }
        }

        // Execute the query
        $query = $query->get();

        return view('supportTickets.assign-ticket', compact('query'));
    }

    public function editTicket(Request $request)
    {

        $id = $request->id;
        $users = Admin::orderBy('id', 'ASC')->get();
        $ticket = Ticket::with(['assignByUser.departments', 'user', 'comments.ticketReply'])->findOrFail($id);
        return view('supportTickets.edit-ticket', compact('ticket' , 'users'));

    }
    public function getReplyMessage(Request $request)
    {

        $id = $request->id;
        $ticket = Ticket::with(['assignByUser.departments', 'user', 'comments.ticketReply'])->findOrFail($id);
        return view('supportTickets.update-status', compact('ticket'));

    }
    public function updateTicket(Request $request)
    {
        $id = $request->id;
        $departmentId = $request->input('department');
        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status' => $request->input('status'),
            'assign_by' => $request->input('assign_by'),
            'case_type'=> $departmentId
        ]);
        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    public function replyToComment(Request $request, Comment $comment)
    {
        $request->validate([
            'reply_message' => 'required|string|max:255',
        ]);

        $ticketData = $request->all();
        $ticketId = $ticketData['ticket_id'];




        // Retrieve the ticket
        $ticket = Ticket::findOrFail($ticketId);
        $assignByUser = $ticket->assignByUser;



        $comment->ticketReply()->create([
            'message' => $request->input('reply_message'),
            'ticket_id' => $request->input('ticket_id'),
            'department_id' => $ticket->case_type
        ]);

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }

public function viewTickets()
{
    try {
        // Count total tickets
        $totalTickets = Ticket::count();

        // Retrieve the status-wise count of tickets grouped by year and month
        $statusWiseCounts = Ticket::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                'status',
                'created_at'
            )
            ->groupBy('year', 'month', 'status')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Group the data by status for easier processing in the view
        $statusWiseCountsGrouped = [];
        foreach ($statusWiseCounts as $count) {
            $statusWiseCountsGrouped[$count->status][] = [
                'month' => $count->month,
                'count' => $count->count,
                'created_at' => $count->created_at
            ];
        }

        // Pass the data to the view
        return view('supportTickets.ticket-view-progress', compact('totalTickets', 'statusWiseCountsGrouped'));
        
    } catch (Exception $e) {
        // Log the error message for debugging purposes
        Log::error("Error in viewTickets function: " . $e->getMessage());
        // Return an error view or redirect with an error message
        return redirect()->route('supportTickets.ticket-view-progress')
            ->with('error', 'System Problem: Please try again later.');
    }
}
    public function fetchUsers(Request $request)
    {
        $departmentId = $request->input('department_id');
        $users = TicketUser::where('department_id', $departmentId)->get();
        return response()->json($users);
    }


    public function createTicket(Request $request)
    {
        
        return view('supportTickets.createTicket');
    }

    public function getCaseCategories($caseTypeId) {
        $categories = getCaseCategory($caseTypeId);
        return response()->json($categories);
    }


    public function createCase(Request $request){

        if($request->isMethod('post')){

            $data = $request->all();
        
            $latestTicket = Ticket::where('ticket_no' ,'like', '%HG%')->orderBy('id', 'desc')->first();
         
            $login_id = Session::get('id');
            
            $ticketNumber = $data['ticket_name'] . str_pad(($latestTicket ? $latestTicket->id + 1 : 1), 7, '0', STR_PAD_LEFT);
           
            $str = Str::uuid();
            $category = isset($data['category_input']) && !empty($data['category_input']) ? $data['category_input'] : $data['category_input'];
      
            try{
                $createCase = Ticket::create([ 
                    'uuid' => $str,
                    'subject' =>  $data['subject'],
                    'ticket_no' =>   $ticketNumber,
                    'category' =>     $category ,
                    'case_type' =>   $data['case_type'],
                    'status' => AppConstants::TICKET_STATUS_PENDING,
                    'priority' => $request->input('priority'),
                    'user_id' =>   $login_id,
                    'assignee_status' =>   1,
                ]);
               
    
                if ($data['comment']) {
                    $comment = new Comment();
                    $comment->tickets_id = $createCase->id;
                    $comment->comments = $request->comment;
                    $comment->save();
                }
                if ($request->hasFile('document')) {
                    $uploadedFiles = $request->file('document');
                    foreach ($uploadedFiles as $file) {
                        $timestamp = now()->timestamp . uniqid();
                        $extension = $file->getClientOriginalExtension();
                        $originalName = $file->getClientOriginalName();
                        $fullName = $timestamp . '.' . $extension;
                        $directory = "issuesDocument";
                        $filePath = public_path($directory . '/' . $fullName);
                        $file->move(public_path($directory), $fullName);
                        $files = new File([
                            'name' => $originalName,
                            'local_path' => $fullName,
                            'user_id' => $login_id,
                        ]);
                        $files->save();
        //                $users->files()->save($files);
                    }
                }
                
            // $userEmail = User::where('id' ,  $login_id)->where('status' , 1)->first();
            // $to = $userEmail->email;
            // if(!empty($to)) {
            //     $EmailTemplate = EmailTemplate::where('slug','ticket')->first();
            //     if($EmailTemplate) {
            //         $body = $EmailTemplate->description;
            //         $mailMessage = str_replace(array('{{username}}', '{{caseID}}' ),array("Sir ",$ticketNumber ),$body);
            //         $datas = array('to' =>$to,'from' => 'info@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
            //         try{
            //         Mail::send('emails.all', $datas, function( $message ) use ($datas)
            //           {
            //            $message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
            //         });
            //         }
            //         catch(\Exception $e){
            //         }
            //     }	
            // }
            return redirect()->back()->with('success', 'Ticket Created successfully.');
        }
             catch (\Exception $e)
              {
                return response()->json(['error' => 'Failed to create ticket.', 'message' => $e->getMessage()], 500);
            }
               
         
            }
        
        }

        function requestTickets(Request $request){
        	
 		 $today = Carbon::today();
    		 $appointmentsToday = Appointments::whereDate('start', $today)->pluck('id');
		 // 2. Get conversation IDs for those appointments
		 $conversationIds = ChatConversation::whereIn('appointment_id', $appointmentsToday)->pluck('id');
                
                $requestTickets = ChatMessage::groupBy('conversation_id')->orderBy('id', 'desc')->whereIn('conversation_id', $conversationIds)->paginate(10);
                
                return view('supportTickets.request-ticket' , compact('requestTickets'));

        }

        function createUserTicket($id){

            $id = base64_decode($id);
             $data = ChatMessage::with('sender')->where('id' , $id)->first();
             
            return view('supportTickets.user-ticket' , compact('data'));  
        }

        function addCase(Request $request){

            if($request->isMethod('post')){

                $data = $request->all();
             
            
                $latestTicket = Ticket::where('ticket_no' ,'like', '%HG%')->orderBy('id', 'desc')->first();
             
                $login_id = $data['user_id'];
                
                $ticketNumber = "HGUSER" . str_pad(($latestTicket ? $latestTicket->id + 1 : 1), 7, '0', STR_PAD_LEFT);
               
                $str = Str::uuid();
                $category = isset($data['category_input']) && !empty($data['category_input']) ? $data['category_input'] : $data['category_input'];
          
                try{
                    $createCase = Ticket::create([ 
                        'uuid' => $str,
                        'subject' =>  $data['subject'],
                        'ticket_no' =>   $ticketNumber,
                        'category' =>     $category ,
                        'case_type' =>   $data['case_type'],
                        'status' => AppConstants::TICKET_STATUS_PENDING,
                        'priority' => $request->input('priority'),
                        'user_id' =>   $login_id,
                         'msg_id' =>     $data['msg_id'],
                        'assignee_status' =>   2,
                    ]);

                    ChatMessage::where('sender_id' , $login_id)->update([
                        'is_read' => 1
                    ]);
                   
        
                    if ($data['comment']) {
                        $comment = new Comment();
                        $comment->tickets_id = $createCase->id;
                        $comment->comments = $request->comment;
                        $comment->save();
                    }
                    if ($request->hasFile('document')) {
                        $uploadedFiles = $request->file('document');
                        foreach ($uploadedFiles as $file) {
                            $timestamp = now()->timestamp . uniqid();
                            $extension = $file->getClientOriginalExtension();
                            $originalName = $file->getClientOriginalName();
                            $fullName = $timestamp . '.' . $extension;
                            $directory = "issuesDocument";
                            $filePath = public_path($directory . '/' . $fullName);
                            $file->move(public_path($directory), $fullName);
                            $files = new File([
                                'name' => $originalName,
                                'local_path' => $fullName,
                                'user_id' => $login_id,
                            ]);
                            $files->save();
            //                $users->files()->save($files);
                        }
                    }
                    
                // $userEmail = User::where('id' ,  $login_id)->where('status' , 1)->first();
                // $to = $userEmail->email;
                // if(!empty($to)) {
                //     $EmailTemplate = EmailTemplate::where('slug','ticket')->first();
                //     if($EmailTemplate) {
                //         $body = $EmailTemplate->description;
                //         $mailMessage = str_replace(array('{{username}}', '{{caseID}}' ),array("Sir ",$ticketNumber ),$body);
                //         $datas = array('to' =>$to,'from' => 'info@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
                //         try{
                //         Mail::send('emails.all', $datas, function( $message ) use ($datas)
                //           {
                //            $message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
                //         });
                //         }
                //         catch(\Exception $e){
                //         }
                //     }	
                // }
                return redirect()->back()->with('success', 'Ticket Created successfully.');
            }
                 catch (\Exception $e)
                {
                    return response()->json(['error' => 'Failed to create ticket.', 'message' => $e->getMessage()], 500);
                }
             }
        }
        public function sendMessage(Request $request)
    {
         $data = $request->all();
  
         $senderId =  Session::get('id');


        $request->validate([
            'conversationId' => 'required|exists:chat_conversations,id',
            'message' => 'nullable|string|max:12000',
        ]);
        
        // if ($request->image && ! $request->hasFile('image')) {
        //     // Extract the base64 string
        //     $base64Image = $request->image;
        //     // Parse and get the file extension
        //     preg_match("/^data:image\/(.*?);base64,/", $base64Image, $imageExtension);
        //     $image = str_replace("data:image/{$imageExtension[1]};base64,", '', $base64Image);
        //     $image = str_replace(' ', '+', $image); // In case spaces are encoded
        
        //     // Generate filename
        //     $fileName = 'chat_' . time() . '.' . $imageExtension[1];
        
        //     // Decode and store the image
        //     Storage::disk('s3')->put("public/uploads/chat-media/" . $fileName, base64_decode($image));
        //     $imagePath = $fileName;
        // }
        

        $message = ChatMessage::create([
            'conversation_id' => $request->conversationId,
            'sender_id' => $senderId,
            'message' => $request->message,
            'sender_type' => 3,
            'sent_at' => now(),
        ]);
        \Log::info('$message', [$message]);

        $message->conversation->update(['last_message_at' => now()]);
    
    
        return response()->json([
            'status' => true,
            'message' => 'Message sent successfully!',
            'data' => $message,
        ]);
    }
    
    public function getMessages(Request $request)
{
    $conversationId = $request->conversation_id;

    $messages = ChatMessage::where('conversation_id', $conversationId)
                ->orderBy('created_at', 'asc')
                ->get();


    return response()->json($messages);
}

	public function startChat(Request $request)
	{
	    $data = $request->all();
	    $senderId = Session::get('id');

	    $chatConversation = ChatConversation::where('id', $data['conversationId'])->first();

	    if (!$chatConversation) {
		return response()->json(['error' => 'Conversation not found.'], 404);
	    }
$chat = ChatMessage::where(['conversation_id' => $data['conversationId'], 'sender_type' => 1])->first();
	    // If doctor is already assigned
	    if ($chat) {
		$chatConversation->update(['last_message_at' => now()]);
		return response()->json(['message' => 'Doctor already assigned.']);
	    }

	    // Assign doctor and update last_message_at
	    $chatConversation->update([
		'doc_id' => $senderId,
		'last_message_at' => now()
	    ]);

	    $doctorInfo = DoctorsInfo::where('user_id', $senderId)->first();

	    // Create new message
	    $message = ChatMessage::create([
		'conversation_id' => $chatConversation->id,
		'sender_id' => $senderId,
		'message' => 'Dr. ' . ($doctorInfo->first_name ?? '') . ' ' . ($doctorInfo->last_name ?? '') . ' has been assigned to you.',
		'sender_type' => 1,
		'sent_at' => now(),
		
	    ]);

	    // ✅ Update status of older messages where sender_type is 2 or 3
	    ChatMessage::where('conversation_id', $chatConversation->id)
		->where('id', '<>', $message->id) // Exclude the newly created message
		->whereIn('sender_type', [2, 3])
		->update(['status' => 1]); // Set desired status value

	    \Log::info('Chat assigned and relevant older messages updated', [$message]);

	    return response()->json($message);
	}








}

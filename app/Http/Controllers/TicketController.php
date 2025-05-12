<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\Comment;
use App\Models\Department;
use App\Models\File;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Illuminate\Support\Facades\Mail;


class TicketController extends Controller
{

    public function store(Request $request)
    {
        

        $users = TicketUser::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile_no' => $request->input('mobile_no')
        ]);
    
        $latestTicket = Ticket::orderBy('id', 'desc')->first();
        $ticketNumber = 'HG' . str_pad(($latestTicket ? $latestTicket->id + 1 : 1), 7, '0', STR_PAD_LEFT);
        $str = Str::uuid();
    
        $tickets = Ticket::create([
            'uuid' => $str,
            'user_id' => $users->id,
            'ticket_no' => $ticketNumber,
            'status' => AppConstants::TICKET_STATUS_PENDING,
            'priority' => $request->input('priority'),
        ]);
    
        if ($request->has('comments')) {
            $comment = new Comment([
                'comments' => $request->input('comments'),
                'tickets_id' => $tickets->id
            ]);
            $tickets->comments()->save($comment);
        }
    
        if ($request->hasFile('document')) {
            $uploadedFiles = $request->file('document');
            foreach ($uploadedFiles as $file) {
                $timestamp = now()->timestamp . uniqid();
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $fullName = $timestamp . '.' . $extension;
                $directory = "issuesDocument";
                $file->move(public_path($directory), $fullName);
    
                $files = new File([
                    'name' => $originalName,
                    'local_path' => $fullName,
                    'user_id' => $users->id,
                ]);
                $files->save();
            }
        }
    
        $EmailTemplate = 'Support Ticket Raise';
        if ($EmailTemplate) {
            $mailMessage = $tickets->ticket_no;
            $datas = [
                'to' => $users->email,
                'from' => 'noreply@healthgennie.com',
                'mailTitle' => $EmailTemplate,
                'content' => 'Your ticket has been successfully submitted. Your Support Ticket no is ' . $mailMessage . '. Track Your Status. Our support team will review your request and respond as soon as possible',
                'subject' => 'HG Support Ticket Raise'
            ];
    
            try {
                Mail::send('emails.support-ticket-mail', $datas, function ($message) use ($datas) {
                    $message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
                });
            } catch (\Exception $e) {
               
            }
        }
    
        $message = urlencode("Dear " . $users->name . " Your ticket has been successfully submitted. Ticket ID: " . $tickets->ticket_no . " Our support team will review your request and respond as soon as possible. Thanks Team Health Gennie");
        $this->sendSMS($users->mobile_no, $message, '1707161587866524594');
    
        Session::flash('Success');

        return response()->json([
            'status' => 1,
            'ticket_no' => $tickets->ticket_no
        ]);
    }
    

    public function trackStatus(Request $request)
    {
        $request = $request->all();

        $ticketId = Ticket::query();
        $ticket = $ticketId->where('ticket_no', $request['ticket_no'])->with(['comments.ticketReply'])->get();
        if($ticket->isEmpty())
        {
            return response()->json(['error' => 'No Data Found!'], 404);
        }
        return $ticket;
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'ticketId' => 'required',
            'comment' => 'required|string|max:255',
        ]);

        $ticket = Ticket::where('id', $request->ticketId)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $comment = new Comment();
        $comment->tickets_id = $request->ticketId;
        $comment->comments = $request->comment;
        $comment->save();

        return response()->json(['success' => true]);
    }

    public function ticketReply(Request $request)
    {
        $ticket = Ticket::where('ticket_no', $request->ticket_no)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $ticketReply = new TicketReply();
        $ticketReply->ticket_id = $ticket->id;
        $ticketReply->department_id = $ticket->department_id; // Example: Assuming department_id is a property of the ticket
        $ticketReply->message = $request->message;
        $ticketReply->save();
        return response()->json(['message' => 'Ticket reply added successfully'], 200);
    }





}

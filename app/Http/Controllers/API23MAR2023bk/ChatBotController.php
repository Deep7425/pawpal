<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ChatData;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {   $userId = $request->input('user_id');
        $userMessage = $request->input('message');
        $roomName = $request->input('room_name');
        
        // Save the user's message to the database
        ChatData::create([
            'user_id' => $userId, // If using authentication
            'room_name' => $roomName,
            'message' => $userMessage,
        ]);

        // Generate a response based on user input
        $botResponse = $this->generateResponse($userMessage);

        return response()->json(['message' => $botResponse]);
    }

    public function receiveMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $roomName = $request->input('room_name');
        
        // Save the user's message to the database
        ChatData::create([
            'user_id' => auth()->id(), // If using authentication
            'room_name' => $roomName,
            'message' => $userMessage,
        ]);

        // Generate a response based on user input
        $botResponse = $this->generateResponse($userMessage);

        return response()->json(['message' => $botResponse]);
    }

    private function generateResponse($userMessage)
    {
        // Define custom responses based on user input
        $responses = [
            'hi' => 'Welcome to Health Genie! How can I help you?',
            'hello' => 'Hello! How can I assist you today?',
            'what\'s your name' => 'I am Health Genie, your virtual assistant.',
            'what\'s your age' => 'I don\'t have an age as I am a computer program.',
            'gender' => 'I am just a chatbot and do not have a gender.',
            'contacts' => 'Sorry, but I can\'t provide my contact information.'
            // Add more custom responses as needed
        ];

        // Check if the user's message matches any predefined responses
        $userMessageLower = strtolower($userMessage);
        if (isset($responses[$userMessageLower])) {
            return $responses[$userMessageLower];
        }

        // If no predefined response matches, provide a default response
        return 'I\'m here to assist you with your health-related questions.';
    }
}
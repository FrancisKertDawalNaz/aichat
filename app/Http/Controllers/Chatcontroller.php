<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message;

class Chatcontroller extends Controller
{
    public function index(){
        $messages = message::all();
        return view('chat', compact('messages'));
    }

    public function send(Request $request)
    {
        $userMessage = $request->input('message');

        // Save user message
        Message::create([
            'sender' => 'user',
            'message' => $userMessage
        ]);

        // Simple AI logic (rule-based)
        $reply = "Sorry, I don’t understand yet 😅";
        if (str_contains(strtolower($userMessage), 'hello')) {
            $reply = "Hi! How can I help you today?";
        } elseif (str_contains(strtolower($userMessage), 'bye')) {
            $reply = "Goodbye! Take care.";
        }

        // Save AI reply
        Message::create([
            'sender' => 'ai',
            'message' => $reply
        ]);

        return response()->json(['reply' => $reply]);
    }
}

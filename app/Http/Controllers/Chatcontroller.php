<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Services\GeminiService;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::all();
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

        // Call Gemini AI service
        $gemini = new GeminiService();
        $reply = $gemini->getReply($userMessage);

        // Save AI reply
        Message::create([
            'sender' => 'ai',
            'message' => $reply
        ]);

        return response()->json(['reply' => $reply]);
    }
}

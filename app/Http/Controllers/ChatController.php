<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\AIService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function saveInitialQuestion(Request $request)
    {
        $question = $request->input('question', '');
        if (!empty($question)) {
            $request->session()->put('chat_question', $question);
        }
        return redirect()->route('chat');
    }

    public function chatPage(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng đã đăng nhập
        // Lấy initialQuestion từ session và xóa sau khi lấy
        $initialQuestion = $request->session()->get('chat_question', '');
        $request->session()->forget('chat_question');

        return view('chat', compact('initialQuestion', 'user'));
    }

    public function chat(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        $prompt = $request->input('prompt');
        $conversationId = $request->input('conversation_id');

        if (empty($prompt)) {
            return response()->json(['error' => 'Câu hỏi không được để trống'], 400);
        }

        // Kiểm tra cuộc trò chuyện có tồn tại và thuộc về người dùng
        if (!$conversationId || !Conversation::where('user_id', $userId)->find($conversationId)) {
            return response()->json(['error' => 'Cuộc trò chuyện không tồn tại hoặc không thuộc về bạn'], 404);
        }

        $response = new StreamedResponse(function () use ($prompt) {
            if (ob_get_level()) ob_end_clean();
            header('Connection: keep-alive');

            $this->aiService->askAI($prompt, function ($data) {
                echo "data: " . json_encode(['message' => $data]) . "\n\n";
                flush();
            });

            // Gửi sự kiện done khi stream hoàn tất
            echo "event: done\n";
            echo "data: {}\n\n";
            flush();
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    public function getConversation(Request $request, $id)
    {
        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        $conversation = Conversation::where('user_id', $userId)->find($id);
        if (!$conversation) {
            return response()->json(['error' => 'Cuộc trò chuyện không tồn tại hoặc không thuộc về bạn'], 404);
        }

        $messages = Message::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content']);

        return response()->json(['messages' => $messages]);
    }

    public function saveMessage(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'role' => 'required|in:user,ai',
            'content' => 'required|string'
        ]);

        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        // Nếu không có conversation_id, tạo mới
        if (!$data['conversation_id']) {
            $conversation = Conversation::create([
                'user_id' => $userId,
                'title' => substr($data['content'], 0, 30) . (strlen($data['content']) > 30 ? "..." : "")
            ]);
            $data['conversation_id'] = $conversation->id;
        } else {
            $conversation = Conversation::where('user_id', $userId)->find($data['conversation_id']);
            if (!$conversation) {
                return response()->json(['error' => 'Cuộc trò chuyện không tồn tại hoặc không thuộc về bạn'], 403);
            }
        }

        Message::create([
            'conversation_id' => $data['conversation_id'],
            'role' => $data['role'],
            'content' => $data['content']
        ]);

        // Cập nhật thời gian để phản ánh trong lịch sử
        $conversation->update(['updated_at' => now()]);

        return response()->json(['status' => 'success', 'conversation_id' => $data['conversation_id']]);
    }

    public function deleteConversation(Request $request, $id)
    {
        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        $conversation = Conversation::where('user_id', $userId)->findOrFail($id);
        $conversation->delete();
        return response()->json(['status' => 'success']);
    }

    public function getHistory(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        $history = Conversation::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json(['history' => $history]);
    }

    public function clearHistory(Request $request)
    {
        $user = Auth::user(); // Lấy người dùng
        $userId = $user->id; // Lấy ID từ đối tượng người dùng

        Conversation::where('user_id', $userId)->delete();
        return response()->json(['status' => 'success']);
    }
}
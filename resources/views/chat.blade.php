<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat với AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }
        .message {
            word-break: break-word;
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            max-width: 80%;
            line-height: 1.6;
            margin-bottom: 10px;
            clear: both;
        }
        .user {
            background-color: #00a9b8;
            color: white;
            float: right;
            margin-left: auto;
        }
        .ai {
            background-color: #f1f1f1;
            float: left;
            margin-right: auto;
        }
        .message pre {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            overflow-x: auto;
            font-family: monospace;
            font-size: 14px;
        }
        .message code {
            background-color: #f0f0f0;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
        }
        #chat-box::after {
            content: "";
            display: table;
            clear: both;
        }
        #chat-box {
            max-height: 70vh;
            overflow-y: auto;
        }
        .profile img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
        }
        .loading-dots {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .loading-dots span {
            width: 8px;
            height: 8px;
            background-color: #00a9b8;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 0.6s infinite alternate;
        }
        .loading-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        .loading-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes bounce {
            to {
                transform: translateY(-6px);
            }
        }
    </style>
</head>
<body class="bg-white font-sans text-sm text-[#333] min-h-screen">
    <div class="flex min-h-screen w-full border border-l-0 border-t-0 border-b-0 border-gray-300">
        <!-- Left panel: History -->
        <div class="w-full sm:w-[300px] border-r border-gray-300 flex flex-col max-h-screen">
            <div class="flex items-center justify-between px-5 py-4">
                <div class="flex items-center space-x-2">
                    <span class="font-extrabold text-[#ffb600] text-lg leading-none select-none">Lịch sử</span>
                </div>
            </div>
            <div class="border-t border-gray-300"></div>
            <div class="px-5 py-5">
                <button id="new-chat-button" class="bg-[#00a9b8] text-white text-xs font-semibold rounded-full px-6 py-3 flex items-center space-x-2 hover:bg-[#0096a3] transition w-full sm:w-auto" type="button">
                    <i class="fas fa-plus"></i>
                    <span>Cuộc trò chuyện mới</span>
                </button>
            </div>
            <div class="flex items-center justify-between px-5 mb-3">
                <b class="text-xs">Lịch sử</b>
                <a class="text-xs text-[#00a9b8] hover:underline" href="" onclick="clearHistory()">Xóa tất cả</a>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-thin">
                <ul id="chat-history" class="divide-y divide-gray-300">
                    <!-- Chat history items will be dynamically populated via AJAX -->
                </ul>
                <p class="px-5 py-4 text-xs text-[#b9a99a] leading-relaxed">
                    Thông tin được cung cấp bởi Trợ lý AI chỉ mang tính tham khảo. Vui lòng kiểm tra thông tin chính thức. Xem thêm:
                    <a class="underline" href="#">Quyền riêng tư của bạn</a>
                </p>
            </div>
        </div>
        <!-- Center panel: Chatbox -->
        <div class="flex-1 p-5 sm:p-8 flex flex-col items-center">
            <div id="chat-box" class="flex-1 mb-8 w-full scrollbar-thin" style="max-width: 80%;">
                <!-- Tin nhắn sẽ được tải động qua AJAX -->
            </div>
            <div class="border border-gray-300 rounded-lg p-5 w-full" style="max-width: 80%;">
                <div class="mb-4 text-[#b9a99a] text-xs select-none">Đặt câu hỏi với Trợ lý AI</div>
                <input id="user-input" type="text" placeholder="Nhập câu hỏi..." class="w-full border border-gray-300 rounded-md px-4 py-3 mb-4 text-[#b9a99a] text-sm focus:outline-none focus:ring-1 focus:ring-[#b9a99a]">
                <div class="flex items-center text-[#b9a99a] text-xs select-none space-x-3">
                    <i class="fas fa-comment-alt"></i>
                    <span>Chat với AI</span>
                    <div class="ml-auto flex items-center space-x-3">
                        <button id="stop-button" class="text-[#dc3545] hover:text-[#bd2130] disabled:text-[#f5858d] disabled:cursor-not-allowed">
                            <i class="fas fa-stop fa-lg"></i>
                        </button>
                        <button id="send-button" class="text-[#00a9b8] hover:text-[#0096a3]">
                            <i class="fas fa-paper-plane fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right panel: Suggested Questions -->
        <div class="w-full sm:w-[300px] border-l border-gray-300 flex flex-col max-h-screen">
            <div class="flex items-center justify-between px-5 py-4">
                <div class="flex items-center space-x-2">
                    <img alt="AI Chat logo" class="w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/28ed616b-2063-4703-d5f5-9191c73f8590.jpg" width="24"/>
                    <span class="font-extrabold text-[#ffb600] text-lg leading-none select-none">AI Chat</span>
                </div>
                <a href="{{ route('profile') }}" class="profile">
                    @if (Auth::check() && Auth::user()->image)
                        <img src="{{ asset(Auth::user()->image) }}" alt="Profile Image">
                    @else
                        <img src="{{ asset('images/users/avt.jpg') }}" alt="Default Avatar">
                    @endif
                </a>
            </div>
            <div class="border-t border-gray-300"></div>
            <div class="px-5 py-5">
                <b class="text-xs">Câu hỏi gợi ý</b>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-thin">
                <ul id="suggested-questions" class="divide-y divide-gray-300">
                    <li class="px-5 py-3 cursor-pointer hover:bg-gray-100 text-sm text-[#333]" onclick="selectSuggestion('Làm thế nào để học lập trình hiệu quả?')">Làm thế nào để học lập trình hiệu quả?</li>
                    <li class="px-5 py-3 cursor-pointer hover:bg-gray-100 text-sm text-[#333]" onclick="selectSuggestion('AI có thể giúp gì trong công việc hàng ngày?')">AI có thể giúp gì trong công việc hàng ngày?</li>
                    <li class="px-5 py-3 cursor-pointer hover:bg-gray-100 text-sm text-[#333]" onclick="selectSuggestion('Lợi ích của việc sử dụng năng lượng tái tạo là gì?')">Lợi ích của việc sử dụng năng lượng tái tạo là gì?</li>
                    <li class="px-5 py-3 cursor-pointer hover:bg-gray-100 text-sm text-[#333]" onclick="selectSuggestion('Cách cải thiện kỹ năng giao tiếp?')">Cách cải thiện kỹ năng giao tiếp?</li>
                </ul>
                <p class="px-5 py-4 text-xs text-[#b9a99a] leading-relaxed">
                    Thông tin được cung cấp bởi Trợ lý AI chỉ mang tính tham khảo. Vui lòng kiểm tra thông tin chính thức. Xem thêm:
                    <a class="underline" href="#">Quyền riêng tư của bạn</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        let currentEventSource = null;
        let currentConversationId = null; // Quản lý hoàn toàn phía client
        const initialQuestion = @json($initialQuestion ?? '');
        let history = []; // Khởi tạo rỗng, sẽ lấy qua AJAX

        function initializeChat() {
            document.getElementById("send-button").addEventListener("click", sendMessage);
            document.getElementById("stop-button").addEventListener("click", stopResponse);
            document.getElementById("new-chat-button").addEventListener("click", startNewChat);
            document.getElementById("user-input").addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    sendMessage();
                }
            });
            window.addEventListener('beforeunload', function() {
                if (currentEventSource) {
                    currentEventSource.close();
                    currentEventSource = null;
                }
            });

            // Tải lịch sử ngay khi trang được tải
            fetchHistory();

            if (initialQuestion && initialQuestion.trim() !== '') {
                document.getElementById("user-input").value = initialQuestion;
                sendMessage();
            }

            const chatBox = document.getElementById("chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function startNewChat() {
            currentConversationId = null; // Đặt lại để tạo conversation mới
            document.getElementById("chat-box").innerHTML = '';
            document.getElementById("user-input").focus();
        }

        function renderHistory() {
            let historyList = document.getElementById("chat-history");
            historyList.innerHTML = '';
            history.forEach(item => {
                let listItem = document.createElement("li");
                listItem.className = "flex justify-between items-center px-5 py-3 cursor-pointer hover:bg-gray-100";
                listItem.dataset.conversationId = item.id;
                listItem.innerHTML = `
                    <span class="text-sm text-[#333]">${item.title}</span>
                    <button aria-label="Remove chat ${item.id}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                listItem.querySelector("button").addEventListener("click", () => {
                    fetch(`/conversation/${item.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        listItem.remove();
                        if (currentConversationId == item.id) {
                            document.getElementById("chat-box").innerHTML = '';
                            currentConversationId = null;
                        }
                        fetchHistory();
                    });
                });
                historyList.prepend(listItem);
            });

            historyList.querySelectorAll('li').forEach(li => {
                li.addEventListener('click', function(e) {
                    if (!e.target.closest('button')) {
                        const conversationId = this.dataset.conversationId;
                        currentConversationId = conversationId;
                        fetchConversation(conversationId);
                    }
                });
            });
        }

        function fetchHistory() {
            fetch('/chat/history')
                .then(response => response.json())
                .then(data => {
                    history = data.history;
                    renderHistory();
                })
                .catch(error => console.error('Lỗi khi lấy lịch sử:', error));
        }

        function fetchConversation(conversationId) {
            fetch(`/api/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.getElementById("chat-box");
                    chatBox.innerHTML = ''; // Xóa nội dung cũ
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const messageElement = document.createElement("div");
                            messageElement.className = `message ${message.role}`;
                            messageElement.innerHTML = `<p>${message.content}</p>`;
                            chatBox.appendChild(messageElement);
                        });
                    }
                    chatBox.scrollTop = chatBox.scrollHeight;
                })
                .catch(error => console.error('Lỗi khi tải cuộc trò chuyện:', error));
        }

        function clearHistory() {
            fetch('/chat/history/clear', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    history = []; // Xóa danh sách lịch sử trên giao diện
                    renderHistory();
                    document.getElementById("chat-box").innerHTML = '';
                    currentConversationId = null;
                }
            })
            .catch(error => console.error('Lỗi khi xóa lịch sử:', error));
        }

        function selectSuggestion(suggestion) {
            document.getElementById("user-input").value = suggestion;
            sendMessage();
        }

        function saveAIMessage(aiMessageElement) {
            if (!currentConversationId) {
                console.error("Không có conversation_id để lưu tin nhắn AI");
                return;
            }

            let aiContent = aiMessageElement.innerHTML;
            if (aiContent === '<div class="loading-dots"><span></span><span></span><span></span></div>' || aiContent.includes("Đã xảy ra lỗi") || aiContent.includes("Kết nối đến AI đã hết thời gian")) {
                console.log("Không lưu tin nhắn AI vì nội dung không hợp lệ:", aiContent);
                return;
            }

            console.log("Đang lưu tin nhắn AI:", aiContent);
            fetch('/api/save-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    conversation_id: currentConversationId,
                    role: 'ai',
                    content: aiContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Lưu tin nhắn AI thành công:", aiContent);
                    fetchHistory(); // Cập nhật lịch sử sau khi lưu AI
                } else {
                    console.error("Lưu tin nhắn AI thất bại:", data);
                }
            })
            .catch(error => console.error('Lỗi khi lưu tin nhắn AI:', error));
        }

        function sendMessage() {
            let input = document.getElementById("user-input");
            let chatBox = document.getElementById("chat-box");
            let stopButton = document.getElementById("stop-button");
            let sendButton = document.getElementById("send-button");

            if (!input.value.trim()) return;

            if (currentEventSource) {
                currentEventSource.close();
                currentEventSource = null;
            }

            let userMessage = document.createElement("div");
            userMessage.className = "message user";
            userMessage.innerHTML = `<p>${input.value}</p>`;
            chatBox.appendChild(userMessage);

            const messageToSend = input.value.trim();
            input.value = "";

            sendButton.disabled = true;
            stopButton.disabled = false;

            fetch('/api/save-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    conversation_id: currentConversationId,
                    role: 'user',
                    content: messageToSend
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    currentConversationId = data.conversation_id;
                    fetchHistory();
                    chatBox.scrollTop = chatBox.scrollHeight;

                    let timeoutId = setTimeout(() => {
                        if (currentEventSource) {
                            currentEventSource.close();
                            currentEventSource = null;
                            let aiMessageElement = document.createElement("div");
                            aiMessageElement.className = "message ai";
                            aiMessageElement.innerHTML = "<p>Kết nối đến AI đã hết thời gian chờ. Vui lòng thử lại.</p>";
                            chatBox.appendChild(aiMessageElement);
                            chatBox.scrollTop = chatBox.scrollHeight;
                            stopButton.disabled = true;
                            sendButton.disabled = false;

                            saveAIMessage(aiMessageElement);
                        }
                    }, 12000);

                    try {
                        currentEventSource = new EventSource(`/api/chat?prompt=${encodeURIComponent(messageToSend)}&conversation_id=${currentConversationId}`);

                        let aiMessageElement = document.createElement("div");
                        aiMessageElement.className = "message ai";
                        aiMessageElement.innerHTML = '<div class="loading-dots"><span></span><span></span><span></span></div>';
                        chatBox.appendChild(aiMessageElement);
                        chatBox.scrollTop = chatBox.scrollHeight;

                        currentEventSource.onmessage = function(event) {
                            clearTimeout(timeoutId);
                            const data = JSON.parse(event.data);
                            let processedText = data.message;
                            let formattedText = processMarkdown(processedText);
                            aiMessageElement.innerHTML = formattedText;
                            chatBox.scrollTop = chatBox.scrollHeight;
                        };

                        currentEventSource.addEventListener('done', function() {
                            if (currentEventSource) {
                                console.log("Sự kiện done được kích hoạt");
                                currentEventSource.close();
                                currentEventSource = null;
                                stopButton.disabled = true;
                                sendButton.disabled = false;
                                chatBox.scrollTop = chatBox.scrollHeight;

                                saveAIMessage(aiMessageElement);
                            }
                        });

                        currentEventSource.onerror = function(event) {
                            clearTimeout(timeoutId);
                            console.error("Lỗi SSE:", event);
                            if (currentEventSource) {
                                currentEventSource.close();
                                currentEventSource = null;
                            }
                            stopButton.disabled = true;
                            sendButton.disabled = false;
                            if (aiMessageElement.innerHTML === '<div class="loading-dots"><span></span><span></span><span></span></div>') {
                                aiMessageElement.innerHTML = "<p>Đã xảy ra lỗi khi kết nối với AI. Vui lòng kiểm tra máy chủ.</p>";
                            }
                            chatBox.scrollTop = chatBox.scrollHeight;
                            console.log("Kết nối stream đã đóng do lỗi");

                            saveAIMessage(aiMessageElement);
                        };
                    } catch (error) {
                        clearTimeout(timeoutId);
                        console.error("Lỗi khi tạo kết nối:", error);
                        let aiMessageElement = document.createElement("div");
                        aiMessageElement.className = "message ai";
                        aiMessageElement.innerHTML = "<p>Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối mạng.</p>";
                        chatBox.appendChild(aiMessageElement);
                        stopButton.disabled = true;
                        sendButton.disabled = false;
                        chatBox.scrollTop = chatBox.scrollHeight;

                        saveAIMessage(aiMessageElement);
                    }
                }
            });
        }

        function stopResponse() {
            let sendButton = document.getElementById("send-button");
            if (currentEventSource) {
                currentEventSource.close();
                currentEventSource = null;
                document.getElementById("stop-button").disabled = true;
                sendButton.disabled = false;
                console.log("Đã dừng phản hồi từ AI");
            }
        }

        function processMarkdown(text) {
            text = text.replace(/```([\s\S]*?)```/g, function(match, code) {
                return '<pre><code>' + code.trim() + '</code></pre>';
            });
            text = text.replace(/`([^`]+)`/g, '<code>$1</code>');
            text = text.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\n\n/g, '</p><p>');
            text = text.replace(/\n/g, '<br>');
            if (!text.startsWith('<p>')) {
                text = '<p>' + text;
            }
            if (!text.endsWith('</p>')) {
                text = text + '</p>';
            }
            return text;
        }

        document.addEventListener("DOMContentLoaded", initializeChat);
    </script>
</body>
</html>
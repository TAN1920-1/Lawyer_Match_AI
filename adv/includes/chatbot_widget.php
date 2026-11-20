<!-- Chatbot Widget -->
<div class="chatbot-container">
    <div class="chat-window glass-panel" id="chatWindow">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-robot mr-2"></i>
                <h6 class="mb-0">Legal AI Assistant</h6>
            </div>
            <button class="btn btn-sm text-white" onclick="toggleChat()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="chat-body" id="chatBody">
            <div class="chat-message bot animate-fade-in">
                Hello! I'm your AI Legal Assistant. I can help you find a lawyer or answer legal questions. How can I help you today?
            </div>
        </div>
        
        <div class="p-3 bg-white border-top">
            <div class="input-group">
                <input type="text" id="chatInput" class="form-control border-0" placeholder="Type a message..." onkeypress="handleEnter(event)">
                <div class="input-group-append">
                    <button class="btn btn-primary rounded-circle ml-2" onclick="sendMessage()" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="chatbot-toggle" onclick="toggleChat()">
        <i class="fas fa-robot"></i>
    </div>
</div>

<script src="js/chatbot.js"></script>

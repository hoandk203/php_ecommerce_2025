<style>
.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    z-index: 1000;
    display: none;
}

.chatbot-header {
    background: #212529;
    color: white;
    padding: 15px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-messages {
    height: 300px;
    overflow-y: auto;
    padding: 15px;
}

.suggested-questions {
    padding: 10px 15px;
    border-top: 1px solid #eee;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.suggested-question {
    background: #e3f2fd;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.2s;
}

.suggested-question:hover {
    background: #bbdefb;
}

.chatbot-input {
    padding: 15px;
    border-top: 1px solid #eee;
}

.message {
    margin-bottom: 10px;
    max-width: 80%;
}

.user-message {
    background: #e9ecef;
    padding: 10px;
    border-radius: 10px;
    margin-left: auto;
}

.bot-message {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 10px;
}

.chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #212529;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    z-index: 999;
    color: #fff;
}

.chatbot-toggle i {
    color: white;
    font-size: 24px;
}
</style>

<!-- Nút mở chatbot -->
<div class="chatbot-toggle" onclick="toggleChatbot()">
    <i class="fas fa-comments"></i>
</div>

<!-- Container chatbot -->
<div class="chatbot-container" id="chatbot">
    <div class="chatbot-header">
        <div>
            <strong>Hỗ trợ khách hàng</strong>
        </div>
        <div style="cursor: pointer;" onclick="toggleChatbot()">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
        <div class="message bot-message">
            Xin chào! Tôi có thể giúp gì cho bạn?
        </div>
    </div>
    <div class="suggested-questions" id="suggested-questions">
        <!-- Câu hỏi gợi ý sẽ được thêm vào đây -->
    </div>
    <div class="chatbot-input">
        <div class="input-group">
            <input type="text" class="form-control" id="user-input" 
                   placeholder="Nhập câu hỏi của bạn..."
                   onkeypress="if(event.key === 'Enter') sendMessage()">
            <button class="btn btn-dark" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
function toggleChatbot() {
    const chatbot = document.getElementById('chatbot');
    chatbot.style.display = chatbot.style.display === 'none' ? 'block' : 'none';
}

function appendMessage(message, isUser = false) {
    const messagesDiv = document.getElementById('chatbot-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    messageDiv.textContent = message;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('user-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Hiển thị tin nhắn của người dùng
    appendMessage(message, true);
    input.value = '';
    
    try {
        // Gửi request đến server
        const response = await fetch('/chatbot/response', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        // Hiển thị câu trả lời từ chatbot
        appendMessage(data.response);
    } catch (error) {
        appendMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.');
    }
}

async function loadSuggestedQuestions() {
    try {
        const response = await fetch('/chatbot/suggestions');
        const data = await response.json();
        
        const suggestionsDiv = document.getElementById('suggested-questions');
        suggestionsDiv.innerHTML = '';
        
        data.suggestions.forEach(question => {
            const questionDiv = document.createElement('div');
            questionDiv.className = 'suggested-question';
            questionDiv.textContent = question;
            questionDiv.onclick = () => {
                document.getElementById('user-input').value = question;
                sendMessage();
            };
            suggestionsDiv.appendChild(questionDiv);
        });
    } catch (error) {
        console.error('Error loading suggestions:', error);
    }
}

// Hiển thị chatbot và load câu hỏi gợi ý khi load trang
document.addEventListener('DOMContentLoaded', () => {
    const chatbot = document.getElementById('chatbot');
    chatbot.style.display = 'none';
    loadSuggestedQuestions();
});
</script> 
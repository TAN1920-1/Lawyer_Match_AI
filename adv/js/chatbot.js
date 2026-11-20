document.addEventListener('DOMContentLoaded', () => {
    // Focus input on load if in full page mode
    const chatInput = document.getElementById('chatInput');
    // Check if we are in full page mode (chatbot.php) or widget mode
    if (document.querySelector('.chat-layout')) {
        if (chatInput) chatInput.focus();
    }
});

function toggleChat() {
    const chatWindow = document.getElementById('chatWindow');
    if (chatWindow) {
        chatWindow.classList.toggle('active');
        if (chatWindow.classList.contains('active')) {
            setTimeout(() => {
                document.getElementById('chatInput').focus();
            }, 300);
        }
    }
}

function handleEnter(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (message === '') return;

    // Add user message
    addMessage(message, 'user');
    input.value = '';

    // Add loading indicator
    const loadingId = addLoadingIndicator();
    
    fetch('chatbot_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        removeLoadingIndicator(loadingId);
        processBotResponse(data.reply);
    })
    .catch(error => {
        console.error('Error:', error);
        removeLoadingIndicator(loadingId);
        addMessage("I apologize, but I'm having trouble connecting right now. Please try again later.", 'bot');
    });
}

function processBotResponse(response) {
    // Check for SEARCH_ACTION
    const searchActionRegex = /\|\|SEARCH_ACTION:(.*?)\|\|/;
    const match = response.match(searchActionRegex);

    let cleanResponse = response;
    let searchAction = null;

    if (match) {
        try {
            searchAction = JSON.parse(match[1]);
            // Remove the action block from the display text
            cleanResponse = response.replace(match[0], '').trim();
        } catch (e) {
            console.error("Failed to parse search action", e);
        }
    }

    addMessage(cleanResponse, 'bot');

    if (searchAction) {
        // Delay slightly for effect
        setTimeout(() => {
            performSearch(searchAction);
        }, 1500);
    }
}

function performSearch(action) {
    const params = new URLSearchParams();
    params.append('search', 'true'); // Trigger the isset($_GET['search']) check
    
    if (action.speciality) params.append('speciality', action.speciality);
    if (action.location) params.append('location', action.location);
    if (action.experience) params.append('experience', action.experience);

    // Show a system message about redirecting
    addMessage(`I'm searching for ${action.speciality || 'lawyers'} in ${action.location || 'your area'}...`, 'bot');
    
    setTimeout(() => {
        window.location.href = `searchLawyer.php?${params.toString()}`;
    }, 1000);
}

function addMessage(text, sender) {
    if (!text) return; // Don't add empty messages
    
    const chatBody = document.getElementById('chatBody');
    const div = document.createElement('div');
    div.className = `chat-message ${sender} animate-fade-in`;
    
    // Convert newlines to <br> for better formatting
    // Basic formatting: bold **text** -> <strong>text</strong>
    let formattedText = text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\n/g, '<br>');

    div.innerHTML = formattedText;
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function addLoadingIndicator() {
    const chatBody = document.getElementById('chatBody');
    const div = document.createElement('div');
    div.className = 'chat-message bot typing-indicator';
    div.id = 'loading-' + Date.now();
    div.innerHTML = '<span></span><span></span><span></span>';
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
    return div.id;
}

function removeLoadingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

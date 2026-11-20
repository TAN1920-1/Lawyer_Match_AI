<div class="chatbot-container">
    <div class="chat-window glass-panel" id="directTalkWindow">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-comments mr-2"></i>
                <h6 class="mb-0">Direct Talk</h6>
            </div>
            <button class="btn btn-sm text-white" onclick="toggleDirectTalk()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-body" id="directTalkBody">
            <div class="mb-2">Share or enter the code and your name.</div>
            <div class="form-group">
                <label>Code</label>
                <input type="text" id="directTalkCode" class="form-control" placeholder="Enter code">
            </div>
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" id="directTalkName" class="form-control" placeholder="Enter your name">
            </div>
            <div class="d-flex justify-content-between">
                <a class="btn btn-outline-modern" href="https://aetherastra.vercel.app/direct" target="_blank">Open Page</a>
                <button class="btn btn-modern" onclick="joinDirectTalk()">Join Talk</button>
            </div>
        </div>
    </div>
    <div class="chatbot-toggle" onclick="toggleDirectTalk()" style="bottom: 100px; right: 20px;">
        <i class="fas fa-comments"></i>
    </div>
</div>

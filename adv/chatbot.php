<?php
    include_once 'includes/session_init.php';
    include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AI Legal Assistant - Lawyer Match AI</title>
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/modern_style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-balance-scale mr-2"></i>Lawyer Match AI
            </a>
            <div class="ml-auto">
                <a href="index.php" class="btn btn-outline-modern btn-sm">Back to Home</a>
            </div>
        </div>
    </nav>

    <div class="chat-layout">
        <div class="chat-container-full animate-fade-in">
            <div class="chat-header-full">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-robot fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Legal AI Assistant</h5>
                        <small style="opacity: 0.9;">Always here to help</small>
                    </div>
                </div>
            </div>
            
            <div class="chat-body-full" id="chatBody">
                <div class="chat-message bot">
                    Hello! I'm your dedicated AI Legal Assistant. I can help you understand legal issues, find the right lawyer, or guide you through our process. How can I assist you today?
                </div>
            </div>
            
            <div class="chat-input-area">
                <input type="text" id="chatInput" class="chat-input" placeholder="Type your legal question here..." onkeypress="handleEnter(event)" style="padding: 15px 25px;">
                <button onclick="sendMessage()" class="chat-send-btn" style="width: 50px; height: 50px;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="js/chatbot.js"></script>
</body>
</html>

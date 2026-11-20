<?php
include_once 'includes/session_init.php';
header('Content-Type: application/json');

// Include configuration
require_once 'db_con/config.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = isset($input['message']) ? trim($input['message']) : '';

if (empty($userMessage)) {
    echo json_encode(['reply' => 'Please type a message.']);
    exit;
}

// Initialize chat history if not set
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [
        [
            'role' => 'system',
            'content' => "You are 'Lexi', the Advanced Legal AI Assistant for 'Lawyer Match AI'.
            
            Your Core Mission:
            To bridge the gap between people and justice by providing instant, accurate, and empathetic legal guidance and connecting users with the perfect lawyer.

            Personality:
            - Professional yet accessible (avoid overly dense legalese).
            - Empathetic and reassuring (legal issues are stressful).
            - Intelligent and proactive (anticipate user needs).
            - Futuristic and efficient.

            Operational Rules:
            1. **No Legal Advice**: Never provide specific legal advice or guarantee outcomes. Always clarify you are an AI and recommend consulting a verified lawyer from our platform.
            2. **Smart Matching**: If a user needs a lawyer, ALWAYS ask for their 'Location' and 'Legal Issue' (e.g., Divorce, Property, Criminal).
            3. **Context Awareness**: Remember previous details in the conversation.
            4. **Search Trigger**: If the user provides both a location and an issue, or explicitly asks to find a lawyer with specific criteria, you MUST trigger the search action.
            
            Search Action Protocol:
            Append a JSON block at the very end of your response (after your helpful text) if you have enough info to search.
            Format: ||SEARCH_ACTION:{\"speciality\": \"<Category>\", \"location\": \"<City>\", \"experience\": \"<Optional>\"}||
            
            Supported Specialities:
            'Criminal law', 'Civil law', 'Writ Jurisdiction', 'Company law', 'Contract law', 'Commercial law', 'Construction law', 'IT law', 'Family law', 'Religious law', 'Investment law', 'Labour law', 'Property law', 'Taxation law'.
            
            Supported Locations:
            'Mumbai', 'Nashik', 'Pune', 'Delhi', 'Nagpur', 'Jalgaon', 'Sambhaji Nagar', 'Thane'.

            Example Interaction:
            User: 'I need a divorce lawyer in Pune.'
            Lexi: 'I can certainly help with that. Finding the right representation is crucial for family matters. I have located several top-rated family lawyers in Pune for you. Redirecting you to their profiles now... ||SEARCH_ACTION:{\"speciality\": \"Family law\", \"location\": \"Pune\"}||'
            "
        ]
    ];
}

// Add user message to history
$_SESSION['chat_history'][] = ['role' => 'user', 'content' => $userMessage];

// Keep history manageable (last 6 messages + system prompt)
if (count($_SESSION['chat_history']) > 7) {
    // Keep system prompt (index 0) and last 6 messages
    $systemPrompt = $_SESSION['chat_history'][0];
    $recentMessages = array_slice($_SESSION['chat_history'], -6);
    $_SESSION['chat_history'] = array_merge([$systemPrompt], $recentMessages);
}

// Call AI (configurable provider with fallback)
$response = callAIProvider($_SESSION['chat_history']);

if ($response) {
    // Add bot response to history
    $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $response];
    echo json_encode(['reply' => $response]);
} else {
    echo json_encode(['reply' => "I apologize, but I'm having trouble connecting to my knowledge base right now. Please try again in a moment."]);
}

function callAIProvider($messages) {
    $apiKey = defined('AI_CHATBOT_API_KEY') ? AI_CHATBOT_API_KEY : '';
    if (!$apiKey) {
        return "API key is not configured. Please set AI_CHATBOT_API_KEY in the server environment.";
    }

    $provider = defined('AI_PROVIDER') ? AI_PROVIDER : 'openrouter';
    $model = defined('AI_MODEL') ? AI_MODEL : 'openrouter/auto';
    $url = defined('AI_BASE_URL') ? AI_BASE_URL : 'https://openrouter.ai/api/v1/chat/completions';
    
    // For Google Gemini API, we might need to append the key to the URL if it's not in headers
    if ($provider === 'google' && strpos($url, '?key=') === false) {
        $url .= '?key=' . $apiKey;
    }

    $data = [];
    if ($provider === 'google') {
        // Google Gemini format
        $contents = [];
        $systemInstruction = null;
        
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemInstruction = ['parts' => [['text' => $msg['content']]]];
                continue;
            }
            
            $role = ($msg['role'] === 'assistant') ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }
        
        $data = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 1,
                'topP' => 0.95,
                'topK' => 64,
                'maxOutputTokens' => 8192,
            ]
        ];
        
        if ($systemInstruction) {
            $data['systemInstruction'] = $systemInstruction;
        }
    } else {
        // OpenAI / OpenRouter format
        $oaMessages = [];
        foreach ($messages as $msg) {
            $oaMessages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }
        $data = [
            'model' => $model,
            'messages' => $oaMessages,
            'temperature' => 0.7,
            'max_tokens' => 500
        ];
    }

    $ch = curl_init($url);
    if (!$ch) {
        return "Failed to initialize network request. Please check your configuration.";
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $headers = ['Content-Type: application/json'];
    
    if ($provider !== 'google') {
        $scheme = 'http';
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')) {
            $scheme = 'https';
        }
        $origin = isset($_SERVER['HTTP_HOST']) ? ($scheme . '://' . $_SERVER['HTTP_HOST'] . '/') : ($scheme . '://localhost/');
        $headers[] = 'Accept: application/json';
        $headers[] = 'Referer: ' . $origin;
        $headers[] = 'X-Title: Lawyer Match AI';
        $headers[] = 'User-Agent: LawyerMatchAI/1.0';
        
        $headerName = defined('AI_HEADER_NAME') ? AI_HEADER_NAME : 'Authorization';
        if (strcasecmp($headerName, 'Authorization') === 0) {
            $headers[] = 'Authorization: Bearer ' . $apiKey;
        } else {
            $headers[] = $headerName . ': ' . $apiKey;
        }
    } else {
        // For Google, we can also put key in header
        $headers[] = 'x-goog-api-key: ' . $apiKey;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrno = curl_errno($ch);
    $curlError = curl_error($ch);
    
    // In PHP 8.0+, curl_close is a no-op as the CurlHandle object is destroyed when it goes out of scope.
    unset($ch);
    
    if ($curlErrno) {
        return false;
    }

    $response = json_decode($result, true);

    if ($httpCode >= 400) {
        $msg = '';
        if (isset($response['error']['message'])) {
            $msg = $response['error']['message'];
        } elseif (isset($response[0]['error']['message'])) {
            $msg = $response[0]['error']['message']; // Sometimes Google returns array
        }
        
        // If quota exceeded or rate limited, try external fallback first, then local rule-based
        if ($httpCode === 429 || stripos($msg, 'quota') !== false || stripos($msg, 'limit') !== false) {
            $fallback = attemptFallback($messages);
            if ($fallback) return $fallback;
            
            // If external fallback also fails or isn't configured, use local rules
            return getLocalFallbackResponse($messages);
        }
        
        // For other errors, also try local fallback to maintain service
        $localFallback = getLocalFallbackResponse($messages);
        if ($localFallback) return $localFallback;

        if ($msg) {
            return "I encountered an error processing your request: " . $msg;
        }
        return "Request failed (HTTP " . $httpCode . "). Please try again later.";
    }

    // Parse response based on provider
    if ($provider === 'google') {
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            return $response['candidates'][0]['content']['parts'][0]['text'];
        }
    } else {
        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }
    }

    if (isset($response['error']['message'])) {
        return "I encountered an error processing your request: " . $response['error']['message'];
    }
    
    // Final safety net: local fallback
    return getLocalFallbackResponse($messages);
}

function getLocalFallbackResponse($messages) {
    $lastMessage = '';
    foreach (array_reverse($messages) as $msg) {
        if ($msg['role'] === 'user') {
            $lastMessage = strtolower($msg['content']);
            break;
        }
    }

    $response = "I am currently operating in **Basic Mode** because our advanced AI is experiencing high demand. I can still help you with basic platform navigation.\n\n";

    // Rule-based matching
    if (preg_match('/(hello|hi|hey|greetings)/', $lastMessage)) {
        $response .= "Hello! How can I assist you today? You can ask me how to find a lawyer or register on the platform.";
    } elseif (preg_match('/(find|search|look for|need|want).*(lawyer|advocate|expert)/', $lastMessage)) {
        $response .= "To find a lawyer, please go to the **Find Lawyers** page from the navigation menu. You can filter by speciality (like Criminal or Family law) and location (like Mumbai or Pune).";
    } elseif (preg_match('/(register|sign up|create account|join)/', $lastMessage)) {
        $response .= "You can register as either a **Lawyer** or a **User**. Click the 'Get Started' button in the top navigation bar to choose your account type.";
    } elseif (preg_match('/(mumbai|pune|delhi|nagpur|nashik)/', $lastMessage) && preg_match('/(divorce|criminal|civil|property)/', $lastMessage)) {
        // Extract city and speciality if possible for a search trigger
        $cities = ['mumbai', 'nashik', 'pune', 'delhi', 'nagpur', 'jalgaon', 'sambhaji nagar', 'thane'];
        $specs = ['criminal law', 'civil law', 'family law', 'property law', 'it law'];
        
        $foundCity = '';
        $foundSpec = '';
        foreach($cities as $c) if(strpos($lastMessage, $c) !== false) $foundCity = ucfirst($c);
        foreach($specs as $s) if(strpos($lastMessage, $s) !== false) $foundSpec = ucwords($s);
        
        if($foundCity && $foundSpec) {
            $response .= "I've understood you're looking for a **$foundSpec** in **$foundCity**. I'll redirect you to our matching results immediately.\n\n||SEARCH_ACTION:{\"speciality\": \"$foundSpec\", \"location\": \"$foundCity\"}||";
        } else {
            $response .= "I see you're looking for legal help. Please tell me your city and the type of law you need assistance with.";
        }
    } elseif (preg_match('/(who are you|your name|what is lexi)/', $lastMessage)) {
        $response .= "I am **Lexi**, your AI Legal Assistant. While my advanced brain is currently resting, I can still guide you through our platform features.";
    } else {
        $response .= "I'm sorry, I couldn't process that complex request in Basic Mode. Please try again in a few minutes when our advanced systems are back online, or ask me about finding a lawyer or registering.";
    }

    return $response;
}

function attemptFallback($messages) {
    if (!defined('AI_FALLBACK_KEY') || AI_FALLBACK_KEY === '' || !defined('AI_FALLBACK_BASE_URL') || AI_FALLBACK_BASE_URL === '') {
        return false;
    }
    $oaMessages = [];
    foreach ($messages as $msg) {
        $oaMessages[] = [
            'role' => $msg['role'],
            'content' => $msg['content']
        ];
    }
    $data = [
        'model' => (defined('AI_FALLBACK_MODEL') && AI_FALLBACK_MODEL !== '' ? AI_FALLBACK_MODEL : 'openrouter/auto'),
        'messages' => $oaMessages,
        'temperature' => 0.7,
        'max_tokens' => 500
    ];
    $ch = curl_init(AI_FALLBACK_BASE_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $scheme = 'http';
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')) {
        $scheme = 'https';
    }
    $origin = isset($_SERVER['HTTP_HOST']) ? ($scheme . '://' . $_SERVER['HTTP_HOST'] . '/') : ($scheme . '://localhost/');
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Referer: ' . $origin,
        'X-Title: Lawyer Match AI'
    ];
    $headerName = (defined('AI_FALLBACK_HEADER_NAME') && AI_FALLBACK_HEADER_NAME !== '') ? AI_FALLBACK_HEADER_NAME : 'Authorization';
    if (strcasecmp($headerName, 'Authorization') === 0) {
        $headers[] = 'Authorization: Bearer ' . AI_FALLBACK_KEY;
    } else {
        $headers[] = $headerName . ': ' . AI_FALLBACK_KEY;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrno = curl_errno($ch);
    
    // In PHP 8.0+, curl_close is unnecessary for CurlHandle objects.
    unset($ch);

    if ($curlErrno) {
        return false;
    }
    if ($httpCode >= 400) {
        return false;
    }
    
    $response = json_decode($result, true);
    if (isset($response['choices'][0]['message']['content'])) {
        return $response['choices'][0]['message']['content'];
    }
    return false;
}
?>

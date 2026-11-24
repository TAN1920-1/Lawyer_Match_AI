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

// Initialize chat history if not set (kept so the AI enhancement can stay context aware)
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
    $systemPrompt = $_SESSION['chat_history'][0];
    $recentMessages = array_slice($_SESSION['chat_history'], -6);
    $_SESSION['chat_history'] = array_merge([$systemPrompt], $recentMessages);
}

// 1) FAQ knowledge base is the primary responder (works with no API / no quota).
$faq = getFaqResponse($userMessage);
if ($faq !== null) {
    $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $faq];
    echo json_encode(['reply' => $faq]);
    exit;
}

// 2) For anything the FAQ does not cover, try the AI provider (optional enhancement).
$response = callAIProvider($_SESSION['chat_history']);

if ($response) {
    $_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $response];
    echo json_encode(['reply' => $response]);
} else {
    // 3) Final safety net: never leave the user without an answer.
    echo json_encode(['reply' => getFaqHelp()]);
}

/* -------------------------------------------------------------------------
 * FAQ knowledge base ("training" data for the chatbot).
 * Answers are matched by intent using keyword + pattern scoring, so the
 * assistant keeps working even when the external AI provider is unavailable.
 * ---------------------------------------------------------------------- */

function lm_specialities() {
    return [
        'Criminal law', 'Civil law', 'Writ Jurisdiction', 'Company law', 'Contract law',
        'Commercial law', 'Construction law', 'IT law', 'Family law', 'Religious law',
        'Investment law', 'Labour law', 'Property law', 'Taxation law'
    ];
}

function lm_locations() {
    return ['Mumbai', 'Nashik', 'Pune', 'Delhi', 'Nagpur', 'Jalgaon', 'Sambhaji Nagar', 'Thane'];
}

/**
 * Map common everyday words to a supported speciality.
 */
function lm_speciality_map() {
    return [
        'Criminal law'      => ['criminal', 'crime', 'theft', 'murder', 'arrest', 'bail', 'fir', 'police', 'robbery', 'assault', 'cheque bounce', 'cheating'],
        'Family law'        => ['family', 'divorce', 'marriage', 'custody', 'maintenance', 'alimony', 'matrimonial', 'domestic'],
        'Property law'      => ['property', 'land', 'real estate', 'rent', 'tenant', 'landlord', 'possession', 'title', 'ownership', 'partition'],
        'Taxation law'      => ['tax', 'taxation', 'gst', 'income tax', 'tds'],
        'Labour law'        => ['labour', 'labor', 'employment', 'employee', 'termination', 'salary', 'wages', 'workplace', 'wrongful dismissal'],
        'Company law'       => ['company law', 'company', 'corporate', 'incorporation', 'shareholder', 'director', 'startup'],
        'Contract law'      => ['contract', 'agreement', 'breach of contract'],
        'Commercial law'    => ['commercial', 'business dispute', 'trade', 'merchant'],
        'Construction law'  => ['construction', 'builder', 'contractor'],
        'IT law'            => ['it law', 'cyber', 'cybercrime', 'information technology', 'data privacy', 'internet law', 'online fraud'],
        'Writ Jurisdiction' => ['writ', 'writ petition', 'habeas corpus'],
        'Religious law'     => ['religious', 'religion', 'temple', 'church', 'mosque'],
        'Investment law'    => ['investment', 'sebi', 'stock', 'share trading', 'mutual fund'],
        'Civil law'         => ['civil', 'injunction', 'damages', 'civil suit']
    ];
}

function lm_detect_speciality($text) {
    foreach (lm_speciality_map() as $canonical => $keys) {
        foreach ($keys as $k) {
            if (strpos($text, $k) !== false) {
                return $canonical;
            }
        }
    }
    return '';
}

function lm_detect_location($text) {
    $aliases = [
        'Sambhaji Nagar' => ['sambhaji nagar', 'sambhajinagar', 'aurangabad', 'chhatrapati sambhaji nagar'],
        'Mumbai' => ['mumbai', 'bombay', 'navi mumbai'],
        'Nashik' => ['nashik', 'nasik'],
        'Pune' => ['pune', 'poona'],
        'Delhi' => ['delhi', 'new delhi'],
        'Nagpur' => ['nagpur'],
        'Jalgaon' => ['jalgaon'],
        'Thane' => ['thane']
    ];
    foreach ($aliases as $canonical => $keys) {
        foreach ($keys as $k) {
            if (strpos($text, $k) !== false) {
                return $canonical;
            }
        }
    }
    return '';
}

function lm_wants_lawyer($text) {
    $a = preg_match('/\b(find|search|look(ing)? for|need|want|require|suggest|recommend|hire|book|connect|get|consult)\b[^.]{0,40}\b(lawyer|advocate|attorney|vakil|law firm|firm)\b/', $text);
    $b = preg_match('/\b(lawyer|advocate|attorney|vakil)\b[^.]{0,25}\b(near|in|around|at)\b/', $text);
    return (bool) ($a || $b);
}

/**
 * The FAQ intents. Each entry is scored against the user message; the highest
 * scoring intent above the threshold wins.
 */
function lm_faq_intents() {
    $spec = implode(', ', lm_specialities());
    $loc  = implode(', ', lm_locations());
    return [
        [
            'id' => 'greeting',
            'patterns' => ['/^\s*(hi|hii+|hello|hey|yo|hola)\b/', '/\bgood (morning|afternoon|evening)\b/', '/^\s*hi\b/'],
            'answer' => "Hello! I'm **Lexi**, your AI Legal Assistant for Lawyer Match AI. I can help you find a lawyer, guide you through registration, and answer common questions. What would you like to do today?"
        ],
        [
            'id' => 'thanks',
            'patterns' => ['/\b(thank you|thanks|thank u|thx|appreciate)\b/'],
            'answer' => "You're most welcome! If you need anything else — finding a lawyer or understanding a feature — just ask. Good luck!"
        ],
        [
            'id' => 'bye',
            'patterns' => ['/\b(bye|goodbye|see you|see ya|good night)\b/'],
            'answer' => "Goodbye and take care! Remember, you can return anytime to find or book a verified lawyer on Lawyer Match AI."
        ],
        [
            'id' => 'identity',
            'patterns' => ['/\b(who are you|your name|what is lexi|what can you do|what do you do|how can you help|what are you)\b/'],
            'answer' => "I'm **Lexi**, the AI Legal Assistant for **Lawyer Match AI**. I can:\n- Help you find a lawyer by speciality and city\n- Guide you through registering as a User, Lawyer, or Firm\n- Explain bookings, dashboards, and account settings\n- Answer common questions about the platform\n\nNote: I provide general guidance, not legal advice — for your specific case please consult a verified lawyer."
        ],
        [
            'id' => 'about',
            'patterns' => ['/\b(about|what is lawyer match|lawyer match ai)\b/', '/\bhow does (this|the) (site|platform|app) work\b/'],
            'answer' => "**Lawyer Match AI** connects people with the right legal experts. You can search lawyers and law firms by speciality (Family, Criminal, Property, Company, etc.) and city, view detailed profiles, and book appointments online. It's designed to make finding legal help fast and transparent."
        ],
        [
            'id' => 'specialities',
            'patterns' => ['/\b(types? of law|specialit(y|ies)|practice areas?|areas of law|which law|categories)\b/'],
            'answer' => "We cover these specialities:\n- " . str_replace(', ', "\n- ", $spec) . "\n\nTell me your city and the type of issue, and I'll match you with the right lawyer."
        ],
        [
            'id' => 'locations',
            'patterns' => ['/\b(which (cities|locations|city)|available (cities|locations)|where.*(available|operate)|do you (serve|cover))\b/'],
            'answer' => "We currently serve these cities:\n- " . str_replace(', ', "\n- ", $loc) . "\n\nTell me your city and your legal issue, and I'll find matching lawyers for you."
        ],
        [
            'id' => 'find_firm',
            'patterns' => ['/\b(law firm|law firms|find firm|search firm|company of lawyers)\b/'],
            'answer' => "To find a **law firm**, open **Find Law Firms** from the navigation menu (searchFirm.php). You can browse firms by name, city, and speciality, view their team, and book with the firm directly."
        ],
        [
            'id' => 'find_lawyer',
            'patterns' => ['/\b(find|search|look(ing)? for|need|want|hire|get)\b[^.]{0,30}\b(lawyer|advocate|attorney|vakil)\b/'],
            'answer' => "To find a lawyer, go to the **Find Lawyers** page from the navigation menu. You can filter by **speciality** (e.g., Family law, Criminal law, Property law) and **city** (e.g., Mumbai, Pune, Nashik).\n\nJust tell me the type of issue and your city, and I can take you straight to the results."
        ],
        [
            'id' => 'register_user',
            'patterns' => ['/\b(register|sign ?up|create)\b[^.]{0,20}\b(user|client|customer|as user)\b/', '/\b(user|client) (registration|account)\b/'],
            'answer' => "To register as a **User** (client), open **Get Started → Register as User** (user_register.php). Fill in your name, email, contact number, and address. A temporary password is generated and emailed to you — use it to log in, then update it from your dashboard."
        ],
        [
            'id' => 'register_lawyer',
            'patterns' => ['/\b(register|sign ?up|create)\b[^.]{0,20}\b(lawyer|advocate|attorney)\b/', '/\b(lawyer|advocate) (registration|account|sign ?up)\b/'],
            'answer' => "To register as a **Lawyer**, open **Get Started → Register as Lawyer** (lawyer_register.php). Provide your details, speciality, and experience. Your account is created with **Pending** status and must be approved by an admin before you can log in."
        ],
        [
            'id' => 'register_firm',
            'patterns' => ['/\b(register|sign ?up|create)\b[^.]{0,20}\b(firm|company|organisation|organization)\b/', '/\bfirm (registration|account|sign ?up)\b/'],
            'answer' => "To register a **Firm**, open **Get Started → Register as Firm** (firm_register.php). Enter your firm details; a login password is emailed to you. Firm accounts also require **admin approval** before you can log in."
        ],
        [
            'id' => 'register_general',
            'patterns' => ['/\b(register|sign ?up|sign-up|create (an )?account|join|get started|new account)\b/'],
            'answer' => "To get started, click **Get Started** in the top navigation bar (register_options.php). You can register as:\n- **User** – to find and book lawyers\n- **Lawyer** – to offer your services (subject to admin approval)\n- **Firm** – to manage a team of lawyers\n\nWhich one would you like to create?"
        ],
        [
            'id' => 'login_issue',
            'patterns' => ['/\b(can\'?t|unable to|cannot|not able to|problem|issue|trouble)\b[^.]{0,20}\blog ?in\b/', '/\blog ?in\b[^.]{0,20}\b(not working|fails|failing|error)\b/', '/\bincorrect (email|password)\b/'],
            'answer' => "Sorry you're having trouble logging in. Please check:\n1. The **email** and **password** are exactly as registered.\n2. You selected the correct **role** (User, Lawyer, Admin, Firm).\n3. Lawyer/Firm accounts are **Active** (approved by admin).\n\nIf it still fails, you can reset your password after logging in — or contact us at lawyermatch.ai@gmail.com."
        ],
        [
            'id' => 'forgot_password',
            'patterns' => ['/\b(forgot|reset|recover|change|update|new)\b[^.]{0,20}\bpassword\b/'],
            'answer' => "Password help:\n- If you are already logged in, go to your dashboard → **Update Password** (update_password.php) and enter your current and new password.\n- If you forgot your password, please email **lawyermatch.ai@gmail.com** with your registered email and we'll help you reset it."
        ],
        [
            'id' => 'update_password',
            'patterns' => ['/\bupdate password\b/', '/\bhow (do i|to) change my password\b/'],
            'answer' => "To change your password: log in → open your **Dashboard** → **Update Password**. Enter your current password and the new one (minimum 6 characters), then click **Update Password**."
        ],
        [
            'id' => 'approval_pending',
            'patterns' => ['/\b(pending|approval|not approved|not active|awaiting|under review)\b/'],
            'answer' => "Lawyer and Firm accounts are reviewed by an admin before activation. If your account shows as **Pending**, please wait for approval — you'll be able to log in once it becomes **Active**. For urgent cases, contact lawyermatch.ai@gmail.com."
        ],
        [
            'id' => 'booking',
            'patterns' => ['/\b(book|appointment|schedule|consult|meeting)\b/'],
            'answer' => "To book an appointment:\n1. Find a lawyer on the **Find Lawyers** page and open their profile.\n2. Click **Book Appointment** and describe your issue.\n3. Submit — the lawyer will see the request on their dashboard.\n\nYou can track all your requests under **My Bookings** in your dashboard."
        ],
        [
            'id' => 'my_bookings',
            'patterns' => ['/\b(my bookings|booking status|status of.*booking|cancel.*booking|view bookings|my appointments)\b/'],
            'answer' => "You can view and manage your appointments from your **Dashboard → My Bookings** (user_booking.php). There you'll see each request, its date, and its current status."
        ],
        [
            'id' => 'fees',
            'patterns' => ['/\b(fee|fees|price|pricing|cost|charge|charges|payment|pay|how much)\b/'],
            'answer' => "Fees are set by each lawyer or firm individually, so they vary by speciality, experience, and city. You'll find the details on the lawyer's profile, and you can confirm the consultation fee when you book the appointment."
        ],
        [
            'id' => 'verified',
            'patterns' => ['/\b(verified|trust|trusted|genuine|background|authentic|safe lawyer)\b/'],
            'answer' => "Every lawyer and firm on Lawyer Match AI is reviewed by our admin team before being listed. You can also check ratings and reviews on profiles to choose the right expert with confidence."
        ],
        [
            'id' => 'ratings',
            'patterns' => ['/\b(rate|rating|review|reviews|feedback|stars?)\b/'],
            'answer' => "After working with a lawyer through the platform, you can leave a **rating and review** from your booking. Ratings help other users make informed choices and are shown on the lawyer's profile."
        ],
        [
            'id' => 'firm_features',
            'patterns' => ['/\b(add|manage|invite)\b[^.]{0,20}\b(lawyer|member|team)\b/', '/\bfirm dashboard\b/'],
            'answer' => "Firm admins can manage their team from the **Firm Dashboard**. Use **Firm Lawyers** (firm_lawyers.php) to add or remove lawyers — added lawyers automatically receive their login email — and use **Firm Bookings** to view client appointments."
        ],
        [
            'id' => 'notifications',
            'patterns' => ['/\b(notification|notifications|alert|bell|updates)\b/'],
            'answer' => "Notifications appear under the **bell icon** in the navigation bar. You'll be alerted about bookings, approvals, and other account activity there."
        ],
        [
            'id' => 'edit_profile',
            'patterns' => ['/\b(edit|update|change)\b[^.]{0,20}\b(profile|details|info|information)\b/'],
            'answer' => "To edit your profile, open your **Dashboard → Edit Profile**. Users, lawyers, and firms each have a profile page where you can update your details and photo."
        ],
        [
            'id' => 'contact',
            'patterns' => ['/\b(contact|support|help ?desk|customer care|reach (you|us)|email (you|us)|complain|complaint|grievance)\b/'],
            'answer' => "You can reach our team at **lawyermatch.ai@gmail.com**. You can also use the **Report a Bug** button (the bug icon at the bottom-left) to send us an issue directly. We'll get back to you as soon as possible."
        ],
        [
            'id' => 'report_bug',
            'patterns' => ['/\b(report|submit|raise)\b[^.]{0,20}\b(bug|issue|problem|error|glitch)\b/', '/\b(bug|not working|broken|error)\b/'],
            'answer' => "To report a bug, click the **bug icon** at the bottom-left of the page (or open report_bug.php). Fill in your details and describe the issue, then submit. It goes straight to our technical team."
        ],
        [
            'id' => 'privacy',
            'patterns' => ['/\b(privacy|data|secure|security|confidential|safe)\b/'],
            'answer' => "We take privacy seriously. Your account data is stored securely and only used to provide the matching and booking service. Your contact details are shared with a lawyer only when you book an appointment with them."
        ],
        [
            'id' => 'legal_advice',
            'patterns' => ['/\b(legal advice|can you advise|should i sue|will i win|what is the law on|is it legal)\b/'],
            'answer' => "I'm an AI assistant and I can't provide legal advice or predict outcomes. For guidance specific to your situation, please consult a verified lawyer on our platform — I can help you find the right one. Tell me your city and type of issue to begin."
        ]
    ];
}

/**
 * Return an FAQ answer, or null when nothing matches confidently enough
 * so the AI enhancement can take over.
 */
function getFaqResponse($message) {
    $text = ' ' . strtolower(preg_replace('/\s+/', ' ', $message)) . ' ';

    $spec = lm_detect_speciality($text);
    $loc  = lm_detect_location($text);

    // Lawyer / firm search intent handling (with the search redirect action).
    if (lm_wants_lawyer($text) || ($spec !== '' && $loc !== '')) {
        if ($spec !== '' && $loc !== '') {
            return "Great — I can help you find a **$spec** specialist in **$loc**. Let me pull up the matching lawyers for you now.\n\n||SEARCH_ACTION:{\"speciality\": \"$spec\", \"location\": \"$loc\"}||";
        }
        if ($loc !== '' && $spec === '') {
            return "I can find lawyers in **$loc**. What type of legal issue do you need help with? (e.g., Family law, Criminal law, Property law, Company law.)";
        }
        if ($spec !== '' && $loc === '') {
            return "I can find a **$spec** specialist for you. Which city are you in? We currently cover: " . implode(', ', lm_locations()) . ".";
        }
    }

    // Score the FAQ intents.
    $best = null;
    $bestScore = 0;
    foreach (lm_faq_intents() as $intent) {
        $score = 0;
        foreach ($intent['patterns'] as $p) {
            if (preg_match($p, $text)) {
                $score += 3;
            }
        }
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $intent;
        }
    }

    if ($best !== null && $bestScore >= 3) {
        return $best['answer'];
    }

    return null;
}

/**
 * Generic help message used when neither the FAQ nor the AI could answer.
 */
function getFaqHelp() {
    return "I'm not sure I understood that, but I can definitely help with these topics:\n- **Finding a lawyer or law firm** (tell me your city + type of issue)\n- **Registering** as a User, Lawyer, or Firm\n- **Booking appointments** and viewing your bookings\n- **Login / password** help\n- **Specialities and cities** we cover\n\nCould you rephrase, or ask about one of the above? You can also email us at lawyermatch.ai@gmail.com.";
}

/* -------------------------------------------------------------------------
 * Optional AI enhancement (used only when the FAQ above has no match).
 * ---------------------------------------------------------------------- */

function lm_last_user_message($messages) {
    foreach (array_reverse($messages) as $msg) {
        if (isset($msg['role']) && $msg['role'] === 'user') {
            return $msg['content'];
        }
    }
    return '';
}

function callAIProvider($messages) {
    $apiKey = defined('AI_CHATBOT_API_KEY') ? AI_CHATBOT_API_KEY : '';
    if (!$apiKey) {
        return false;
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
                'temperature' => 0.7,
                'topP' => 0.95,
                'topK' => 64,
                'maxOutputTokens' => 1024,
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
        return false;
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

        // If quota exceeded or rate limited, try the external fallback.
        if ($httpCode === 429 || stripos($msg, 'quota') !== false || stripos($msg, 'limit') !== false) {
            $fallback = attemptFallback($messages);
            if ($fallback) return $fallback;
        }

        error_log("Chatbot AI provider error (HTTP $httpCode): $msg");
        return false;
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

    return false;
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

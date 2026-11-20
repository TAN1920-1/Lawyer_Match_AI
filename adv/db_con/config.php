<?php
// Configuration settings
// Reads AI chatbot API key from environment for security.
// Set AI_CHATBOT_API_KEY in your server environment before running.
$key = getenv('AI_CHATBOT_API_KEY') ?: '';
if ($key === '') {
    $local = __DIR__ . '/config.local.php';
    if (file_exists($local)) {
        require_once $local;
        if (defined('AI_CHATBOT_API_KEY_LOCAL')) {
            $key = constant('AI_CHATBOT_API_KEY_LOCAL');
        }
    }
}
define('AI_CHATBOT_API_KEY', $key ?: '');

$provider = getenv('AI_PROVIDER') ?: (defined('AI_PROVIDER_LOCAL') ? constant('AI_PROVIDER_LOCAL') : '');
$baseUrl  = getenv('AI_BASE_URL') ?: (defined('AI_BASE_URL_LOCAL') ? constant('AI_BASE_URL_LOCAL') : '');
$model    = getenv('AI_MODEL') ?: (defined('AI_MODEL_LOCAL') ? constant('AI_MODEL_LOCAL') : '');
$header   = getenv('AI_HEADER_NAME') ?: (defined('AI_HEADER_NAME_LOCAL') ? constant('AI_HEADER_NAME_LOCAL') : '');

if ($provider === '' && preg_match('/^sk-or-v1/', AI_CHATBOT_API_KEY)) {
    $provider = 'openrouter';
}
if ($baseUrl === '' && $provider === 'openrouter') {
    $baseUrl = 'https://openrouter.ai/api/v1/chat/completions';
}
if ($baseUrl === '' && $provider === 'google') {
    $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
}
if ($model === '' && $provider === 'openrouter') {
    $model = 'openrouter/auto';
}
if ($header === '' && $provider === 'openrouter') {
    $header = 'Authorization';
}

define('AI_PROVIDER', $provider ?: 'openrouter');
define('AI_BASE_URL', $baseUrl ?: 'https://openrouter.ai/api/v1/chat/completions');
define('AI_MODEL', $model ?: 'openrouter/auto');
define('AI_HEADER_NAME', $header ?: 'Authorization');

$fkey   = getenv('AI_FALLBACK_KEY') ?: (defined('AI_FALLBACK_KEY_LOCAL') ? constant('AI_FALLBACK_KEY_LOCAL') : '');
$furl   = getenv('AI_FALLBACK_BASE_URL') ?: (defined('AI_FALLBACK_BASE_URL_LOCAL') ? constant('AI_FALLBACK_BASE_URL_LOCAL') : '');
$fmodel = getenv('AI_FALLBACK_MODEL') ?: (defined('AI_FALLBACK_MODEL_LOCAL') ? constant('AI_FALLBACK_MODEL_LOCAL') : '');
$fheader= getenv('AI_FALLBACK_HEADER_NAME') ?: (defined('AI_FALLBACK_HEADER_NAME_LOCAL') ? constant('AI_FALLBACK_HEADER_NAME_LOCAL') : '');
define('AI_FALLBACK_KEY', $fkey ?: '');
define('AI_FALLBACK_BASE_URL', $furl ?: '');
define('AI_FALLBACK_MODEL', $fmodel ?: '');
define('AI_FALLBACK_HEADER_NAME', $fheader ?: '');
?>

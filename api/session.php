<?php
// Cookie-backed Session Handler untuk kompatibilitas Serverless (Vercel)
if (session_status() === PHP_SESSION_NONE) {
    $secret = "sparepart_secret_key_123_abc";
    $GLOBALS['_SESSION'] = [];

    if (isset($_COOKIE['app_session'])) {
        $data = json_decode(base64_decode($_COOKIE['app_session']), true);
        if (is_array($data) && isset($data['signature']) && isset($data['payload'])) {
            $expected_sig = hash_hmac('sha256', json_encode($data['payload']), $secret);
            if (hash_equals($expected_sig, $data['signature'])) {
                $GLOBALS['_SESSION'] = $data['payload'];
            }
        }
    }
}

// Fungsi pembantu untuk menyimpan data ke session dan cookie (harus dipanggil sebelum ada output echo)
function write_session($payload) {
    $secret = "sparepart_secret_key_123_abc";
    $GLOBALS['_SESSION'] = $payload;
    
    $signature = hash_hmac('sha256', json_encode($payload), $secret);
    $data = base64_encode(json_encode([
        'payload' => $payload,
        'signature' => $signature
    ]));
    // Simpan cookie selama 30 hari
    setcookie('app_session', $data, time() + (86400 * 30), '/');
}

// Fungsi pembantu untuk menghancurkan session (pengganti session_destroy)
function destroy_session() {
    $GLOBALS['_SESSION'] = [];
    setcookie('app_session', '', time() - 3600, '/');
}
?>

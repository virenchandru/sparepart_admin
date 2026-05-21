<?php
// Cookie-backed Session Handler untuk kompatibilitas Serverless (Vercel)
if (session_status() === PHP_SESSION_NONE) {
    // Kita tidak menggunakan session_start() bawaan PHP karena file-based session
    // bersifat stateless di Vercel. Sebagai gantinya, kita membuat tiruan $_SESSION
    // menggunakan secure cookie yang ditandatangani (signed cookie).
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

    // Daftarkan fungsi shutdown untuk menyimpan session kembali ke cookie di akhir request
    register_shutdown_function(function() use ($secret) {
        $payload = $GLOBALS['_SESSION'] ?? [];
        if (empty($payload)) {
            // Hapus cookie jika session kosong
            setcookie('app_session', '', time() - 3600, '/');
        } else {
            // Simpan cookie selama 30 hari
            $signature = hash_hmac('sha256', json_encode($payload), $secret);
            $data = base64_encode(json_encode([
                'payload' => $payload,
                'signature' => $signature
            ]));
            setcookie('app_session', $data, time() + (86400 * 30), '/');
        }
    });
}

// Fungsi pembantu untuk menghancurkan session (pengganti session_destroy)
function destroy_session() {
    $GLOBALS['_SESSION'] = [];
    setcookie('app_session', '', time() - 3600, '/');
}
?>

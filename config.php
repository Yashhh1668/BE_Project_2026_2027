<?php
// includes/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'edutrack');
define('DB_PASS', 'edutrack@2026#');
define('DB_NAME', 'edutrack');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die('<div style="font-family:Poppins,sans-serif;padding:40px;color:#dc2626">DB Error: ' . $conn->connect_error . '</div>');
}
$conn->set_charset('utf8mb4');

session_start();

function isLoggedIn()  { return isset($_SESSION['user_id']); }
function requireLogin() {
    if (!isLoggedIn()) { header('Location: /edutrack/login.php'); exit; }
}
function sanitize($conn, $val) { return $conn->real_escape_string(trim($val)); }
function formatTime($s) {
    return floor($s/60).'m '.($s%60).'s';
}

// ---------- 2FA / Mail settings ----------
define('TWOFA_ISSUER',  'EduTrack Pro');
define('TWOFA_KEY',     'CHANGE-THIS-TO-A-LONG-RANDOM-STRING-64+chars'); // encrypts TOTP secrets & hashes OTPs. Never change after go-live.
define('MAIL_FROM',     'no-reply@yourdomain.com');   // must be a valid sender for your server
define('MAIL_FROM_NAME','EduTrack Pro');
define('OTP_TTL_MIN',   10);   // email OTP validity (minutes)
define('OTP_RESEND_SEC',60);   // cooldown between OTP emails
define('OTP_MAX_TRIES', 5);    // wrong attempts allowed per OTP / per login
define('OTP_DEBUG_LOG', false);// LOCAL TESTING ONLY: writes OTP to logs/otp_debug.log if mail() fails

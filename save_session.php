
<?php
header('Content-Type: application/json; charset=utf-8');

// Allow POST requests only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode([
        'success' => false,
        'message' => 'POST method required'
    ]));
}

// API authentication
$API_KEY = 'THIS_IS_VERY_HANDSOME_KEY';

$receivedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

if (!hash_equals($API_KEY, $receivedKey)) {
    http_response_code(401);
    exit(json_encode([
        'success' => false,
        'message' => 'Unauthorized device'
    ]));
}

// Existing database connection
require_once __DIR__ . '/../includes/config.php';

// Read JSON request
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid JSON'
    ]));
}

// Validate required fields
$student_id = filter_var(
    $data['student_id'] ?? null,
    FILTER_VALIDATE_INT
);

$topic_id = filter_var(
    $data['topic_id'] ?? null,
    FILTER_VALIDATE_INT
);

$teacher_id = filter_var(
    $data['teacher_id'] ?? null,
    FILTER_VALIDATE_INT
);

$total_questions = filter_var(
    $data['total_questions'] ?? null,
    FILTER_VALIDATE_INT
);

$correct_answers = filter_var(
    $data['correct_answers'] ?? null,
    FILTER_VALIDATE_INT
);

$minutes = filter_var(
    $data['minutes'] ?? 0,
    FILTER_VALIDATE_INT
);

$seconds = filter_var(
    $data['seconds'] ?? 0,
    FILTER_VALIDATE_INT
);

if (
    $student_id === false || $student_id < 1 ||
    $topic_id === false || $topic_id < 1 ||
    $teacher_id === false || $teacher_id < 1 ||
    $total_questions === false || $total_questions < 1 ||
    $correct_answers === false ||
    $correct_answers < 0 ||
    $correct_answers > $total_questions ||
    $minutes === false || $minutes < 0 ||
    $seconds === false || $seconds < 0 ||
    $seconds > 59
) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid or missing session values'
    ]));
}

// Session details
$session_date = $data['session_date'] ?? date('Y-m-d');

$date = DateTime::createFromFormat('!Y-m-d', $session_date);

if (!$date || $date->format('Y-m-d') !== $session_date) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid session date'
    ]));
}

$task_attempted = trim($data['task_attempted'] ?? '');
$answers_given = trim($data['answers_given'] ?? '');
$notes = trim($data['notes'] ?? 'Raspberry Pi session');

// Calculate score and total time
$score = round(
    ($correct_answers / $total_questions) * 100,
    2
);

$time_taken = ($minutes * 60) + $seconds;

// Insert into existing sessions table
$sql = "INSERT INTO sessions
(
    student_id,
    topic_id,
    session_date,
    task_attempted,
    answers_given,
    score,
    total_questions,
    correct_answers,
    time_taken,
    notes,
    created_by
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log('Session API prepare error: ' . $conn->error);
    http_response_code(500);
    exit(json_encode([
        'success' => false,
        'message' => 'Database query failed'
    ]));
}

$stmt->bind_param(
    "iisssdiiisi",
    $student_id,
    $topic_id,
    $session_date,
    $task_attempted,
    $answers_given,
    $score,
    $total_questions,
    $correct_answers,
    $time_taken,
    $notes,
    $teacher_id
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Session saved successfully',
        'session_id' => $stmt->insert_id,
        'score' => $score,
        'time_taken' => $time_taken
    ]);
} else {
    error_log('Session API insert error: ' . $stmt->error);
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Session could not be saved'
    ]);
}

$stmt->close();
?>

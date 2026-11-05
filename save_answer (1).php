<?php
header('Content-Type: application/json; charset=utf-8');

// POST requests only
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

// Database connection
require_once __DIR__ . '/../includes/config.php';

// Read JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid JSON'
    ]));
}

// Read and validate values
$student_id = filter_var(
    $data['student_id'] ?? null,
    FILTER_VALIDATE_INT
);

$section = $data['section'] ?? '';

$allowed_sections = [
    'skill_development',
    'english',
    'maths'
];

$question_text = trim($data['question_text'] ?? '');
$given_answer = trim($data['given_answer'] ?? '');
$correct_answer = trim($data['correct_answer'] ?? '');

$is_correct = filter_var(
    $data['is_correct'] ?? null,
    FILTER_VALIDATE_INT
);

$time_taken = filter_var(
    $data['time_taken'] ?? 0,
    FILTER_VALIDATE_INT
);

$session_reference = trim(
    $data['session_reference'] ?? ''
);

// Validate required fields
if (
    $student_id === false || $student_id < 1 ||
    !in_array($section, $allowed_sections, true) ||
    $question_text === '' ||
    $given_answer === '' ||
    $correct_answer === '' ||
    !in_array($is_correct, [0, 1], true) ||
    $time_taken === false || $time_taken < 0
) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid or missing answer details'
    ]));
}

// Check whether student exists
$check = $conn->prepare(
    "SELECT id FROM students WHERE id = ?"
);

$check->bind_param("i", $student_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $check->close();

    http_response_code(404);
    exit(json_encode([
        'success' => false,
        'message' => 'Student not found'
    ]));
}

$check->close();

// Insert answer into database
$sql = "INSERT INTO question_responses
(
    student_id,
    section,
    question_text,
    given_answer,
    correct_answer,
    is_correct,
    time_taken,
    session_reference
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log('Answer API prepare error: ' . $conn->error);

    http_response_code(500);
    exit(json_encode([
        'success' => false,
        'message' => 'Database query failed'
    ]));
}

$stmt->bind_param(
    "issssiis",
    $student_id,
    $section,
    $question_text,
    $given_answer,
    $correct_answer,
    $is_correct,
    $time_taken,
    $session_reference
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Answer saved successfully',
        'response_id' => $stmt->insert_id
    ]);
} else {
    error_log('Answer API insert error: ' . $stmt->error);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Answer could not be saved'
    ]);
}

$stmt->close();
?>
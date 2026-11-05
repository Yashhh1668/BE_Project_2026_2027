<?php
header('Content-Type: application/json; charset=utf-8');

// API KEY: Apni long random secret key yahan set karo
$API_KEY = 'THIS_IS_VERY_HANDSOME_KEY';

$receivedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';

if (!hash_equals($API_KEY, $receivedKey)) {
    http_response_code(401);
    exit(json_encode([
        'success' => false,
        'message' => 'Unauthorized device'
    ]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode([
        'success' => false,
        'message' => 'POST method required'
    ]));
}

// Database connection
require_once __DIR__ . '/../includes/config.php';

// Read JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data) || !isset($data['responses']) || !is_array($data['responses'])) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid request format'
    ]));
}

$student_id = filter_var(
    $data['student_id'] ?? null,
    FILTER_VALIDATE_INT
);

$session_reference = trim($data['session_reference'] ?? '');

if (
    $student_id === false ||
    $student_id < 1 ||
    $session_reference === '' ||
    count($data['responses']) < 1 ||
    count($data['responses']) > 100
) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Invalid student or session data'
    ]));
}

// Check student exists
$studentCheck = $conn->prepare(
    "SELECT id FROM students WHERE id = ?"
);

$studentCheck->bind_param("i", $student_id);
$studentCheck->execute();
$studentResult = $studentCheck->get_result();

if ($studentResult->num_rows === 0) {
    http_response_code(400);
    exit(json_encode([
        'success' => false,
        'message' => 'Student not found'
    ]));
}

$studentCheck->close();

$allowedSections = [
    'skill_development',
    'english',
    'maths'
];

// Prepare insert query
$stmt = $conn->prepare(
    "INSERT INTO question_responses
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
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log('Question API prepare error: ' . $conn->error);
    http_response_code(500);
    exit(json_encode([
        'success' => false,
        'message' => 'Database query failed'
    ]));
}

$conn->begin_transaction();

try {
    $savedCount = 0;

    foreach ($data['responses'] as $response) {

        $section = $response['section'] ?? '';
        $question = trim($response['question_text'] ?? '');
        $givenAnswer = trim($response['given_answer'] ?? '');
        $correctAnswer = trim($response['correct_answer'] ?? '');

        $isCorrect = filter_var(
            $response['is_correct'] ?? null,
            FILTER_VALIDATE_INT
        );

        $timeTaken = filter_var(
            $response['time_taken'] ?? 0,
            FILTER_VALIDATE_INT
        );

        if (
            !in_array($section, $allowedSections, true) ||
            $question === '' ||
            !in_array($isCorrect, [0, 1], true) ||
            $timeTaken === false ||
            $timeTaken < 0
        ) {
            throw new Exception('Invalid question response data');
        }

        $stmt->bind_param(
            "issssiis",
            $student_id,
            $section,
            $question,
            $givenAnswer,
            $correctAnswer,
            $isCorrect,
            $timeTaken,
            $session_reference
        );

        if (!$stmt->execute()) {
            throw new Exception('Question could not be saved');
        }

        $savedCount++;
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Question responses saved successfully',
        'saved_count' => $savedCount
    ]);

} catch (Exception $e) {

    $conn->rollback();

    error_log('Question API error: ' . $e->getMessage());

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
?>
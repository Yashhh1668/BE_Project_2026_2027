
<?php
header('Content-Type: application/json; charset=utf-8');

// GET requests only
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit(json_encode([
        'success' => false,
        'message' => 'GET method required'
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

// Fetch the latest active assignment
$sql = "SELECT
            aa.id,
            aa.student_id,
            s.student_id AS student_code,
            s.full_name,
            aa.section,
            aa.assigned_by,
            aa.assigned_at
        FROM active_assignments aa
        INNER JOIN students s
            ON aa.student_id = s.id
        WHERE aa.status = 'active'
        ORDER BY aa.assigned_at DESC, aa.id DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log('Assignment API error: ' . $conn->error);

    http_response_code(500);
    exit(json_encode([
        'success' => false,
        'message' => 'Database query failed'
    ]));
}

$stmt->execute();

$stmt->bind_result(
    $assignment_id,
    $student_db_id,
    $student_code,
    $full_name,
    $section,
    $assigned_by,
    $assigned_at
);

if ($stmt->fetch()) {
    echo json_encode([
        'success' => true,
        'active_assignment' => [
            'assignment_id' => $assignment_id,
            'student_id' => $student_db_id,
            'student_code' => $student_code,
            'student_name' => $full_name,
            'section' => $section,
            'assigned_by' => $assigned_by,
            'assigned_at' => $assigned_at
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'active_assignment' => null,
        'message' => 'No active student assignment'
    ]);
}

$stmt->close();
?>

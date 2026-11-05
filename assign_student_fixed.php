<?php
// EduTrack Pro - Assign a student and learning section to the Raspberry Pi.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$uid = (int) $_SESSION['user_id'];
$msg = '';
$err = '';
$allowedSections = ['english', 'maths', 'skill_development'];

// Simple CSRF protection for assignment actions.
if (empty($_SESSION['assign_student_csrf'])) {
    $_SESSION['assign_student_csrf'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['assign_student_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($postedToken) || !hash_equals($csrfToken, $postedToken)) {
        $err = 'Security check failed. Please refresh the page and try again.';
    } else {
        $action = $_POST['form_action'] ?? '';

        if ($action === 'cancel_assignment') {
            $assignmentId = filter_var($_POST['assignment_id'] ?? null, FILTER_VALIDATE_INT);

            if (!$assignmentId || $assignmentId < 1) {
                $err = 'Invalid assignment selected.';
            } else {
                // The active assignment row is removed so the Pi will no longer receive it.
                $cancel = $conn->prepare(
                    "DELETE FROM active_assignments
                     WHERE id = ? AND status = 'active'"
                );

                if (!$cancel) {
                    $err = 'Could not prepare the cancellation request.';
                    error_log('EduTrack assignment cancel prepare error: ' . $conn->error);
                } else {
                    $cancel->bind_param('i', $assignmentId);
                    if ($cancel->execute() && $cancel->affected_rows === 1) {
                        $msg = 'Selection cancelled. You can now assign another student.';
                    } else {
                        $err = 'The active assignment was not found or was already cancelled.';
                    }
                    $cancel->close();
                }
            }
        } elseif ($action === 'start_learning') {
            $studentId = filter_var($_POST['student_id'] ?? null, FILTER_VALIDATE_INT);
            $section = $_POST['section'] ?? '';

            if (!$studentId || $studentId < 1 || !in_array($section, $allowedSections, true)) {
                $err = 'Invalid student or learning section selected.';
            } else {
                // Show all students in this shared demo database. Do not filter by created_by:
                // the existing student records may belong to another teacher account.
                $studentCheck = $conn->prepare('SELECT id FROM students WHERE id = ? LIMIT 1');

                if (!$studentCheck) {
                    $err = 'Could not verify the selected student.';
                    error_log('EduTrack student check prepare error: ' . $conn->error);
                } else {
                    $studentCheck->bind_param('i', $studentId);
                    $studentCheck->execute();
                    $studentExists = $studentCheck->get_result()->num_rows === 1;
                    $studentCheck->close();

                    if (!$studentExists) {
                        $err = 'Student not found. Please refresh the page.';
                    } else {
                        $activeCheck = $conn->prepare(
                            "SELECT id FROM active_assignments WHERE status = 'active' LIMIT 1"
                        );

                        if (!$activeCheck) {
                            $err = 'Could not check the current Raspberry Pi assignment.';
                            error_log('EduTrack active check prepare error: ' . $conn->error);
                        } else {
                            $activeCheck->execute();
                            $activeExists = $activeCheck->get_result()->num_rows > 0;
                            $activeCheck->close();

                            if ($activeExists) {
                                $err = 'A learning assignment is already active. Cancel it or complete it on the Raspberry Pi first.';
                            } else {
                                $insert = $conn->prepare(
                                    "INSERT INTO active_assignments
                                     (student_id, section, assigned_by, status)
                                     VALUES (?, ?, ?, 'active')"
                                );

                                if (!$insert) {
                                    $err = 'Could not prepare the assignment.';
                                    error_log('EduTrack assignment insert prepare error: ' . $conn->error);
                                } else {
                                    $insert->bind_param('isi', $studentId, $section, $uid);

                                    if ($insert->execute()) {
                                        $msg = 'Assignment started! The Raspberry Pi can now fetch this student and section.';
                                    } else {
                                        $err = 'Could not create the assignment. Please try again.';
                                        error_log('EduTrack assignment insert error: ' . $insert->error);
                                    }
                                    $insert->close();
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $err = 'Unknown request. Please try again.';
        }
    }
}

// Get the currently active assignment.
$activeAssignment = null;
$activeResult = $conn->query(
    "SELECT aa.id, aa.student_id, aa.section, aa.assigned_at,
            s.full_name, s.student_id AS student_code
     FROM active_assignments aa
     JOIN students s ON s.id = aa.student_id
     WHERE aa.status = 'active'
     ORDER BY aa.id DESC
     LIMIT 1"
);

if ($activeResult) {
    $activeAssignment = $activeResult->fetch_assoc();
    $activeResult->free();
} else {
    error_log('EduTrack active assignment query error: ' . $conn->error);
    $err = $err ?: 'Could not load the current assignment.';
}

// Load all students; student ownership is not filtered because the demo records
// are shared between teacher accounts in the current database.
$studentRows = $conn->query(
    "SELECT s.id, s.student_id, s.full_name, s.age, s.class,
            (SELECT COUNT(*) FROM sessions se WHERE se.student_id = s.id) AS session_count
     FROM students s
     ORDER BY s.full_name ASC"
);

if (!$studentRows) {
    error_log('EduTrack students query error: ' . $conn->error);
    $err = $err ?: 'Could not load students from the database.';
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.assign-wrap{max-width:1150px;margin:0 auto}
.assign-intro{color:var(--text-muted);font-size:13px;margin-top:5px}
.assign-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(245px,1fr));gap:16px}
.assign-student{background:#fff;border:1px solid #dce4f2;border-radius:16px;padding:19px;box-shadow:0 3px 12px rgba(20,40,80,.045)}
.assign-student h3{font-size:17px;margin:0 0 5px;color:#10213e}
.assign-meta{font-size:12px;color:#71809b;margin-bottom:15px}
.assign-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.assign-actions button{border:0;border-radius:9px;padding:10px 8px;font-size:12px;font-weight:700;cursor:pointer;background:#edf3ff;color:#174fc4}
.assign-actions button:hover{background:#dce8ff}
.assign-actions button.skill{grid-column:1/-1;background:#eef8f5;color:#13775b}
.assign-actions button:disabled{opacity:.45;cursor:not-allowed}
.assign-active{background:#eaf8f1;border:1px solid #b9e8cf;border-radius:12px;padding:15px;margin-bottom:18px;color:#145b3d}
.assign-alert{padding:12px 15px;border-radius:10px;margin-bottom:15px;font-size:13px}
.assign-alert.success{background:#eaf8f1;color:#145b3d}
.assign-alert.error{background:#fff0f0;color:#9b2424}
</style>

<div class="assign-wrap">
  <div class="card-white mb-4">
    <div class="card-header-row">
      <div>
        <div class="card-title"><i class="fa-solid fa-display"></i> Assign Student to Raspberry Pi</div>
        <div class="assign-intro">Student ke card par required subject click karo. Assignment Pi ko automatically available ho jayega.</div>
      </div>
      <a href="/edutrack/pages/students.php" class="btn-outline" style="font-size:12px;padding:8px 13px">Back to Students</a>
    </div>

    <?php if ($msg): ?>
      <div class="assign-alert success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
      <div class="assign-alert error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($activeAssignment): ?>
      <div class="assign-active">
        <strong><i class="fa-solid fa-play-circle"></i> Raspberry Pi currently has an active assignment</strong><br>
        <?= htmlspecialchars($activeAssignment['full_name'], ENT_QUOTES, 'UTF-8') ?>
        (<?= htmlspecialchars($activeAssignment['student_code'], ENT_QUOTES, 'UTF-8') ?>)
        · <?= htmlspecialchars(ucwords(str_replace('_', ' ', $activeAssignment['section'])), ENT_QUOTES, 'UTF-8') ?><br>
        <small>Next student assign karne ke liye current selection cancel kar sakte ho.</small>
        <form method="POST" style="margin-top:12px" onsubmit="return confirm('Cancel this active assignment? The Raspberry Pi will no longer receive it on its next check.');">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
          <input type="hidden" name="form_action" value="cancel_assignment">
          <input type="hidden" name="assignment_id" value="<?= (int)$activeAssignment['id'] ?>">
          <button type="submit" style="border:0;border-radius:8px;padding:9px 14px;background:#fff;color:#b42318;font-weight:700;cursor:pointer"><i class="fa-solid fa-xmark"></i> Cancel Selection</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="assign-grid">
      <?php if ($studentRows && $studentRows->num_rows > 0): ?>
        <?php while ($student = $studentRows->fetch_assoc()): ?>
          <div class="assign-student">
            <div style="display:flex;align-items:center;gap:11px;margin-bottom:10px">
              <div style="width:42px;height:42px;border-radius:50%;background:#e8f0ff;color:#1854c9;display:flex;align-items:center;justify-content:center;font-size:18px"><i class="fa-solid fa-child"></i></div>
              <div>
                <h3><?= htmlspecialchars($student['full_name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <div class="assign-meta" style="margin:0">
                  <?= htmlspecialchars($student['student_id'], ENT_QUOTES, 'UTF-8') ?>
                  · Class <?= htmlspecialchars((string)$student['class'], ENT_QUOTES, 'UTF-8') ?>
                  · <?= (int)$student['age'] ?> yrs
                </div>
              </div>
            </div>
            <div class="assign-meta">Previous sessions: <?= (int)$student['session_count'] ?></div>
            <form method="POST" class="assign-actions" onsubmit="return confirm('Start this learning assignment?');">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
              <input type="hidden" name="form_action" value="start_learning">
              <input type="hidden" name="student_id" value="<?= (int)$student['id'] ?>">
              <button type="submit" name="section" value="english" <?= $activeAssignment ? 'disabled' : '' ?>><i class="fa-solid fa-language"></i> English</button>
              <button type="submit" name="section" value="maths" <?= $activeAssignment ? 'disabled' : '' ?>><i class="fa-solid fa-calculator"></i> Maths</button>
              <button type="submit" name="section" value="skill_development" class="skill" <?= $activeAssignment ? 'disabled' : '' ?>><i class="fa-solid fa-puzzle-piece"></i> Skill Development</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div style="padding:25px;color:var(--text-muted)">No students found. Please add a student first.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php
if ($studentRows instanceof mysqli_result) {
    $studentRows->free();
}
?>

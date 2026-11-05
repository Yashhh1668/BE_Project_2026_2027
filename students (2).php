<?php
require_once __DIR__ . '/../includes/config.php';
requireLogin();
$pageTitle = 'Students';
$uid = (int)$_SESSION['user_id'];
$msg = $err = '';
$action = $_GET['action'] ?? '';

if ($action === 'delete' && isset($_GET['id'])) {
    $conn->query("DELETE FROM students WHERE id=".(int)$_GET['id']." AND created_by=$uid");
    header('Location: /edutrack/pages/students.php?msg=deleted'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname  = sanitize($conn, $_POST['full_name']        ?? '');
    $sid    = sanitize($conn, $_POST['student_id']       ?? '');
    $age    = (int)($_POST['age']                        ?? 0);
    $class  = sanitize($conn, $_POST['class']            ?? '');
    $gname  = sanitize($conn, $_POST['guardian_name']   ?? '');
    $gphone = sanitize($conn, $_POST['guardian_phone']  ?? '');
    $gemail = sanitize($conn, $_POST['guardian_email']  ?? '');
    $grel   = sanitize($conn, $_POST['guardian_relation'] ?? '');
    $eid    = (int)($_POST['edit_id'] ?? 0);

    if (!$sname || !$sid || !$age || !$class) { $err = 'Please fill all required fields.'; }
    else {
        if ($eid) {
            $conn->query("UPDATE students SET full_name='$sname',student_id='$sid',age=$age,class='$class',
                guardian_name='$gname',guardian_phone='$gphone',guardian_email='$gemail',guardian_relation='$grel'
                WHERE id=$eid AND created_by=$uid");
            $msg = 'Student updated successfully!';
        } else {
            $r = $conn->query("INSERT INTO students (student_id,full_name,age,class,guardian_name,guardian_phone,guardian_email,guardian_relation,created_by)
                VALUES ('$sid','$sname',$age,'$class','$gname','$gphone','$gemail','$grel',$uid)");
            $msg = $r ? 'Student added!' : 'Error: Student ID may already exist.';
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $msg = 'Student removed.';

$editS = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editS = $conn->query("SELECT * FROM students WHERE id=".(int)$_GET['id']." AND created_by=$uid")->fetch_assoc();
}

$list = $conn->query("SELECT s.*, (SELECT COUNT(*) FROM sessions WHERE student_id=s.id) cnt FROM students s WHERE s.created_by=$uid ORDER BY s.created_at DESC");

include __DIR__ . '/../includes/header.php';
?>

<?php if ($msg): ?><div class="alert alert-success mb-3"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error   mb-3"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- FORM -->
<div class="card-white mb-4">
  <div class="card-header-row">
    <div class="card-title"><i class="fa-solid fa-<?= $editS ? 'user-pen' : 'user-plus' ?>"></i> <?= $editS ? 'Edit Student' : 'Add New Student' ?></div>
    <?php if ($editS): ?>
    <a href="/edutrack/pages/students.php" class="btn-outline" style="font-size:12px;padding:6px 14px"><i class="fa-solid fa-plus"></i> Add New</a>
    <?php endif; ?>
  </div>
  <form method="POST">
    <?php if ($editS): ?><input type="hidden" name="edit_id" value="<?= $editS['id'] ?>"><?php endif; ?>
    <div class="row g-3">
      <div class="col-md-4"><div class="f-group mb-0"><label class="f-label">Full Name *</label>
        <input type="text" name="full_name" class="f-control" placeholder="Student's full name" required value="<?= htmlspecialchars($editS['full_name'] ?? '') ?>"></div></div>
      <div class="col-md-2"><div class="f-group mb-0"><label class="f-label">Student ID *</label>
        <input type="text" name="student_id" class="f-control" placeholder="e.g. STU001" required value="<?= htmlspecialchars($editS['student_id'] ?? '') ?>"></div></div>
      <div class="col-md-2"><div class="f-group mb-0"><label class="f-label">Age *</label>
        <input type="number" name="age" class="f-control" placeholder="Age" min="3" max="25" required value="<?= $editS['age'] ?? '' ?>"></div></div>
      <div class="col-md-4"><div class="f-group mb-0"><label class="f-label">Class / Grade *</label>
        <input type="text" name="class" class="f-control" placeholder="e.g. Class 5 / Grade 3" required value="<?= htmlspecialchars($editS['class'] ?? '') ?>"></div></div>
    </div>
    <div style="margin:18px 0 10px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);display:flex;align-items:center;gap:8px">
      <i class="fa-solid fa-heart" style="color:var(--accent)"></i> Guardian / Parent Details
    </div>
    <div class="row g-3">
      <div class="col-md-3"><div class="f-group mb-0"><label class="f-label">Guardian Name</label>
        <input type="text" name="guardian_name" class="f-control" placeholder="Parent / Guardian" value="<?= htmlspecialchars($editS['guardian_name'] ?? '') ?>"></div></div>
      <div class="col-md-3"><div class="f-group mb-0"><label class="f-label">Phone</label>
        <input type="tel" name="guardian_phone" class="f-control" placeholder="+91 9XXXXXXXXX" value="<?= htmlspecialchars($editS['guardian_phone'] ?? '') ?>"></div></div>
      <div class="col-md-3"><div class="f-group mb-0"><label class="f-label">Email</label>
        <input type="email" name="guardian_email" class="f-control" placeholder="guardian@email.com" value="<?= htmlspecialchars($editS['guardian_email'] ?? '') ?>"></div></div>
      <div class="col-md-3"><div class="f-group mb-0"><label class="f-label">Relation</label>
        <select name="guardian_relation" class="f-control">
          <?php foreach (['','Father','Mother','Guardian','Sibling','Other'] as $rel): ?>
          <option value="<?= $rel ?>" <?= ($editS['guardian_relation'] ?? '') === $rel ? 'selected' : '' ?>><?= $rel ?: '-- Select --' ?></option>
          <?php endforeach; ?>
        </select></div></div>
    </div>
    <div style="margin-top:20px">
      <button type="submit" class="btn-navy">
        <i class="fa-solid fa-<?= $editS ? 'floppy-disk' : 'user-plus' ?>"></i> <?= $editS ? 'Save Changes' : 'Add Student' ?>
      </button>
    </div>
  </form>
</div>

<!-- TABLE -->
<div class="card-white">
  <div class="card-header-row">
    <div class="card-title"><i class="fa-solid fa-children"></i> All Students <span class="badge-navy ms-2"><?= $list ? $list->num_rows : 0 ?></span></div>
    <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input type="text" id="ss" placeholder="Search students..."></div>
  </div>
  <div style="overflow-x:auto">
  <table class="t-table" id="stbl">
    <thead><tr><th>#</th><th>Student ID</th><th>Name</th><th>Age</th><th>Class</th><th>Guardian</th><th>Phone</th><th>Sessions</th><th>Added</th><th>Actions</th></tr></thead>
    <tbody>
    <?php if ($list && $list->num_rows > 0):
      $i = 1; while ($s = $list->fetch_assoc()): ?>
      <tr>
        <td style="color:var(--text-muted);font-size:12px"><?= $i++ ?></td>
        <td><span class="badge-navy" style="font-size:10px"><?= htmlspecialchars($s['student_id']) ?></span></td>
        <td>
          <div style="display:flex;align-items:center;gap:9px">
            <div style="width:30px;height:30px;background:var(--accent-soft);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:13px;flex-shrink:0">
              <i class="fa-solid fa-child"></i>
            </div>
            <strong style="font-size:13.5px"><?= htmlspecialchars($s['full_name']) ?></strong>
          </div>
        </td>
        <td><?= $s['age'] ?> yrs</td>
        <td><span class="badge-blue"><?= htmlspecialchars($s['class']) ?></span></td>
        <td style="font-size:13px"><?= htmlspecialchars($s['guardian_name'] ?: '—') ?><br><span style="font-size:11px;color:var(--text-muted)"><?= htmlspecialchars($s['guardian_relation'] ?: '') ?></span></td>
        <td style="font-size:12.5px;color:var(--text-mid)"><?= htmlspecialchars($s['guardian_phone'] ?: '—') ?></td>
        <td><span class="badge-teal"><?= $s['cnt'] ?> sessions</span></td>
        <td style="color:var(--text-muted);font-size:12px"><?= date('d M Y',strtotime($s['created_at'])) ?></td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="?action=edit&id=<?= $s['id'] ?>" class="btn-icon-sm" title="Edit"><i class="fa-solid fa-pen"></i></a>
            <button onclick="confirmDelete('/edutrack/pages/students.php?action=delete&id=<?= $s['id'] ?>','<?= addslashes($s['full_name']) ?>')" class="btn-icon-sm del" title="Delete"><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
    <?php endwhile; else: ?>
      <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--text-muted)">
        <div style="font-size:32px;margin-bottom:10px">👶</div>
        No students added yet. Use the form above to add your first student.
      </td></tr>
    <?php endif; ?>
    </tbody>
  </table>
  </div>
</div>

<?php
$extraScripts = "<script>initSearch('ss','stbl');</script>";
include __DIR__ . '/../includes/footer.php';
?>

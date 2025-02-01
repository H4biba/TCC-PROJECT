<?php
include 'includes/config.php';
include 'includes/auth.php';
redirectIfNotLoggedIn();

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch task details
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if (!$task && !isAdmin()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, priority = ?, due_date = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $description, $status, $priority, $due_date, $task_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to update task!";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="card col-md-6 mx-auto">
    <div class="card-body">
        <h2 class="card-title">Edit Task</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Pending" <?php echo $task['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo $task['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo $task['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="Low" <?php echo $task['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                    <option value="Medium" <?php echo $task['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                    <option value="High" <?php echo $task['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?php echo $task['due_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
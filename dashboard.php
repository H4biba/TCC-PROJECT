<?php
include 'includes/config.php';
include 'includes/auth.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>
<div class="container mt-4"> <!-- Reduced top margin -->
    <!-- Title + New Task Button (Right-Aligned) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Your Tasks</h2> <!-- Remove default margin -->
        <a href="create_task.php" class="btn btn-success btn-sm">
            <i class="fas fa-plus me-1"></i>New Task
        </a>
    </div>

    <!-- Task Table -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                    <td><span class="badge bg-info"><?php echo $task['status']; ?></span></td>
                    <td><span class="badge bg-warning"><?php echo $task['priority']; ?></span></td>
                    <td><?php echo $task['due_date']; ?></td>
                    <td>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
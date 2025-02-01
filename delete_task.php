<?php
include 'includes/config.php';
include 'includes/auth.php';
redirectIfNotLoggedIn();

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Delete task (users can only delete their own tasks; admins can delete any)
if (isAdmin()) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
} else {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
}

if (isAdmin()) {
    $stmt->bind_param("i", $task_id);
}

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    die("Failed to delete task!");
}
?>

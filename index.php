<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM tasks WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Task Manager</h1>
    <a href="create_task.php">Add New Task</a>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($task = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($task['title']) ?></td>
            <td><?= htmlspecialchars($task['description']) ?></td>
            <td><?= $task['status'] ?></td>
            <td>
                <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a> |
                <a href="delete_task.php?id=<?= $task['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>

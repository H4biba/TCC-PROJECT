<?php
include 'includes/config.php';
include 'includes/auth.php';
redirectIfNotAdmin();

// Delete user (admin only)
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    if ($user_id != $_SESSION['user_id']) { // Prevent self-deletion
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    header("Location: manage_users.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");
?>

<?php include 'includes/header.php'; ?>
<h2>Manage Users</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="manage_users.php?delete_user=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'includes/footer.php'; ?>
<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 11/2/2024
    * Time: 4:34 PM
 */
session_start();
$pageName = "Manage Users";
require_once "header.php";

checkLogin(true);
// Initialize variables
$message = '';
$searchTerm = '';
$loggedInUserId = $_SESSION['userID'];


if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
}

// Prepare SQL query with search criteria if applicable
$sql = "SELECT userID, username, isActive FROM users";
if ($searchTerm) {
    $sql .= " WHERE username LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare($sql);
}

// Execute query and fetch results
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h2 class="page-title">Manage Users</h2>
    <p class="page-subtitle">Manage user accounts: add, update, or deactivate users as needed.</p>

    <?php if ($message): ?>
        <p>
            <span><?php echo htmlspecialchars($message); ?></span>
        </p>
    <?php endif; ?>

    <!-- Actions Section -->
    <section class="actions">
        <a href="add_user.php" class="button">Add User</a>
    </section>

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Users</h2>
        <form name="searchUsers" id="searchUsers" method="post" action="<?php echo $currentFile; ?>">
            <input type="text" name="search" id="search" placeholder="Enter username to search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <?php if (!empty($users)): ?>
        <!-- Users Table -->
        <table class="users-table">
            <tr>
                <th>Username</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td>
                        <?php echo ($user['isActive'] == 1) ? "Active" : "Inactive"; ?>
                    </td>
                    <td>
                        <?php if ($user['userID'] == $loggedInUserId): ?>
                            <button class="btn btn-update" disabled>Update</button>
                        <?php else: ?>
                            <a href="update_user.php?user_id=<?php echo $user['userID']; ?>" class="btn btn-update">Update</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><span class='error-message'>No results found for the given search term.</span></p>
    <?php endif; ?>
</div>



<?php require_once "footer.php"; ?>










<?php 
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    include "DB_connection.php";
    include "app/Model/Notification.php";

    
    if (isset($_POST['clear_all'])) {
        $user_id = $_SESSION['id'];

        $sql = "DELETE FROM notifications WHERE recipient = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);

        header("Location: notifications.php?success=All notifications cleared");
        exit();
    }

    $notifications = get_all_my_notifications($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    </style>
</head>
<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>

<div class="body">
    <?php include "inc/nav.php"; ?>

    <section class="section-1">
        <h4 class="title">All Notifications</h4>

        <?php if (isset($_GET['success'])) { ?>
            <div class="success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php } ?>

        
        <?php if ($notifications != 0) { ?>
        <form method="POST">
            <button type="submit" name="clear_all" class="danger-btn"
                onclick="return confirm('Are you sure you want to delete all notifications?')">
                <i class="fa fa-trash"></i> Clear All Notifications
            </button>
        </form>
        <?php } ?>

        <?php if ($notifications != 0) { ?>
            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>

                <?php $i = 0; foreach ($notifications as $notification) { ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= htmlspecialchars($notification['message']) ?></td>
                    <td><?= htmlspecialchars($notification['type']) ?></td>
                    <td><?= htmlspecialchars($notification['date']) ?></td>
                </tr>
                <?php } ?>
            </table>

        <?php } else { ?>
            <h3>You have zero notification</h3>
        <?php } ?>

    </section>
</div>

<script>
    var active = document.querySelector("#navList li:nth-child(4)");
    if (active) {
        active.classList.add("active");
    }
</script>

</body>
</html>

<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
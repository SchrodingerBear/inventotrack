<?php
session_start();
include_once 'db/function.php';

$function = new DBFunctions();

// Fetch all notifications
$notifications = $function->select('notifications', '*');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'addNotification') {
        $data = [
            'message' => $_POST['notificationMessage'],
            'recipient' => $_POST['notificationRecipient'],
            'status' => 'unread',
        ];

        if ($function->insert('notifications', $data)) {
            echo "<script>alert('Notification added successfully'); window.location.href = 'notifications.php';</script>";
        } else {
            echo "Failed to add notification.";
        }
    } elseif ($action === 'markAsRead') {
        $notificationId = $_POST['notificationId'];
        $data = [
            'status' => 'read'
        ];
        $conditions = ['id' => $notificationId];

        if ($function->update('notifications', $data, $conditions)) {
            echo "<script>alert('Notification marked as read successfully'); window.location.href = 'notifications.php';</script>";
        } else {
            echo "Failed to mark notification as read.";
        }
    }
}
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="content p-4">
        <h2>Notifications</h2>


        <table id="notificationsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notification['message']); ?></td>
                        <td><?php echo htmlspecialchars($notification['notification_date']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">

                                    <a href="notifications.php?delete=<?php echo $notification['id'] ?>&table=notifications"
                                        class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <script>
            function populateEditModal(element) {
                document.getElementById('editNotificationId').value = element.getAttribute('data-id');
                document.getElementById('editMessage').value = element.getAttribute('data-message');
                document.getElementById('editRecipient').value = element.getAttribute('data-recipient');
            }
        </script>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#notificationsTable').DataTable();
        });
    </script>
</body>

</html>
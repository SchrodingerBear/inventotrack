<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Settings</h2>
        <form action="settings.php" method="POST">
            <div class="form-group">
                <label for="siteName">Site Name</label>
                <input type="text" class="form-control" id="siteName" name="siteName" placeholder="Enter site name">
            </div>
            <div class="form-group">
                <label for="adminEmail">Admin Email</label>
                <input type="email" class="form-control" id="adminEmail" name="adminEmail"
                    placeholder="Enter admin email">
            </div>
            <div class="form-group">
                <label for="themeColor">Theme Color</label>
                <input type="color" class="form-control" id="themeColor" name="themeColor">
            </div>
            <div class="form-group">
                <label for="maintenanceMode">Maintenance Mode</label>
                <select class="form-control" id="maintenanceMode" name="maintenanceMode">
                    <option value="off">Off</option>
                    <option value="on">On</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
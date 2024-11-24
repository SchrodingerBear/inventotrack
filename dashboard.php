<?php
session_start();
include_once 'db/function.php';
$function = new DBFunctions();
$suppliers = $function->select('suppliers', '*');
$notifications = $function->select('notifications', '*');

// Fetch statistics
$totalProducts = $function->count('products');
$lowStocks = $function->count('products', 'stock < 10');
$outOfStocks = $function->count('products', 'stock = 0');
$totalSuppliers = $function->count('suppliers');

// Fetch data for charts
$db = new Database();
$conn = $db->connect();

// Query for total stock per category
$query = "SELECT category, SUM(stock) AS total_stock FROM products GROUP BY category";
$stmt = $conn->prepare($query);
$stmt->execute();
$categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query for user types
$query2 = "SELECT type, COUNT(*) AS user_count FROM users GROUP BY type";
$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$userTypesData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
$stocks = [];
foreach ($categoriesData as $row) {
    $categories[] = $row['category'];
    $stocks[] = $row['total_stock'];
}

$userTypes = [];
$userCounts = [];
foreach ($userTypesData as $row) {
    $userTypes[] = $row['type'];
    $userCounts[] = $row['user_count'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="content p-4">
        <h2>Welcome to the Admin Dashboard</h2>


        <!-- Statistics Row -->
        <div class="row">

            <!-- Total Products Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text display-4"><?php echo $totalProducts; ?></p>
                    </div>
                </div>
            </div>

            <!-- Low Stocks Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Low Stocks</h5>
                        <p class="card-text display-4"><?php echo $lowStocks; ?></p>
                    </div>
                </div>
            </div>

            <!-- Out of Stocks Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Out of Stocks</h5>
                        <p class="card-text display-4"><?php echo $outOfStocks; ?></p>
                    </div>
                </div>
            </div>

            <!-- Suppliers Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text display-4"><?php echo $totalSuppliers; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row of Cards -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>

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
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    data-toggle="dropdown">
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
                    </div>
                </div>
            </div>


            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Supplier Information</h5>
                        <table id="suppliersTable" class="table table-striped table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['address']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['notes']); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    data-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">

                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#editSupplierModal"
                                                        data-supplier-name="<?php echo htmlspecialchars($supplier['supplier_name']); ?>"
                                                        data-contact-person="<?php echo htmlspecialchars($supplier['contact_person']); ?>"
                                                        data-contact-number="<?php echo htmlspecialchars($supplier['contact_number']); ?>"
                                                        data-email="<?php echo htmlspecialchars($supplier['email']); ?>"
                                                        data-address="<?php echo htmlspecialchars($supplier['address']); ?>"
                                                        data-notes="<?php echo htmlspecialchars($supplier['notes']); ?>"
                                                        data-id="<?php echo $supplier['id']; ?>"
                                                        onclick="populateEditModal(this)">Edit</a>
                                                    <a href="supplier.php?delete=<?php echo $supplier['id'] ?>&table=suppliers"
                                                        class="dropdown-item" href="#">Delete</a>


                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="height: 5px; !important">
            <div class="col-md-12">
                <canvas id="stockChart"></canvas> <!-- Chart for category stock -->
            </div>

        </div>


    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#suppliersTable').DataTable();
            $('#notificationsTable').DataTable({
                "pageLength": 5 // Limit the table to 5 rows per page
            });
        });

    </script>

    <script>
        // Data for the Stock Chart
        var stockData = {
            labels: <?php echo json_encode($categories); ?>, // Categories from database
            datasets: [{
                label: 'Total Stock per Category',
                data: <?php echo json_encode($stocks); ?>, // Stock data from database
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var ctx1 = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx1, {
            type: 'bar',
            data: stockData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Data for the User Type Chart
        var userTypeData = {
            labels: <?php echo json_encode($userTypes); ?>, // User types from database
            datasets: [{
                label: 'User Count by Type',
                data: <?php echo json_encode($userCounts); ?>, // User count data from database
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',  // Color for Admin
                    'rgba(54, 162, 235, 0.2)',  // Color for Staff
                    'rgba(255, 206, 86, 0.2)',  // Add other colors if needed for additional user types
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',    // Border color for Admin
                    'rgba(54, 162, 235, 1)',    // Border color for Staff
                    'rgba(255, 206, 86, 1)',    // Border color for other types
                ],
                borderWidth: 1
            }]
        };



    </script>

</body>

</html>
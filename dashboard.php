<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                        <p class="card-text display-4">30</p>
                    </div>
                </div>
            </div>

            <!-- Low Stocks Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Low Stocks</h5>
                        <p class="card-text display-4">30</p>
                    </div>
                </div>
            </div>

            <!-- Out of Stocks Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Out of Stocks</h5>
                        <p class="card-text display-4">30</p>
                    </div>
                </div>
            </div>

            <!-- Suppliers Card -->
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text display-4">5</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row of Cards -->
        <div class="row">
            <!-- Recent Activity Card -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <p class="card-text">View recent activities related to products or inventory.</p>
                    </div>
                </div>
            </div>

            <!-- Supplier Information Card -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Supplier Information</h5>
                        <p class="card-text">Details about suppliers, such as contact info or performance metrics.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        $(document).ready(function () {
            $('.navbar-toggler').click(function () {
                $('#sidebar').toggleClass('show');
            });
        });
    </script>
</body>

</html>
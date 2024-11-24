<?php
include_once 'db/function.php';
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}
$dbFunctions = new DBFunctions();

// Get the table and delete ID from the URL parameters
$table = isset($_GET['table']) ? $_GET['table'] : null;
$deleteId = isset($_GET['delete']) ? (int) $_GET['delete'] : null;

if ($table && $deleteId) {
    $conditions = ['id' => $deleteId]; // Assumes 'id' is the primary key in your table

    try {
        $rowsAffected = $dbFunctions->delete($table, $conditions);

        if ($rowsAffected > 0) {
            echo "<script>alert('Delete Success'); window.location.reload();</script>";

        }
    } catch (RuntimeException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<style>
    /* Make the navbar fixed at the top */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        /* Ensure it stays above other elements */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Optional: adds a shadow below the navbar */
    }

    /* Adjust the content to prevent it from being covered by the fixed navbar */
    .content {
        margin-top: 70px;
        /* Adjust this value based on the navbar height */
    }

    /* For smaller screens, you may want to adjust the navbar height */
    @media (max-width: 768px) {
        .navbar {
            padding: 10px 0;
        }

        .content {
            margin-top: 60px;
            /* Adjust for mobile */
        }
    }

    /* Sidebar styling */
    .sidebar {
        width: 220px;
        background-color: #213830;
        color: white;
        min-height: 100vh;
        position: fixed;
        z-index: 1;
        transition: all 0.3s ease;
        margin-top: 3%;
    }

    .sidebar .nav-link {
        color: #ddd;
        padding: 10px 20px;
        font-size: 1rem;
    }

    .sidebar .nav-link:hover {
        background-color: #2A3F4F;
        color: white;
    }

    .sidebar .sidebar-header {
        padding: 15px;
        font-size: 1rem;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #2A3F4F;
    }

    .sidebar .profile-logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .navbar-logo {
        width: 30px;
        height: 30px;
        margin-right: 10px;
    }

    /* Main content styling */
    .content {
        margin-left: 220px;
        width: calc(100% - 220px);
        padding-top: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: 100vh;
            position: absolute;
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .content {
            margin-left: 0;
            width: 100%;
        }

        .navbar-toggler {
            border: none;
            font-size: 1.2rem;
            color: white;
        }
    }
</style>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #ABE68A;">
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#sidebar"
        aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
    </button>
    <a style="color: black; !important" class="navbar-brand ml-3" href="#">
        <img src="assets/images/logo.png" alt="System Logo" class="navbar-logo">
        INVENTOTRACK
    </a>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar collapse d-lg-block" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/images/<?php echo $_SESSION['userid'] ?>.png" alt="Profile Logo" class="profile-logo">
            <p class="m-0"><?php echo $_SESSION['name'] ?></p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="stock.php"><i class="fas fa-boxes"></i> Stocks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="supplier.php"><i class="fas fa-truck"></i> Supplier</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="settings.php"><i class="fas fa-cog"></i> Settings</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php"><i class="fas fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>

</div>
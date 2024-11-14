<?php
include_once 'db/function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'full_name' => $_POST['full_name'],
        'type' => 2, // Set type as 2
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'department' => $_POST['department'],
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
    ];

    $db = new DBFunctions(); // Ensure that this matches the class name and setup in your function file
    if ($db->insert('users', $data)) {
        echo "<script>alert('Registration successful'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error occurred during registration.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Form styling */
        .form-card {
            width: 100%;
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-header img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .form-header h2 {
            font-size: 1.5rem;
            margin: 0;
            color: #213830;
        }

        .btn-custom {
            background-color: #213830;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-card {
                width: 90%;
            }
        }
    </style>
</head>

<body style="background-color: #f2f2f2;">

    <!-- Registration Form -->
    <div class="form-card mt-5">
        <div class="form-header">
            <img src="assets/images/logo.png" alt="Logo">
            <h2>Register</h2>
        </div>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="registerFullName">Full Name</label>
                <input type="text" class="form-control" id="registerFullName" name="full_name"
                    placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="registerUsername">Username</label>
                <input type="text" class="form-control" id="registerUsername" name="username"
                    placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label for="registerEmail">Email Address</label>
                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Enter your email"
                    required>
            </div>
            <div class="form-group">
                <label for="registerDepartment">Department</label>
                <input type="text" class="form-control" id="registerDepartment" name="department"
                    placeholder="Enter your department" required>
            </div>
            <div class="form-group">
                <label for="registerPassword">Password</label>
                <input type="password" class="form-control" id="registerPassword" name="password"
                    placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="registerConfirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="registerConfirmPassword" name="confirm_password"
                    placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Register</button>
            <a href="index.php" class="btn btn-custom btn-block">Login</a>

        </form>

    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
session_start();
include_once 'db/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $function = new DBFunctions();

    $user = $function->select('users', '*', ['email' => $email]);

    if ($user && password_verify($password, $user[0]['password'])) {
        $_SESSION['userid'] = $user[0]['id'];
        $_SESSION['type'] = $user[0]['type'];
        $_SESSION['name'] = $user[0]['full_name'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href = 'index.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        @media (max-width: 768px) {
            .form-card {
                width: 90%;
            }
        }
    </style>
</head>

<body style="background-color: #f2f2f2;">
    <div class="form-card mt-4">
        <div class="form-header">
            <img src="assets/images/logo.png" alt="Logo">
            <h2>Login</h2>
        </div>
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Enter your email"
                    required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" class="form-control" id="loginPassword" name="password"
                    placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Login</button>
            <a href="register.php" class="btn btn-custom btn-block">Register</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
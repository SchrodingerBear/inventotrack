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

    $db = new DBFunctions(); // Ensure this matches the class name in your function file

    // Check for duplicate username or email
    $username_exists = !empty($db->select('users', '*', ['username' => $data['username']]));
    $email_exists = !empty($db->select('users', '*', ['email' => $data['email']]));

    if ($username_exists || $email_exists) {
        echo "<script>
                alert('Username or Email already exists. Please use a different one.');
                window.location.href = 'register.php';
              </script>";
    } else {
        // Insert the new user if no duplicates found
        if ($db->insert('users', $data)) {
            echo "<script>
                    alert('Registration successful');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "Error occurred during registration.";
        }
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
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
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

        <form id="registrationForm" method="POST" action="register.php">
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

            <!-- Terms and Conditions Checkbox -->
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                <label class="form-check-label" for="acceptTerms">I accept the <a href="#" id="termsLink">Terms and
                        Conditions</a></label>
            </div>

            <button type="submit" class="btn btn-custom btn-block">Register</button>
            <a href="index.php" class="btn btn-custom btn-block">Login</a>

        </form>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

    <script>
        $(function () {
            $('#registrationForm').on('submit', function (event) {
                if (!$('#acceptTerms').prop('checked')) {
                    event.preventDefault();  // Prevent form submission

                    // Show SweetAlert with a warning
                    Swal.fire({
                        title: 'Oops!',
                        text: 'You must accept the terms and conditions to register.',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
            });

            $('#termsLink').on('click', function (event) {
                event.preventDefault(); // Prevent page jump
                Swal.fire({
                    title: 'Terms and Conditions',
                    text: 'Here are the terms and conditions...',
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            });
        });
    </script>


</body>

</html>
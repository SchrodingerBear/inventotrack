<?php
session_start();
include_once 'db/function.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
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



if (isset($_POST['otp']) && isset($_POST['email'])) {
    // Store generated OTP and email in session
    $_SESSION['otp'] = $_POST['otp'];
    $_SESSION['email'] = $_POST['email']; // Store email in session
    // Send OTP email to the provided email address
    $message = "Your OTP is: " . $_SESSION['otp'];
    $response = sendOTPEmail($_SESSION['email'], $message); // Use the stored email

    echo 'OTP stored and email sent.';
    exit;
}

// Function to send OTP email using SMTP
function sendOTPEmail($email, $message)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                         // Set the SMTP server to Gmail
        $mail->SMTPAuth = true;                                     // Enable SMTP authentication
        $mail->Username = 'seancvpugosa@gmail.com';                   // SMTP username
        $mail->Password = 'lxopfyttpemfqprt';                    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
        $mail->Port = 587;                                      // TCP port to connect to

        //Recipients
        $mail->setFrom('seancvpugosa@gmail.com', 'Sean');
        $mail->addAddress($email);                                     // Add recipient email

        // Content
        $mail->isHTML(true);                                           // Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body = $message;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => $mail->ErrorInfo];
    }
}

// Check for OTP verification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    // Verify OTP from session
    if ($_POST['verify_otp'] == $_SESSION['otp']) {
        // OTP is verified, return success
        echo 'success';
    } else {
        echo 'fail';
    }
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password']) && isset($_POST['reset'])) {
    $password = $_POST['password'];
    $email = $_SESSION['email']; // Retrieve email from session

    // Check if it's an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // This is an AJAX request, handle it
        // Create a new Database object and get the connection
        $database = new Database();
        $conn = $database->connect();

        try {
            // Step 4: Search for the email in the users table
            $sqlCustomer = "SELECT email_address FROM users WHERE email_address = :email";
            $stmt = $conn->prepare($sqlCustomer);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $email = trim($email);
                $sqlUpdate = "UPDATE users SET password = :password WHERE email_address = :email";
                $stmtUpdate = $conn->prepare($sqlUpdate);

                // Using password_hash for secure password hashing
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmtUpdate->bindParam(':password', $hashedPassword);
                $stmtUpdate->bindParam(':email', $email);

                // Execute the query
                $stmtUpdate->execute();

                // Check if any rows were affected
                if ($stmtUpdate->rowCount() > 0) {
                    echo 'success';
                    exit;
                } else {
                    echo 'error';
                    exit;
                }

            } else {
                echo 'emailnotexist';
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}


$toastrMessage = ''; // Variable to store error message for Toastr

if (isset($_POST['login'])) {
    $input = $_POST['student_number']; // This could be either email or student number
    $password = $_POST['password'];

    $dbFunctions = new DBFunctions();

    // Check if the input is an email or a student number
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $conditions = ['email_address' => $input];
    } else {
        $conditions = ['student_number' => $input];
    }

    // Fetch the user with matching student_number or email_address
    $result = $dbFunctions->select('users', '*', $conditions);

    if (!empty($result)) {
        $user = $result[0]; // Since we expect only one result

        // Verify password
        if (password_verify($password, $user['password'])) {

            if ($user['type'] == 3 || $user['type'] == 2 && filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['student_number'] = $user['student_number']; // Store student number
            } elseif (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['email'] = $user['email_address']; // Store the email
                unset($_SESSION['student_number']); // Ensure student number is not set
            } else {
                $_SESSION['student_number'] = $user['student_number']; // Store student number
                unset($_SESSION['email']); // Ensure email is not set
            }

            $_SESSION['user_id'] = $user['id']; // Store user ID regardless
            $_SESSION['user_type'] = $user['type']; // Store user ID regardless

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password message
            $toastrMessage = "Invalid password. Please try again.";
        }
    } else {
        // No user found with the given input
        $toastrMessage = "Student number or email address not found.";
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
        <br>
        <div class="px-2 mb-0">
            <a class="text-center" onclick="askForInput()">Reset Password</a>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Function to generate a 4-digit OTP
        function generateOTP() {
            return Math.floor(1000 + Math.random() * 9000); // Generates a random 4-digit number
        }

        function askForInput() {
            Swal.fire({
                title: 'Enter Your Email Address',
                input: 'email',
                inputValue: '',
                showCancelButton: false,
                allowOutsideClick: true, // Prevent closing the modal by clicking outside
                confirmButtonText: 'Next',
                preConfirm: (email) => {
                    if (!email) {
                        Swal.showValidationMessage('Email address is required');
                        return false; // Stop the process
                    }
                    return email;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const email = result.value;
                    const otp = generateOTP();
                    storeOTPInSession(otp, email);
                }
            });
        }

        // Function to store OTP in PHP session via AJAX
        function storeOTPInSession(otp, email) {
            $.ajax({
                url: 'index.php', // PHP file to handle session storage
                type: 'POST',
                data: { otp: otp, email: email }, // Send both OTP and email
                success: function () {
                    console.log('OTP stored in session.');
                    // Now ask the user to enter the OTP
                    askForOTP(); // Call to ask for OTP input
                }
            });
        }

        // Function to ask for OTP input
        function askForOTP(invalidMessage = '') {
            Swal.fire({
                title: 'Enter Your OTP',
                input: 'text',
                inputValue: '',
                showCancelButton: false,
                allowOutsideClick: false,
                text: invalidMessage, // Show the error message if provided
                confirmButtonText: 'Submit',
                preConfirm: (inputValue) => {
                    if (!inputValue) {
                        Swal.showValidationMessage('OTP is required');
                    }
                    return inputValue;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    verifyOTP(result.value); // Call verifyOTP with the entered OTP
                }
            });
        }

        // Function to verify OTP
        function verifyOTP(inputOTP) {
            $.ajax({
                url: 'index.php', // PHP file to verify OTP
                type: 'POST',
                data: { verify_otp: inputOTP }, // Send entered OTP for verification
                success: function (response) {
                    if (response.trim() === 'success') {
                        askForPassword();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid OTP',
                            text: 'Please try again.'
                        }).then(() => {
                            askForOTP('Invalid OTP. Please try again.'); // Re-ask after displaying the error
                        });
                    }
                }
            });
        }

        function askForPassword() {
            Swal.fire({
                title: 'Enter Your Password',
                input: 'password',
                inputValue: '',
                showCancelButton: false,
                allowOutsideClick: false,
                confirmButtonText: 'Submit',
                preConfirm: (password) => {
                    if (!password) {
                        Swal.showValidationMessage('Password is required');
                    }
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitPassword(result.value); // Call to submit the entered password
                }
            });
        }
        function submitPassword(password) {
            $.ajax({
                url: 'index.php', // PHP file to handle password submission
                type: 'POST',
                data: { password: password, reset: "true" }, // Send the entered password for processing
                success: function (response) {
                    console.log(response); // Log the response to inspect it
                    if (response.trim() === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Change Success',
                        });
                    } else if (response.trim() === 'emailnotexist') {
                        Swal.fire({
                            icon: 'error',
                            title: "Account doesn't Exist",
                        });
                    }
                }
            });
        }


    </script>

    <!-- jQuery (necessary for Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr Script -->
    <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        <?php if (!empty($toastrMessage)) { ?>
            toastr.error("<?php echo $toastrMessage; ?>");
        <?php } ?>
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
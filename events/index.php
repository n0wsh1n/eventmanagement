<?php
session_start();
if (isset($_SESSION['email'])) {
header("Location: events.php"); 
exit();
}
// Include external PHP file for database connection and adding events
include 'DbConnect.php';

// Login
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['email'] = $email;
        header("Location: events.php"); // Redirect to dashboard after successful login
        exit();
    } else {
        $output = "Invalid email or password!";
    }
}

// Create new account
if(isset($_POST['create_account'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
// Check if email already exists
    $check_sql = "SELECT * FROM user WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $output = "Email already in use. Please use a different email.";
    } else {
        // Insert new account if email is not in use
        $insert_sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($insert_sql) === TRUE) {
            $output = "Account created successfully!";
        } else {
            $output = "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

// Forgot Password

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    $reset_token = bin2hex(random_bytes(32)); // Generating a random token

    $sql = "UPDATE user SET reset_token = '$reset_token' WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result) {
        // Send an email with the reset link to the user
        $reset_link = "http://localhost/event/index.php?token=$reset_token";
        // Send the $reset_link to the user's email
        // Example using PHP's mail function:
        /*
        $to = $email;
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $reset_link";
        $headers = "From: aitpark2023@gmail.com";
        mail($to, $subject, $message, $headers);
        */
        $output = "Password reset instructions sent to your email.";
    } else {
        $output = "Error updating reset token: " . $conn->error;
    }
}

if (isset($_POST['reset_password'])) {
    $token = $_GET['token']; // Token from the reset link
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify that the password and confirmation match
    if ($new_password === $confirm_password) {
        $sql = "UPDATE user SET password = '$new_password', reset_token = NULL WHERE reset_token = '$token'";
        $result = $conn->query($sql);

        if ($result) {
            $output = "Password has been reset successfully!";
            header("Location: index.php"); // Redirect to login after successful forget password
            exit();
        } else {
            $output = "Error resetting password: " . $conn->error;
        }
    } else {
        $output = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #364d99 ;
        }

        .container {
            max-width: 700px;
            margin: 100px auto;
            background-color: #fff;
            padding: 100px ;
            border-radius: 100px;
            box-shadow: 20px 20px 20px rgba(0, 0, 100, 5);
            
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #092eaa;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #052180;
        }

        a {
            text-decoration: none;
            color: #333;
        }

        .link-buttons {
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <script>
        function showLogin() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('createAccountForm').style.display = 'none';
            document.getElementById('forgotPasswordForm').style.display = 'none';
        }

        function showCreateAccount() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('createAccountForm').style.display = 'block';
            document.getElementById('forgotPasswordForm').style.display = 'none';
        }

        function showForgotPassword() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('createAccountForm').style.display = 'none';
            document.getElementById('forgotPasswordForm').style.display = 'block';
        }
    </script>
</head>

<body>

    <div class="container">
        <center>
            <a href="index.php"><img src="logo.png" alt="Event" width="100px"></a>
        </center>
        <div id="loginForm">
            <h2>Login Now</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required autocomplete="off"><br>
                <input type="password" name="password" placeholder="Password" required autocomplete="off"><br>
                <input type="submit" name="login" value="Login">
                <br>
                <center>
                    <a href="javascript:void(0)" onclick="showCreateAccount()">Create Account</a> |
                    <a href="javascript:void(0)" onclick="showForgotPassword()">Forgot Password?</a>
                </center>
            </form>
            <br>
            <center>
                <a href="index.php"><img src="first.png" alt="Event" width="700px"></a>
            </center>
    </div>
    <div>
        <div id="createAccountForm" style="display: none;">
            <h2>Create a New Account</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required autocomplete="off"><br>
                <input type="email" name="email" placeholder="Email" required autocomplete="off"><br>
                <input type="password" name="password" placeholder="Password" required autocomplete="off"><br>
                <input type="submit" name="create_account" value="Create Account">
                <br>
                <center>
                    <a href="javascript:void(0)" onclick="showLogin()">Login</a> |
                    <a href="javascript:void(0)" onclick="showForgotPassword()">Forgot Password?</a>
                </center>
        
            </form>
        </div>

        <div id="forgotPasswordForm" style="display: none;">
            <h2>Forgot Password</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required autocomplete="off"><br>
                <input type="submit" name="forgot_password#" value="Reset Password">
                <br>
                <center>
                    <a href="javascript:void(0)" onclick="showLogin()">Login</a> |
                    <a href="javascript:void(0)" onclick="showCreateAccount()">Create Account</a>
                </center>
            </form>
        </div>
    </div>


    <?php
if(isset($output)){ ?>
    <center>
        <h3><?php echo $output; ?></h3>
    </center>
    <?php 
                    }
    
?>
</body>

</html>

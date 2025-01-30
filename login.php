<?php
session_start();
include 'header.php';
require 'db_config.php'; // Assume this file contains database connection logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'resident') {
                    header("Location: dashresident.php");
                } elseif ($user['role'] === 'collector') {
                    header("Location: dashcoll.php");
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Split-screen container */
        .split-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        @media (min-width: 768px) {
            .split-container {
                flex-direction: row;
            }
        }

        /* Left side with the image */
        .split-container .image-section {
            flex: 1;
            background: url('image/loginsign.jpg') no-repeat center center;
            background-size: cover;
        }

        /* Right side with the form */
        .split-container .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
        }

        .form-section .form-container {
            max-width: 400px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(168, 18, 18, 0.2);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container p {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .form-container input {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container .btn {
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .form-container .btn:hover {
            background: #45a049;
        }

        .form-container .signup-link {
            text-align: center;
            margin-top: 10px;
            color: #4CAF50;
        }

        .form-container .signup-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left side: Image section -->
        <div class="image-section"></div>

        <!-- Right side: Form section -->
        <div class="form-section">
            <div class="form-container">
                <h2>Welcome Back!</h2>
                <p>Enter your details to log in and manage your Smart Waste Bin system.</p>
                <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
                <form method="POST">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

                    <label for="password"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" name="password" id="password" required>

                    <button type="submit" class="btn">Login</button>
                </form>
                <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a></p>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

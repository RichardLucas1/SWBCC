<?php
session_start();
include 'header.php';
require 'db_config.php'; // Assume this file contains database connection logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php"); // Redirect to login page after successful signup
            exit;
        } else {
            $error = "Failed to sign up. Please try again.";
        }

        $stmt->close();
    }
}
?>

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
            background: url('image/signupbg.jpg') no-repeat center center;
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

<!-- <body class="signup-page"> -->
<div class="split-container">
    <div class="image-section"></div>
    <div class="form-section">
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <form method="POST" class="form-container">
        <h2>Create Your Account</h2>
        <p>Join the Smart Waste Bin community and contribute to sustainable waste management!</p>
            <label for="name"><i class="fas fa-user"></i> Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email"><i class="fas fa-envelope"></i> Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password"><i class="fas fa-lock"></i> Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="role"><i class="fas fa-users"></i> Role:</label>
            <select name="role" id="role" required>
                <option value="collector">Collector</option>
                <option value="resident">Resident</option>
            </select>

            <button type="submit" class="btn">Sign Up</button>
            <p class="login-link">Already have an account? <a href="login.php">Log in here</a></p>
        </form>
        
    </div>
</div>
<!-- </body> -->
<?php include 'footer.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Waste Bin System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo"><a href="index.php">Smart Waste Bin</a></h1>
            <nav>
    <ul>
        <?php
        // Get the current page name
        $current_page = basename($_SERVER['PHP_SELF']);

        // Define navigation links based on the current page
        if ($current_page === 'index.php') {
            echo '<li><a href="signup.php">Sign Up</a></li>';
            echo '<li class="most-right"><a href="login.php">Login</a></li>';
        } elseif ($current_page === 'signup.php') {
            echo '<li><a href="index.php">Home</a></li>';
            echo '<li class="most-right"><a href="login.php">Login</a></li>';
        } elseif ($current_page === 'login.php') {
            echo '<li><a href="index.php">Home</a></li>';
            echo '<li class="most-right"><a href="signup.php">Sign Up</a></li>';
        } else {
            echo '<li><a href="index.php">Home</a></li>';
            echo '<li class="most-right"><a href="logout.php">Logout</a></li>';
        }
        ?>
    </ul>
</nav>


        </div>
    </header>
</body>

</html>

<style>
.navbar {
    display: flex;
    align-items: center;
    background: #4CAF50;
    color: #fff;
    padding: 15px 20px;
    border-left: 3px solid black; /* Black border only on the left */
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.navbar .logo {
    margin-right: auto; /* Push logo to the left */
}

.navbar .logo a {
    color: #fff;
    text-decoration: none;
    font-size: 1.8rem;
    font-weight: bold;
}

.navbar nav {
    margin-left: auto; /* Push navigation to the right */
}

.navbar nav ul {
    display: flex;
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.navbar nav ul li a {
    color: #fff;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background 0.3s, color 0.3s;
}

.navbar nav ul li a:hover {
    background: #45a049;
    color: #fff;
}

/* Style for the most left navigation link */
.most-left a {
    border-left: 3px solid black; /* Add black border on the left */
    padding-left: 15px; /* Add padding to offset text */
}


</style>
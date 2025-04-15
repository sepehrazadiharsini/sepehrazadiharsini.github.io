<?php
session_start(); // Start the session

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "blog";

// Create a connection to the database
$connection = new mysqli($server_name, $username, $password, $database_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $connection->real_escape_string($_POST['email']);
    $password = $connection->real_escape_string($_POST['password']);

    // Query to check if the user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_email'] = $user['email'];

            // Redirect to addEntry.php
            header("Location: addEntry.php");
            exit();
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>SEPEHR | LOGIN</title>
</head>


<body>
    <header>
        <nav>
            <h2 class="name">Sepehr Azadi Harsini</h2>
            <ul class="nav-link">
                <li><a href="index.html">Home</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="projects.html">My Portfolio</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>


    <main>
        <aside class="login-form">
            <h2>Login</h2>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="PHP/login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
                <button type="reset" id="clearButton">Clear</button>
            </form>

            <!-- Include external JavaScript -->
            <script src="../Javascript/clearForm.js"></script>
        </aside>
    </main>


    <footer id="contact">
        <p>Contact</p>
        <p>Email: <a href="mailto:ec24789@qmul.ac.uk"><i class="fas fa-envelope"></i>s.azadi-harsini@se24.qmul.ac.uk</a></p>
        <p>GitHub: <a href="https://github.com/sepehrazadiharsini" target="_blank"><i class="fab fa-github"></i> sepehrazadiharsini</a></p>
    </footer>
    
</body>
</html>

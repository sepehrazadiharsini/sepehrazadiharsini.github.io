<?php
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

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";

    if ($connection->query($query) === TRUE) {
        $success_message = "User registered successfully!";
    } else {
        $error_message = "Error: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css" type="text/css">
    <title>Register User</title>
</head>
<body>
    <main>
        <section class="register-form">
            <h2>Register User</h2>
            <?php if (isset($success_message)): ?>
                <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="registerUser.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Register</button>
                <button type="reset" id="clearButton">Clear</button>
            </form>

            <!-- Include external JavaScript -->
            <script src="../Javascript/clearForm.js"></script>
        </section>
    </main>
</body>
</html>
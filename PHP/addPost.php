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

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $connection->real_escape_string($_POST['title']);
    $content = $connection->real_escape_string($_POST['content']);
    $date_created = date("Y-m-d H:i:s"); // Get the current date and time

    // Insert the new blog post into the database
    $query = "INSERT INTO blog_posts(title, content, date_created) VALUES ('$title', '$content', '$date_created')";

    if ($connection->query($query) === TRUE) {
        // Redirect to viewBlog.php after successful insertion
        header("Location: viewBlog.php");
        exit();
    } else {
        echo "Error: " . $connection->error;
    }
}
?>
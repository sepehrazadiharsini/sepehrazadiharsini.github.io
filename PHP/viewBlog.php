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

// Fetch blog posts from the database
$query = "SELECT * FROM blog_posts ORDER BY date_created DESC";
$result = $connection->query($query);

// Redirect to login.html if no entries exist
if ($result->num_rows === 0) {
    header("Location: ../login.html");
    exit();
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
    <title>SEPEHR | BLOG</title>
</head>
<body>
    <header>
        <nav>
            <h2 class="name">Sepehr Azadi Harsini</h2>
            <ul class="nav-link">
                <li><a href="index.php">Home</a></li>
                <li><a href="viewBlog.php">Blog</a></li>
                <li><a href="../projects.html">My Portfolio</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="blog-posts">
            <h2>Blog Posts</h2>
            <?php while ($row = $result->fetch_assoc()): ?>
                <article class="blog-post">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date_created']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <hr>
                </article>
            <?php endwhile; ?>
        </section>
    </main>

    <footer id="contact">
        <p>Contact</p>
        <p>Email: <a href="mailto:ec24789@qmul.ac.uk"><i class="fas fa-envelope"></i>s.azadi-harsini@se24.qmul.ac.uk</a></p>
        <p>GitHub: <a href="https://github.com/sepehrazadiharsini" target="_blank"><i class="fab fa-github"></i> sepehrazadiharsini</a></p>
    </footer>
</body>
</html>

<?php
session_start();


if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "blog";

$connection = new mysqli($server_name, $username, $password, $database_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
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
    <script src="../Javascript/prevent.js"></script>
    <script src="../Javascript/clearForm.js"></script>
    <title>SEPEHR | ADD ENTRY</title>
</head>

<body>
    <header>
        <nav>
            <h2 class="name">Sepehr Azadi Harsini</h2>
            <ul class="nav-link">
                <li><a href="index.php">Home</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="../experience.html">My Experience</a></li>
                <li><a href="projects.html">My Portfolio</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="blog-posts">
            <h2>Blog Posts</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</p>
            <aside class="post-form">
                <h2>New Post</h2>
                <form id="postForm" action="addPost.php" method="POST">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo isset($_SESSION['edit_title']) ? htmlspecialchars($_SESSION['edit_title']) : ''; ?>" required>
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="5" required><?php echo isset($_SESSION['edit_content']) ? htmlspecialchars($_SESSION['edit_content']) : ''; ?></textarea>
                    <button type="submit" name="action" value="post">Post</button>
                    <button type="submit" name="action" value="preview">Preview</button>
                    <button type="reset" id="clearButton">Clear</button>
                </form>
                <?php
                // Clear the session variables
                unset($_SESSION['edit_title']);
                unset($_SESSION['edit_content']);
                ?>
            </aside>
        </section>
    </main>

    <footer id="contact">
        <p>Contact</p>
        <p>Email: <a href="mailto:ec24789@qmul.ac.uk"><i class="fas fa-envelope"></i>s.azadi-harsini@se24.qmul.ac.uk</a></p>
        <p>GitHub: <a href="https://github.com/sepehrazadiharsini" target="_blank"><i class="fab fa-github"></i> sepehrazadiharsini</a></p>
    </footer>

    
</body>
</html>

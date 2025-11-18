<?php
session_start();

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "blog";


$connection = new mysqli($server_name, $username, $password, $database_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $connection->real_escape_string($_POST['title']);
    $content = $connection->real_escape_string($_POST['content']);
    $date_created = date("Y-m-d H:i:s");

    // Handle the preview action
    if ($_POST['action'] === 'preview') {
    
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='stylesheet' href='../CSS/style.css' type='text/css'>
            <title>Preview Post</title>
        </head>
        <body>
            <header>
                <nav>
                    <h2 class='name'>Sepehr Azadi Harsini</h2>
                    <ul class='nav-link'>
                        <li><a href='index.php'>Home</a></li>
                        <li><a href='viewBlog.php'>Blog</a></li>
                        <li><a href='../experience.html'>My Experience</a></li>
                        <li><a href='../projects.html'>My Portfolio</a></li>
                        <li><a href='logout.php'>Logout</a></li>
                    </ul>
                </nav>
            </header>
            <main>
                <section class='blog-posts'>
                    <h2>Preview Post</h2>
                    <article class='blog-post'>
                        <h3>" . htmlspecialchars($title) . "</h3>
                        <p><strong>Date:</strong> " . date('jS F Y, H:i \U\T\C', strtotime($date_created)) . "</p>
                        <p>" . nl2br(htmlspecialchars($content)) . "</p>
                    </article>
                    <form action='addPost.php' method='POST'>
                        <input type='hidden' name='title' value='" . htmlspecialchars($title, ENT_QUOTES) . "'>
                        <input type='hidden' name='content' value='" . htmlspecialchars($content, ENT_QUOTES) . "'>
                        <button type='submit' name='action' value='post'>Upload Post</button>
                        <button type='submit' name='action' value='edit'>Go Back to Edit</button>
                    </form>
                </section>
            </main>
            <footer id='contact'>
                <p>Contact</p>
                <p>Email: <a href='mailto:ec24789@qmul.ac.uk'><i class='fas fa-envelope'></i>s.azadi-harsini@se24.qmul.ac.uk</a></p>
                <p>GitHub: <a href='https://github.com/sepehrazadiharsini' target='_blank'><i class='fab fa-github'></i> sepehrazadiharsini</a></p>
            </footer>
        </body>
        </html>";
        exit();
    }

    // Handle thepost action
    if ($_POST['action'] === 'post') {
        $query = "INSERT INTO blog_posts(title, content, date_created) VALUES ('$title', '$content', '$date_created')";

        if ($connection->query($query) === TRUE) {

            header("Location: viewBlog.php");
            exit();
        } else {
            echo "Error: " . $connection->error;
        }
    }

    // Handle the edit action
    if ($_POST['action'] === 'edit') {
        $_SESSION['edit_title'] = $title;
        $_SESSION['edit_content'] = $content;
        header("Location: addEntry.php");
        exit();
    }
}
?>
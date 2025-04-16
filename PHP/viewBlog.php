<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "blog";

// Create a connection to the database
$connection = new mysqli($server_name, $username, $password, $database_name);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch all blog posts from the database
$query = "SELECT * FROM blog_posts";
$result = $connection->query($query);

$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Fetch unique months and years for the dropdown
$archive_query = "SELECT DISTINCT DATE_FORMAT(date_created, '%Y-%m') AS archive_month FROM blog_posts ORDER BY archive_month DESC";
$archive_result = $connection->query($archive_query);

// Apply filters
if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title_filter = strtolower($_GET['title']);
    $posts = array_filter($posts, function ($post) use ($title_filter) {
        return strpos(strtolower($post['title']), $title_filter) !== false;
    });
}

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date_filter = $_GET['date'];
    $posts = array_filter($posts, function ($post) use ($date_filter) {
        return strpos($post['date_created'], $date_filter) !== false;
    });
}

if (isset($_GET['content']) && !empty($_GET['content'])) {
    $content_filter = strtolower($_GET['content']);
    $posts = array_filter($posts, function ($post) use ($content_filter) {
        return strpos(strtolower($post['content']), $content_filter) !== false;
    });
}

if (isset($_GET['month']) && !empty($_GET['month'])) {
    $selected_month = $_GET['month'];
    $posts = array_filter($posts, function ($post) use ($selected_month) {
        return strpos($post['date_created'], $selected_month) !== false;
    });
}

// Sort the posts by date_created
if (isset($_GET['sort']) && $_GET['sort'] === "oldest") {
    usort($posts, function ($a, $b) {
        return strtotime($a['date_created']) - strtotime($b['date_created']);
    });
} else {
    usort($posts, function ($a, $b) {
        return strtotime($b['date_created']) - strtotime($a['date_created']);
    });
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
                <li><a href="../experience.html">My Experience</a></li>
                <li><a href="../projects.html">My Portfolio</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="blog-posts">
            <h2>Blog Posts</h2>

            <!-- Filter Form -->
            <form method="GET" action="viewBlog.php" class="filter-form">
                <label for="title">Filter by Title:</label>
                <input type="text" id="title" name="title" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">

                <label for="date">Filter by Date:</label>
                <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">

                <label for="content">Filter by Content:</label>
                <input type="text" id="content" name="content" value="<?php echo isset($_GET['content']) ? htmlspecialchars($_GET['content']) : ''; ?>">

                <label for="month">Filter by Month:</label>
                <select name="month" id="month">
                    <option value="">All Months</option>
                    <?php while ($row = $archive_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['archive_month']); ?>" 
                            <?php echo (isset($_GET['month']) && $_GET['month'] === $row['archive_month']) ? 'selected' : ''; ?>>
                            <?php echo date("F Y", strtotime($row['archive_month'] . "-01")); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="sort">Sort by:</label>
                <select name="sort" id="sort">
                    <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] === "newest") ? 'selected' : ''; ?>>Newest</option>
                    <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] === "oldest") ? 'selected' : ''; ?>>Oldest</option>
                </select>

                <button type="submit">Apply Filters</button>
            </form>

            <!-- Display blog posts -->
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="blog-post">
                        <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-date">
                            <strong>Date:</strong> 
                            <?php 
                                $date = new DateTime($post['date_created']);
                                echo $date->format('jS F Y, H:i \U\T\C'); 
                            ?>
                        </p>
                        <p class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        <hr class="post-divider">
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-posts">No blog posts found for the selected filters.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer id="contact">
        <p>Contact</p>
        <p>Email: <a href="mailto:ec24789@qmul.ac.uk"><i class="fas fa-envelope"></i>s.azadi-harsini@se24.qmul.ac.uk</a></p>
        <p>GitHub: <a href="https://github.com/sepehrazadiharsini" target="_blank"><i class="fab fa-github"></i> sepehrazadiharsini</a></p>
    </footer>
</body>
</html>

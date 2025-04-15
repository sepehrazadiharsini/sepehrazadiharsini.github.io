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

// Fetch unique months and years from the blog_posts table for the dropdown
$archive_query = "SELECT DISTINCT DATE_FORMAT(date_created, '%Y-%m') AS archive_month FROM blog_posts ORDER BY archive_month DESC";
$archive_result = $connection->query($archive_query);

// Handle filtering by Title, Date, and Content
$filter_query = "WHERE 1=1"; // Default condition to allow appending filters
if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title_filter = $connection->real_escape_string($_GET['title']);
    $filter_query .= " AND title LIKE '%$title_filter%'";
}
if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date_filter = $connection->real_escape_string($_GET['date']);
    $filter_query .= " AND DATE(date_created) = '$date_filter'";
}
if (isset($_GET['content']) && !empty($_GET['content'])) {
    $content_filter = $connection->real_escape_string($_GET['content']);
    $filter_query .= " AND content LIKE '%$content_filter%'";
}

// Handle filtering by month
if (isset($_GET['month']) && !empty($_GET['month'])) {
    $selected_month = $connection->real_escape_string($_GET['month']);
    $filter_query .= " AND DATE_FORMAT(date_created, '%Y-%m') = '$selected_month'";
}

// Handle sorting by date
$order_query = "ORDER BY date_created DESC"; // Default to newest first
if (isset($_GET['sort'])) {
    $sort_order = $connection->real_escape_string($_GET['sort']);
    if ($sort_order === "oldest") {
        $order_query = "ORDER BY date_created ASC";
    }
}

// Fetch blog posts based on the filters and sorting order
$query = "SELECT * FROM blog_posts $filter_query $order_query";
$result = $connection->query($query);
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
                            <?php echo (isset($selected_month) && $selected_month === $row['archive_month']) ? 'selected' : ''; ?>>
                            <?php echo date("F Y", strtotime($row['archive_month'] . "-01")); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="sort">Sort by:</label>
                <select name="sort" id="sort">
                    <option value="newest" <?php echo (isset($sort_order) && $sort_order === "newest") ? 'selected' : ''; ?>>Newest</option>
                    <option value="oldest" <?php echo (isset($sort_order) && $sort_order === "oldest") ? 'selected' : ''; ?>>Oldest</option>
                </select>

                <button type="submit">Apply Filters</button>
            </form>

            <!-- Display blog posts -->
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <article class="blog-post">
                        <h3 class="post-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="post-date">
                            <strong>Date:</strong> 
                            <?php 
                                $date = new DateTime($row['date_created']);
                                echo $date->format('jS F Y, H:i \U\T\C'); 
                            ?>
                        </p>
                        <p class="post-content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <hr class="post-divider">
                    </article>
                <?php endwhile; ?>
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

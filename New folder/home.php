<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

$userId = $_SESSION["user_id"];

// Modify the query to check for role in another way (or just remove the role check if not needed)
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Check if role exists in your table or if you need to set some default role
$isAdmin = false; // Set a default value for admin check, or you can implement another check

// If role column exists or another way of checking admin, adjust the logic accordingly
// For example, if you have a 'username' or 'email' for admin users, you can check here.

if (isset($user['role']) && $user['role'] === 'admin') {
    $isAdmin = true;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
    <style>
        body {
            background: linear-gradient(45deg, #f0f0f0, #3498db, #f0f0f0, #3498db);
            background-size: 400% 400%;
            animation: gradientAnimation 5s infinite;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body>
    <h1>Welcome to the Home Page</h1>

    <?php
    echo '<a href="create_event.php">Create an Event</a><br>';

    echo '<h2>Your Events</h2>';
    // List events created by the logged-in user
    $query = "SELECT id, title, description, event_date, image, user_id FROM events WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);

    while ($event = $stmt->fetch()) {
        echo '<p>Title: ' . $event['title'] . '</p>';
        echo '<p>Description: ' . $event['description'] . '</p>';
        echo '<p>Date: ' . $event['event_date'] . '</p>';

        // Check if the user is the creator of the event using the user's ID from the users table
        if ($event['user_id'] == $userId) {
            // Display the "Delete Event" link
            echo '<a href="delete_event.php?event_id=' . $event['id'] . '">Delete</a>';
        }

        // Remove the reference to 'created_at'
        // echo '<p>Creation Date: ' . $event['created_at'] . '</p>';

        // Display the event image
        if (!empty($event['image'])) {
            echo '<img src="' . $event['image'] . '" alt="Event Image" style="max-width: 200px;">';
        }
        echo '<hr>';
    }

    echo '<h2>Events Created by Others</h2>';
    // List events created by others
    $query = "SELECT e.id, e.title, e.description, e.event_date, e.image, u.username
              FROM events e
              INNER JOIN users u ON e.user_id = u.id
              WHERE e.user_id != ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);

    while ($event = $stmt->fetch()) {
        echo '<p>Title: ' . $event['title'] . '</p>';
        echo '<p>Description: ' . $event['description'] . '</p>';
        echo '<p>Date: ' . $event['event_date'] . '</p>';
        // Remove the reference to 'created_at'
        // echo '<p>Creation Date: ' . $event['created_at'] . '</p>';
        echo '<p>Created by: ' . $event['username'] . '</p>';
        // Display the event image
        if (!empty($event['image'])) {
            echo '<img src="' . $event['image'] . '" alt="Event Image" style="max-width: 200px;">';
        }
        echo '<hr>';
    }
    ?>
    <a href="login.php">Logout</a>
</body>

</html>

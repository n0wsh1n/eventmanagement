<?php
session_start();
// Include external PHP file for database connection and adding events
include 'database_connection.php';

if (!isset($_SESSION['email'])) {
header("Location: index.php"); 
exit();
}else{
    
// Get the user's email from the session
$userEmail = $_SESSION['email'];

// Query to retrieve user data based on the email
$query = "SELECT * FROM user WHERE email = '$userEmail'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch user data
    while ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        // Retrieve other user data as needed
    }
} else {
    echo "No user found with this email.";
}
}
// Check if form is submitted
// Check if form is submitted
if (isset($_POST['add_event']) && $_POST['add_event'] == "Add") {
    // Handle form data
    $event_title = $_POST['event_title'];
    $event_type = $_POST['event_type'];
    $event_location = $_POST['event_location'];
    $event_notes = $_POST['event_notes'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $duration = $_POST['duration'];
    $user_id = $_SESSION['user_id'];
    // Prepare INSERT statement for event_info
    $insertInfoQuery = $conn->prepare("INSERT INTO event_info (event_title, event_type, location, notes) VALUES (?, ?, ?, ?)");
    $insertInfoQuery->bind_param("ssss", $event_title, $event_type, $event_location, $event_notes);

    if ($insertInfoQuery->execute()) {
        $lastInsertedId = $conn->insert_id;

        // Prepare INSERT statement for event
        $insertEventQuery = $conn->prepare("INSERT INTO event (event_info_id, event_date, event_time, duration, user_id) VALUES (?, ?, ?, ?, ?)");
        $insertEventQuery->bind_param("isssi", $lastInsertedId, $event_date, $event_time, $duration, $user_id);

        if ($insertEventQuery->execute()) {
            $r = "Event added successfully";
        } else {
            $r = "Error inserting into event table: " . $insertEventQuery->error;
        }
    } else {
        $r = "Error inserting into event_info table: " . $insertInfoQuery->error;
    }

    // Close prepared statements
    $insertInfoQuery->close();
    $insertEventQuery->close();
}

?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            background-color: #364d99 ;
        }

        .container {
            max-width: 700px;
            margin: 100px auto;
            background-color: #fff;
            padding: 100px ;
            border-radius: 100px;
            box-shadow: 20px 20px 20px rgba(0, 0, 100, 5);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }

        input[type="date"],
        input[type="time"],
        input[type="text"],
        input[type="submit"] {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        select {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #092eaa;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #052180;
        }


        /* Style for the menu */
        .menu {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .menu a {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #ddd;
        }

        #calendar table {
            width: 100%;
        }

        #calendar table caption {
            background: #365685;
            padding: 10px;
            color: white;
        }

        #calendar table td {
            text-align: center;
        }

    </style>
</head>

<body>
    <div class="container">
           <center>
            <a href="index.php"><img src="logo.png" alt="Event" width="100px"></a>
            <center>


        <?php

    if(!isset($_GET['action'])) {
        // Event List
        include 'include/event_list.php';
    }elseif ($_GET['action'] === 'add') {
        // Add Event form
        include 'include/add_event.php';
    }elseif ($_GET['action'] === 'logout') {
        // Delete Event form
        session_destroy();
        header("Location: index.php"); 
        exit();
    }
    ?>
    
        <?php
            if((isset($r)) && ($r !== NULL)){
                echo "<p>". $r ."</p>";
            }
        
        ?>
    </div>


</body>

</html>

<?php 
    $conn->close();
?>

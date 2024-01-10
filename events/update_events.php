<?php
session_start();

// Check user authentication
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

include 'database_connection.php';

if (isset($_POST['update_event_now']) && $_POST['update_event_now'] == "Update Now") {
    // Handle form data
    $updatedEventId = $_POST["event_info_id"];
    $updatedEventTitle = $_POST["event_title"];
    $updatedEventType = $_POST["event_type"];
    $updatedEventLocation = $_POST["event_location"];
    $updatedEventNotes = $_POST["event_notes"];
    $updatedEventDate = $_POST["event_date"];
    $updatedEventTime = $_POST["event_time"];
    $updatedDuration = $_POST["duration"];

    // Prepare and execute the SQL UPDATE statement for event_info
    $updateInfoQuery = $conn->prepare("UPDATE event_info SET event_title=?, event_type=?, location=?, notes=? WHERE event_info_id=?");
    $updateInfoQuery->bind_param("ssssi", $updatedEventTitle, $updatedEventType, $updatedEventLocation, $updatedEventNotes, $updatedEventId);

    if ($updateInfoQuery->execute()) {
        // Prepare and execute the SQL UPDATE statement for event
        $updateEventQuery = $conn->prepare("UPDATE event SET event_date=?, event_time=?, duration=? WHERE event_info_id=?");
        $updateEventQuery->bind_param("sssi", $updatedEventDate, $updatedEventTime, $updatedDuration, $updatedEventId);

        if ($updateEventQuery->execute()) {
            $r = "Event updated successfully";
        } else {
            $r = "Error updating event table: " . $updateEventQuery->error;
        }
    } else {
        $r = "Error updating event_info table: " . $updateInfoQuery->error;
    }

    // Close prepared statements
    $updateInfoQuery->close();
    $updateEventQuery->close();
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
            padding: 20px ;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 700px;
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
        .result_massage{
            text-align: center;
    color: blue;
        }
    </style>
</head>

<body>
    <div class="container">
        <center>
            <a href="index.php"><img src="logo.png" alt="Event" width="60px"></a>
        </center>
        <h2>Update Event</h2>
        <?php
if((isset($_POST['event_id'])) && ($_POST['event_id'] !== NULL)){
$query = "SELECT event_info.event_info_id, event_info.event_title, event_info.event_type, event_info.location, event_info.notes, event.event_date, event.event_time, event.duration FROM event_info INNER JOIN event ON event_info.event_info_id = event.event_info_id AND event_info.event_info_id = ".$_POST['event_id']."";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eEvent_info_id = $row["event_info_id"];
        $eEvent_title = $row["event_title"];
        $eEvent_type = $row["event_type"];
        $eLocation = $row["location"];
        $eNotes = $row["notes"];
        $eEvent_date = $row["event_date"];
        $eEvent_time = $row["event_time"];
        $eDuration = $row["duration"];     
        ?>
        <form method="post" action="update_events.php">
            Event Name: <input type="text" value="<?php echo $eEvent_title; ?>" name="event_title" required><br>
            Event type:
            <select id="event_type" name="event_type">
                <option value="<?php echo $eEvent_type; ?>"><?php echo $eEvent_type; ?></option>
                <option value="Meeting">Meeting</option>
                <option value="Birthday">Birthday</option>
                <option value="Holiday">Holiday</option>
                <!-- Add more options as needed -->
            </select><br>
            Event location: <input type="text" value="<?php echo $eLocation; ?>" name="event_location" required><br>
            Event notes: <input type="text" value="<?php echo $eNotes; ?>" name="event_notes" required><br>
            Event Date: <input type="date" value="<?php echo $eEvent_date; ?>" name="event_date" required><br>
            Event Time: <input type="time" value="<?php echo $eEvent_time; ?>" name="event_time" required><br>
            Duration: <input type="text" value="<?php echo $eDuration; ?>" name="duration" required><br>
            <input type="hidden" value="<?php echo $eEvent_info_id; ?>" name="event_info_id" required><br>
            <input type="submit" value="Update Now" name="update_event_now">
        </form>


        <?php 
             
    }
}  
}
        ?>
        <!-- Menu section -->
        <div class="menu">
            <a href="events.php">Upcoming Events</a>
            <a href="events.php?action=logout">Logout</a>
        </div>
        <?php
            if((isset($r)) && ($r !== NULL)){
                echo "<p class='result_massage'>". $r ."</p>";
            }
        
        ?>
    </div>
</body>

</html>
<?php 
$conn->close();
?>
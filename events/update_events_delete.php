<?php
session_start();
if (!isset($_SESSION['email'])) {
header("Location: index.php"); 
exit();
}
// Include external PHP file for database connection and adding events
include 'database_connection.php';
if((isset($_POST['delete_event'])) && ($_POST['delete_event'] == "Delete")){
   $eventToDelete = $_POST['event_id'];

    // Delete data from 'event' table first (assuming 'event_info_id' is a foreign key)
    $deleteEventQuery = "DELETE FROM event WHERE event_info_id = $eventToDelete";
    $deleteEventResult = $conn->query($deleteEventQuery);

    if (!$deleteEventResult) {
        echo "Error deleting event: " . $conn->error;
    } else {
        // Then delete data from 'event_info' table
        $deleteEventInfoQuery = "DELETE FROM event_info WHERE event_info_id = $eventToDelete";
        $deleteEventInfoResult = $conn->query($deleteEventInfoQuery);

        if (!$deleteEventInfoResult) {
            echo "Error deleting event info: " . $conn->error;
        } else {
            // Redirect to a confirmation page or reload the page
            header("Location: events.php");
            exit();
        }
    }
}


$conn->close();
?>
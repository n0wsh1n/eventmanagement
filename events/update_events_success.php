<?php
session_start();
if (!isset($_SESSION['email'])) {
header("Location: index.php"); 
exit();
}
// Include external PHP file for database connection and adding events
include 'database_connection.php';


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
            <a href="index.php"><img src="logo.png" alt="Event" width="60px"></a>
        </center>
        <h2>Update Event Successfully</h2>
        <!-- Menu section -->
        <div class="menu">
            <a href="events.php">Upcoming Events</a>
            <a href="events.php?action=logout">Logout</a>
        </div>
    </div>
</body>

</html>
<?php 
$conn->close();
?>
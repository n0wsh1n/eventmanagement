


<h2>Create Event</h2>
<form method="post" action="events.php">			

    Event Name: <input type="text" name="event_title" required><br>
    Event type: 
    <select id="event_type" name="event_type">
      <option value="meeting">Meeting</option>
      <option value="birthday">Birthday</option>
      <option value="holiday">Holiday</option>
      <option value="other">Other</option>

    </select><br>
    Event location: <input type="text" name="event_location" required><br>
    Event notes: <input type="text" name="event_notes" required><br>
    Event Date: <input type="date" name="event_date" required><br>
    Event Time: <input type="time" name="event_time" required><br>
    Duration: <input type="text" name="duration" required><br>
    <input type="submit" value="Add" name="add_event">
</form>

        <!-- Menu section -->
        <div class="menu">
            <a href="events.php">Upcoming Events</a>
            <a href="events.php?action=logout">Logout</a>
        </div>
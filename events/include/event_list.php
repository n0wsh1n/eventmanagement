<style>
    /* Styles for modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        
    }

    .modal-content {
        background-color: #fefefe;
        margin: 8% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 500px;
        border-radius: 4px;
        max-width: 80%;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .close {
        color: #e38686;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    .modal-content h2{        
    margin: 0;
    font-size: 21px;
    font-weight: 500;
    margin-top: 15px;
    }
    #eventDetails h2{        
    background: #365685;
    padding: 15px;
    color: white;
    margin-top: 5px;
    border-radius: 4px;
    }
    /* Highlight style */
    .highlight {
        background-color: #365685;
        font-weight: bold;
        cursor: pointer;
        color: white;
    }

    /* Today's date style */
    .today {
        background-color: #f9d9e2;
        /* Change the background color for today's date */
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    caption {
        font-size: 20px;
        font-weight: bold;
        padding: 10px 0;
    }

    .monthName {
        padding: 10px;
        text-align: center;
        font-size: 21px;
        background: #365685;
        color: white;
        font-weight: 800;
    }

    .arrow {
        cursor: pointer;
        font-size: 20px;
        margin: 0 10px;
        background: #41659b;
        padding: 2px 10px;
        border-radius: 20px;
    }

    #eventDetails {
        display: inline-block;
        text-align: center;
        width: 100%;
    }

    #eventDetails form {
    width: 46%;
    float: left;
    margin: 0px;
    padding: 2%;
    }
    #eventDetails form input[type="submit"] {
    display: block;
    width: 100%;
    margin-bottom: 0;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
    background: #325a95;
} 
    
    #eventDetails form input[type="submit"]:hover {
    background: #1d4074;
}
    #eventDetails form .delBtn{
        background: #8d3030!important;
    }
    #eventDetails form .delBtn:hover{
        background: #650d0d!important;
    }
    #eventDetails .eType{
    display: inline-block;
    margin: 5px;
    float: left;
    }
    #eventDetails .eLocation{
    display: inline-block;
    margin: 5px;
    float: right;
    }
    #eventDetails .eNotes{
                padding: 11px;
    display: inline-block;
    border: 1px solid #e3d9d9;
    margin: 5px;
    border-radius: 4px;
    width: 93%;
    min-height: 150px;
    }
    #eventDetails .eDate{
    display: inline-block;
    margin: 5px;
    font-size: 14px;
    float: left;
    }
    #eventDetails .eTime{
    display: inline-block;
    margin: 5px;
    font-size: 14px;
    }
    #eventDetails .eDuration{
    display: inline-block;
    margin: 5px;
    font-size: 14px;
    float: right;
    }
</style>
<h2>Upcoming Events</h2>

<div class="monthName">
    <span class="arrow" id="prevMonth">&lt;</span>
    <span id="monthYear"></span>
    <span class="arrow" id="nextMonth">&gt;</span>
</div>

<div id="calendar"></div>

<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Event Details</h2>
        <div id="eventDetails">
        </div>
    </div>
</div>
<!-- Menu section -->
<div class="menu">
    <a href="events.php?action=add">Create Event</a>
    <a href="events.php?action=logout">Logout</a>
</div>
<script>
    const calendarDiv = document.getElementById('calendar');
    const currentDate = new Date();
    let currentDisplayMonth = currentDate.getMonth();
    let currentDisplayYear = currentDate.getFullYear();

    const monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];

    function renderCalendar(events) {
        const firstDayOfMonth = new Date(currentDisplayYear, currentDisplayMonth, 1).getDay();
        const daysInMonth = new Date(currentDisplayYear, currentDisplayMonth + 1, 0).getDate();

        let calendarHTML = '<table>';
        calendarHTML += '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
        let dayCount = 1;
        let cellCount = 0;

        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDayOfMonth) {
                    calendarHTML += '<td class="empty-cell"></td>';
                } else if (dayCount <= daysInMonth) {
                    let cellClasses = '';
                    const currentDateFormatted = currentDisplayYear + '-' +
                        ('0' + (currentDisplayMonth + 1)).slice(-2) + '-' +
                        ('0' + dayCount).slice(-2);

                    if (events[currentDateFormatted]) {
                        cellClasses = 'highlight'; // Apply a class for highlighting
                    }

                    const todayFormatted = currentDate.getFullYear() + '-' +
                        ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
                        ('0' + currentDate.getDate()).slice(-2);

                    if (currentDateFormatted === todayFormatted) {
                        cellClasses += ' today'; // Apply a class for today's date
                    }

                    calendarHTML += '<td class="' + cellClasses + '" data-date="' + currentDateFormatted + '">' + dayCount + '</td>';
                    dayCount++;
                } else {
                    calendarHTML += '<td class="empty-cell"></td>';
                    cellCount++;
                }
                cellCount++;
            }
            calendarHTML += '</tr>';
            if (dayCount > daysInMonth) {
                break;
            }
        }
        calendarHTML += '</table>';
        calendarDiv.innerHTML = calendarHTML;

        // Attach event listeners after rendering the calendar
        attachEventListeners(events);
        document.getElementById('monthYear').textContent = monthNames[currentDisplayMonth] + ' ' + currentDisplayYear;
    }

    function attachEventListeners(events) {
        const highlightCells = document.querySelectorAll('.highlight');
        highlightCells.forEach(cell => {
            cell.addEventListener('click', function() {
                const clickedDate = this.getAttribute('data-date');
                const eventInfo = events[clickedDate];

                if (eventInfo) {
                    showModal(eventInfo);
                }
            });
        });

        document.querySelector('.close').addEventListener('click', closeModal);
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('eventModal')) {
                closeModal();
            }
        });
    }

    function showModal(eventInfo) {
        const modal = document.getElementById('eventModal');
        const eventDetails = document.getElementById('eventDetails');
        modal.style.display = 'block';
        eventDetails.innerHTML = eventInfo;
    }

    function closeModal() {
        const modal = document.getElementById('eventModal');
        modal.style.display = 'none';
    }

    // Fetch events data from PHP (You'll need to replace this with your PHP logic)
    <?php
    
    
$query = "SELECT event_info.event_info_id, event_info.event_title, event_info.event_type, event_info.location, event_info.notes, event.event_date, event.event_time, event.duration 
          FROM event_info INNER JOIN event ON event_info.event_info_id = event.event_info_id";

$result = $conn->query($query);

// Initialize an empty array to store events
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eEvent_info_id = $row["event_info_id"];
        $eEvent_title = $row["event_title"];
        $eEvent_type = $row["event_type"];
        $eLocation = $row["location"];
        $eNotes = $row["notes"];
        $eEvent_date = $row["event_date"];        
        $eEvent_date2 = date("F d, Y", strtotime($row["event_date"]));
        $eEvent_time = date("h:i A", strtotime($row["event_time"]));
        $eDuration = $row["duration"];  

        // Construct event data string
        $event_data = "<h2>" .$eEvent_title. "</h2><span class='eType'>Type:". $eEvent_type. "</span> <span class='eLocation'>Location: ". $eLocation. "</span><br><span class='eNotes'>Notes: ". $eNotes. "</span><br><span class='eDate'>Date: ". $eEvent_date2. "</span> <span class='eTime'>Time:". $eEvent_time. "</span> <span class='eDuration'>Duration: ". $eDuration. "</span><br>
            <div class='form_btn'><form method='post' action='update_events.php'><input type='hidden' value=' ". $row["event_info_id"]. "' name='event_id' required><input type='submit' value='Update' name='update_event'></form> <form method='post' action='update_events_delete.php'><input type='hidden' value='". $row["event_info_id"]. "' name='event_id' required><input type='submit' value='Delete' name='delete_event'  class='delBtn'></form></div>";

        // Add event data to the events array using the date as the key
        $events[$eEvent_date][] = $event_data;
    }
}
      echo 'const events = ' . json_encode($events) . ';'; // Pass events data to JavaScript
    ?>

    renderCalendar(events); 
    // Initial rendering

    // Change month functionality
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentDisplayMonth -= 1;
        if (currentDisplayMonth < 0) {
            currentDisplayMonth = 11;
            currentDisplayYear -= 1;
        }
        renderCalendar(events);
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentDisplayMonth += 1;
        if (currentDisplayMonth > 11) {
            currentDisplayMonth = 0;
            currentDisplayYear += 1;
        }
        renderCalendar(events);
    });

</script>

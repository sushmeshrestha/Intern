<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<style>
    #dateTime {
        display: none;
    }
    #timeDay {
        display: none;
    }
</style>
<script>
    changeSearchFilters = () => {
        var byDateTime = document.getElementById('dateTime');
        var byTimeDay = document.getElementById('timeDay');
        
        if(document.getElementById('searchFilter').value == 'Date and Time'){
            console.log('hi');
            byTimeDay.style.display = 'none';
            byDateTime.style.display = 'block';
        }
        if(document.getElementById('searchFilter').value == 'Time and Day'){
            console.log('hello');
            byDateTime.style.display = 'none';
            byTimeDay.style.display = 'block';
        }
    }
</script>
<body>
<div>
<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "resource_management";

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if ($conn -> connect_error) {
    die("Connection Failed : " .$conn -> connect_error );
} else {
    echo "<div style='text-align:center'>Connected Successfully</div>"."<br><br>";
}
?>

<div id="filter" onchange="changeSearchFilters()">
    <h4>Filters : </h4>
    <select name="searchFilter" id="searchFilter">
        <option value="" selected disabled >--- Select Filter ---</option>
        <option value="Date and Time">By Date and Time</option>
        <option value="Time and Day">By Time and Day</option>
    </select>

    <div id="dateTime">
        <form action="" method="POST">
            <div style="text-align:center">
                <label for="">Date</label>
                <input type="date" name="date" id="">  <br> <br>
                <label for="">Time</label>
                <input type="time" name="timeInDay" id=""> <br> <br>
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
    <br>
    <div id="timeDay">
        <form action="" method="POST">
            <div style="text-align:center">
                <label for="">Time</label>
                <input type="time" name="timeInWeek" id="">  <br> <br>
                <label for="">Week Days</label>
                <select name="weekDay" id="weekDay">
                    <option value="1">Sunday</option>
                    <option value="2">Monday</option>
                    <option value="3">Tueday</option>
                    <option value="4">Wednesday</option>
                    <option value="5">Thurday</option>
                    <option value="6">Friday</option>
                    <option value="7">Saturday</option>
                </select>
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</div>
<br>
<?php
    if(isset($_POST['date']) && isset($_POST['timeInDay'])) {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $dateInput = $_REQUEST['date'];
            $timeInput = $_REQUEST['timeInDay'];
            echo "Date Input: " .$dateInput .'<br>'.' Time Input: '. $timeInput;
            $searchQuery = "SELECT table_room_booking.room_id, table_rel_booking.bookID, table_room_booking.start_date, table_room_booking.end_date, table_rel_booking.start_time, table_rel_booking.end_time, table_rel_booking.bookDay 
            FROM table_room_booking INNER JOIN table_rel_booking 
            ON table_room_booking.id=table_rel_booking.bookID 
            WHERE 
            table_room_booking.start_date <= CAST($dateInput AS DATE) AND table_room_booking.end_date >= CAST($dateInput AS DATE) 
            AND NOT table_rel_booking.start_time >= CAST($timeInput AS TIME) AND table_rel_booking.start_time <= CAST($timeInput AS TIME);";
            // $searchQuery = "SELECT table_rel_booking.room_id, table_rel_booking.bookID FROM table_rel_booking.room RIGHT JOIN table_rel_booking ON table_room_booking.id = table_rel_booking.bookID";
            $searchResult = mysqli_query($conn, $searchQuery);
            if (mysqli_num_rows($searchResult) > 0) {
                echo "<table border='1' style='margin-left:auto; margin-right:auto; margin-top:10px;'>
                <tr>
                    <th>Room ID</th>
                    <th>Book ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Week Day</th>
                </tr>";
                while($row = mysqli_fetch_assoc($searchResult)){
                    echo "<tr>";
                        echo "<td>" . $row['room_id'] . "</td>";
                        echo "<td>" . $row['bookID'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['end_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "<td>" . $row['bookDay'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No result found";
            }
        }
    } elseif (isset($_POST['timeInWeek']) && isset($_POST['weekDay'])) {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $timeInWeek = $_REQUEST['timeInWeek'];
            $weekDay = $_REQUEST['weekDay'];
            echo "Time Input: " .$timeInWeek .'<br>'.' Week Day: '. $weekDay;
            $searchQuery = "SELECT table_room_booking.room_id, table_rel_booking.bookID, table_room_booking.start_date, table_room_booking.end_date, table_rel_booking.start_time, table_rel_booking.end_time, table_rel_booking.bookDay 
            FROM table_room_booking INNER JOIN table_rel_booking 
            ON table_room_booking.id=table_rel_booking.bookID 
            WHERE table_rel_booking.bookDay != $weekDay OR table_rel_booking.bookDay = $weekDay 
            AND 
            table_rel_booking.start_time >= CAST($timeInWeek AS TIME) AND table_rel_booking.end_time <= CAST($timeInWeek AS TIME);";
            // $searchQuery = "SELECT table_rel_booking.room_id, table_rel_booking.bookID FROM table_rel_booking.room RIGHT JOIN table_rel_booking ON table_room_booking.id = table_rel_booking.bookID";
            $searchResult = mysqli_query($conn, $searchQuery);
            if (mysqli_num_rows($searchResult) > 0) {
                echo "<table border='1' style='margin-left:auto; margin-right:auto; margin-top:10px;'>
                <tr>
                    <th>Room ID</th>
                    <th>Book ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Week Day</th>
                </tr>";
                while($row = mysqli_fetch_assoc($searchResult)){
                    echo "<tr>";
                        echo "<td>" . $row['room_id'] . "</td>";
                        echo "<td>" . $row['bookID'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['end_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "<td>" . $row['bookDay'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No result found";
            }
        }
    }
?>


<br><br>
<div class="roomBookDetailWeek">
    <form action="" method="POST">
        <label for="">Select Room :</label>
        <select name="roomName" id="rooms">
            <option value="">---Select---</option>
        <?php 
        $room = "SELECT * FROM table_room_booking";
        
        $room_result = mysqli_query($conn, $room);
        
        while($room_list = mysqli_fetch_assoc($room_result)) {
        ?>
            <option value="<?php echo $room_list["room_id"]; ?>">  <?php echo $room_list["room_id"];?> </option>
        <?php
            }
        ?>
        </select> &nbsp &nbsp &nbsp
        <input type="submit" value="Search">
    </form>
</div>

<?php
    if(isset($_POST['roomName'])) {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $roomName = $_REQUEST['roomName'];
            echo "Room Name : ".$roomName;
            $searchQuery = "SELECT table_room_booking.room_id, table_rel_booking.bookID, table_room_booking.start_date, table_room_booking.end_date, table_rel_booking.start_time, table_rel_booking.end_time, table_rel_booking.bookDay FROM table_room_booking
            INNER JOIN table_rel_booking ON table_room_booking.id=table_rel_booking.bookID 
            WHERE CAST('2022-11-17' AS DATE) BETWEEN table_room_booking.start_date AND table_room_booking.end_date 
            AND CAST('15:00' AS TIME) NOT BETWEEN table_rel_booking.start_time AND table_rel_booking.end_time";
            $searchResult = mysqli_query($conn, $searchQuery);
            if (mysqli_num_rows($searchResult) > 0) {
                echo "<table border='1' style='margin-left:auto; margin-right:auto; margin-top:10px;'>
                <tr>
                    <th>Room ID</th>
                    <th>Book ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Week Day</th>
                </tr>";
                while($row = mysqli_fetch_assoc($searchResult)){
                    echo "<tr>";
                        echo "<td>" . $row['room_id'] . "</td>";
                        echo "<td>" . $row['bookID'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['end_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "<td>" . $row['bookDay'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No result found";
            }
        }
    }
?>

</body>
</html>

<!--
    SELECT table_room_booking.room_id, table_rel_booking.bookID, table_room_booking.start_date, table_room_booking.end_date, table_rel_booking.start_time, table_rel_booking.end_time, table_rel_booking.bookDay 
        FROM table_room_booking INNER JOIN table_rel_booking 
        ON table_room_booking.id=table_rel_booking.bookID 
        WHERE 
        table_room_booking.start_date <= CAST('2022-10-13' AS DATE) AND table_room_booking.end_date >= CAST('2022-10-13' AS DATE) 
        AND NOT table_rel_booking.start_time <= CAST('10:00' AS TIME) AND table_rel_booking.end_time >= CAST('10:00' AS TIME);
--> 

<!--
    SELECT table_room_booking.room_id, table_rel_booking.bookID, table_room_booking.start_date, table_room_booking.end_date, table_rel_booking.start_time, table_rel_booking.end_time, table_rel_booking.bookDay 
        FROM table_room_booking INNER JOIN table_rel_booking 
        ON table_room_booking.id=table_rel_booking.bookID 
        WHERE table_rel_booking.bookDay != 6 OR table_rel_booking.bookDay = 6 
        AND 
        table_rel_booking.start_time >= CAST('10:00' AS TIME) AND table_rel_booking.end_time <= CAST('10:00' AS TIME);
-->
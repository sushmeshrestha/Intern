

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship</title>
    <style>
        body {
            background: #E9E9E9;
            font-family: "serif", Georgia;
        }
        .heading {
            text-align: center;
        }
        label{
            font-size: 18px;
        }
        input:datetime{
            width: 250px;
        }
        input[type=submit]{
            background: darkblue;
            color: white;
            border:none;
            border-radius:4px;
            height: 25px;
            margin-top:10px;
            cursor: pointer;
        }
        .form1{
            margin: 20px;
            text-align:center;  
        }
        .form2{
            margin:20px;
            text-align: center;
        }
        .availableRoom {
            background: #1d1f22;
            color: white;
            width:20%;
            margin: 20px;
            padding-left: 20px;
            padding-top:5px;
            padding-bottom:5px;
            height: width;
            border-radius: 8px;
            box-shadow: 4px 8px 5px #aaaaaa;
            
        }
    </style>
</head>
<body>
    <h1 class="heading">Resource Management</h1>
    <form action="" method="POST"  class="form1">
        <div class="dateTime">
            <label for="">Date and Time</label>
            <input type="date" name="dateInput" id="">
            <input type="time" name="timeInput" id="">
            <input type="submit" value="Search" name="DateTimeSubmit" id="submitDateTime">
        </div>
    </form>
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
$week_days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
if(isset($_POST['DateTimeSubmit'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dateInput = $_REQUEST['dateInput'];
        $timeInput = $_REQUEST['timeInput'];
        $weekDay = date('w', strtotime($dateInput));
        // echo "Week Day".$weekDay;
        $emptyRoom = [];
        
        $emptyBookIdQuery = "SELECT * FROM table_room_booking WHERE NOT EXISTS ( SELECT * FROM table_rel_booking WHERE table_room_booking.id = table_rel_booking.bookID)";
        $emptyBookIdQueryConn = mysqli_query($conn, $emptyBookIdQuery);
        if (mysqli_num_rows($emptyBookIdQueryConn) > 0) {
            while($empty = mysqli_fetch_assoc($emptyBookIdQueryConn)){
                array_push($emptyRoom, $empty['room_id']);
            }
            // print_r($emptyRoom);
        } 

        $query = "SELECT * FROM table_room_booking LEFT JOIN table_rel_booking ON table_room_booking.id=table_rel_booking.bookID";
        
        $queryConn = mysqli_query($conn, $query);
        if (mysqli_num_rows($queryConn) > 0 ) {
            while($row = mysqli_fetch_assoc($queryConn)){
                // array_push($roomId, $row['room_id']); 
                // array_push($id, $row['id']); 
                // echo "<br>";
                // echo "<br>";
                // echo "User  Time :" . $dateInput;
                // echo "<br>";

                // echo "Start Time :" .$row['start_date'];                
                // echo "<br>";

                // echo "End Time :" .$row['end_date'];
                // echo "<br>";
                // echo "<br>";

                if( $dateInput >= $row['start_date'] &&  $dateInput <= $row['end_date']) {
                    // echo $row['room_id'];   
                    // echo "is between date";   
                    if($weekDay == $row['bookDay']) {
                        // echo "<br>";
                        // echo "same week day";
                        if ($timeInput >= $row['start_time'] &&  $timeInput <= $row['end_time']) {
                            echo "<br>";
                            // echo "Room Booked";
                        } 
                    } else {
                        if (in_array($row['room_id'], $emptyRoom)) {
                        } else {
                            array_push($emptyRoom, $row['room_id']);
                        }
                    }
                } else {
                    if (in_array($row['room_id'], $emptyRoom)) {
                    } else {
                        array_push($emptyRoom, $row['room_id']);
                    }
                }
            }
            for($i=0; $i<count($emptyRoom); $i++){
                print_r("<div class='availableRoom'>");
                    print_r("<h3> Room Id: $emptyRoom[$i] </h3>");
                    
                    print_r("</div>");
            }
        }
    }
}
?>

    <br><br>
        <form action="" method="POST" class="form2">
            <div class="timeDay">
                <label for="">Start Time</label>
                <input type="time" name="startTime" id="">
                <label for="">End Time</label>
                <input type="time" name="endTime" id="">
                <label for="">Start Date</label>
                <input type="date" name="startDate" id="">
                <label for="">End Date</label>
                <input type="date" name="endDate" id="">
                <label for="">Day</label>
                <select name="day" id="">
                    <option value="0"> Sunday </option>
                    <option value="1"> Monday </option>
                    <option value="2"> Tuesday </option>
                    <option value="3"> Wednesday </option>
                    <option value="4"> Thursday </option>
                    <option value="5"> Friday </option>
                    <option value="6"> Saturday </option>
                </select>
                <input type="submit" name="DateTimeDaySubmit" value="Search" >
            </div>    
        </form>

<?php
if(!empty($_POST['DateTimeDaySubmit'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $startTime = $_REQUEST['startTime'];
        $endTime = $_REQUEST['endTime'];
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        $day = $_REQUEST['day'];

        echo "Time between :" .$startTime .' to ' . $endTime;
        echo "<br>";
        echo "Date between :" .$startDate .' to ' . $endDate;
        echo "<br>";
        echo "Day : ". $week_days[$day];
        echo "<br>";
        $emptyRoom = [];
        $query = "SELECT * FROM table_room_booking LEFT JOIN table_rel_booking ON table_room_booking.id=table_rel_booking.bookID";
        $queryConn = mysqli_query($conn, $query);
        if ($row = mysqli_num_rows($queryConn) > 0) {
            while($row = mysqli_fetch_assoc($queryConn)){ 
                if( $startDate >= $row['start_date'] &&  $endDate <= $row['end_date']) {
                    // echo $row['room_id'];   
                    // echo "is between date";   
                    if($day == $row['bookDay']) {
                        // echo "<br>";
                        // echo "same week day";
                        if ($startTime >= $row['start_time'] &&  $endTime <= $row['end_time']) {
                            echo "<br>";
                            // echo "Room Booked";
                        } 
                    } else {
                        if (in_array($row['room_id'], $emptyRoom)) {
                        } else {
                            array_push($emptyRoom, $row['room_id']);
                        }
                    }
                } else {
                    if (in_array($row['room_id'], $emptyRoom)) {
                    } else {
                        array_push($emptyRoom, $row['room_id']);
                    }
                } 
            }
            for($i=0; $i<count($emptyRoom); $i++){
                print_r("<div class='availableRoom'>");
                    print_r("<h3> Room Id: $emptyRoom[$i] </h3>");
                    
                    print_r("</div>");
            } 
        }
    }
}
?>    

<script type="text/javascript">
    var submitDateTime = document.getElementById('submitDateTime');

</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphical Display</title>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script> -->

    <!-- <link rel="stylesheet" href="css/intern.css"> -->
    <style>
        body{
            background: #E9E9E9;
            font-family: "serif", Georgia;
        }
        #graphDisplayForm{
            /* text-align: center; */
            display:flex;   
            margin-left: 25%;
            margin-top:40px;    
        }
        #graphDisplayForm label{
            font-weight: bolder;
            color: #1d1f22;
            padding-top: 8px;
            
        }
        input[type=date]{
            height: 30px;
            width: 150px;
            margin-left: 30px;
            border:none;
            border-radius: 8px;
            box-shadow: 4px 5px 5px #aaaaaa;
        }
        select{
            width: 150px;
            height: 30px;
            margin-left: 30px;
            border-radius: 8px;
            box-shadow: 4px 5px 5px #aaaaaa;
            border:none;
        }


        #submitBtn {
            background: #1d1f22;
            color: white;
            margin-left: 20px;
            height: 30px;
            width: 150px;
            border:none;
            border-radius: 8px;
            box-shadow: 4px 8px 5px #aaaaaa;
        }

        #graphViewRepresentation{
            background: #1d1f22;
            color: white; 
            margin: 20px 120px;
            border-radius: 6px;
        }
        #roomID{
            text-align: center; 
            text-decoration: underline gray;
            text-underline-offset: 4px;
            font-weight: bold;
            padding-top: 30px;
        }
        .graphView {
            /* backgroud:yellow; */
            display:flex;
            padding:10px;
            margin-top:5px;
            margin-left: 50px;
            margin-right: 250px;
            width:80%;
        }
        .graphView #bookedTime{
            background: rgb(128,0,0);
            color: white;   
            padding: 5px !important;
            margin-left : 15px;
            border: black 2px;
        }
    </style>
</head>
<body>


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
    // echo "Connected Successfully";
}

$room = "SELECT * FROM table_room_booking";

$room_result = mysqli_query($conn, $room);

?>

<form action="" method="POST" id="graphDisplayForm">
    <label for="">Choose a Room :</label>
    <input type="date" name="calendarInput" id="calendarInput" onchange="calendarChange()" required>
    <input type="text" name="weekDays" id="arrWeek" hidden>
    
    <br>
    <select name="rooms" id="rooms" required>
        <option value="">---Select---</option>
<?php 
        while($roomList = mysqli_fetch_assoc($room_result)) {
?>
    <option value="<?php echo $roomList["room_id"]; ?>">  <?php echo $roomList["room_id"];?> </option>
<?php
} 
?>  
        <input type="submit" value="Search" id='submitBtn' onclick="onSubmit()">
    </select>
</form>

<!-- Graphical Representation of Room Availability -->
<div id='graphViewRepresentation'>
<?php
if(isset($_POST['rooms'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $roomId = $_REQUEST['rooms'];
        echo "<div id='roomID'>";
        echo "<strong>Room ID : </strong>". $roomId;
        echo "</div>";
        $weekDayInput = $_REQUEST['weekDays'];
        
        // echo ($weekDayInput);
        // $weekDays = [];
        $weekDays = explode("," , $weekDayInput);
        // print_r($weekDays);
        // $array = json_decode($_POST['jsondata']);
        // echo $array;
        
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        

        for ($i=0; $i < 7; $i++) {
            $dateTime = new DateTime();
            $t = explode('-',($weekDays[$i]));
            echo "<br>";
            // echo $dateTime;
            $year = $t[0];
            $month = $t[1];
            $date = $t[2];
            $dateTime->setDate($year,$month,$date);
            $fullDate = $dateTime->format('Y-m-d');
            // echo $fullDate;
            
            // echo gettype($weekDays[$i]);
            $graphical = "SELECT * FROM table_room_booking LEFT JOIN table_rel_booking ON table_room_booking.id=table_rel_booking.bookID 
            WHERE table_room_booking.room_id='$roomId' AND 
            CAST('2022-11-24' AS DATE) BETWEEN table_room_booking.start_date and table_room_booking.end_date AND
            table_rel_booking.bookDay = $i 
            ORDER BY table_rel_booking.start_time ASC";
            // print_r('i'.$weekDays[$i]);
            $arr = [];
            $graphicalConn = mysqli_query($conn, $graphical);
            if (mysqli_num_rows($graphicalConn) > 0) {
                while($graphicalResult = mysqli_fetch_assoc($graphicalConn)) {
                    // echo $graphicalResult['room_id'];
                    // echo "<br>";
                    // echo $graphicalResult['bookID'];
                    $start_time = $graphicalResult['start_time'];
                    $end_time = $graphicalResult['end_time'];
                    array_push($arr, $start_time. ' - ' .$end_time); 
                    // echo "<br>";
            }
            } else {
                
            }
            
                echo "<div class='graphView'>"; 
                echo "<strong> $date $months[$month], $year  $days[$i] :   </strong> ";
                for ($x=0; $x < count($arr); $x++) {
                    echo "<div id='bookedTime'>";
                        print_r($arr[$x]);
                    echo "</div>";
                }
                echo '</div>';
        }
    }
}
?>
</div>

<script type="text/javascript">
        var getDateArray = function(start, end){
        var arr = new Array();
        var dt = new Date(start);
        console.log(dt);
        while (dt <= end) {
            var d = new Date(dt);   
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat',]
            // arr.push(months[d.getMonth()] + '-'+ d.getDate() + '  ' + days[d.getDay()]);
            // arr.push(d.toDateString());
            arr.push(d.getFullYear()+'-'+ d.getMonth()+ '-' + d.getDate());
            dt.setDate(dt.getDate() + 1);
        }
        return arr;
    }
    var calendarChange = function(){
        var calendarInput = document.getElementById("calendarInput").value;
        console.log(calendarInput);
        return calendarInput;
    }
    
    function onSubmit() {
        console.log('hello');
        var calendarDate = calendarChange();
        console.log(',,'.calendarDate);
        // const d = new Date();
        const d = new Date(calendarDate);
        
        const weekDay = d.getDay()    // returns the weekday of a date as a number (0-6)
        console.log(weekDay);
        const monthDay = d.getDate()  // returns the day of a date as a number (1-31)
        console.log(monthDay);
        const firstDayofWeek = d.setDate(monthDay - (weekDay % 7));
        const lastDayofWeek = d.setDate(monthDay - weekDay + 6  );
        const weekFirstDate = new Date(firstDayofWeek);
        const weekLastDate = new Date(lastDayofWeek);

        var weekDays = getDateArray(weekFirstDate, weekLastDate);
        console.log(weekDays);
        document.getElementById("arrWeek").value = weekDays.toString();
        
        return weekDays;

    }
</script>   

</body>
</html> 

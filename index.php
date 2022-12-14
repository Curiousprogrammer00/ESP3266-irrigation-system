<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monitoring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" type="image/x-icon" href="/img/monitoring.png">
</head>
<body id="divs">
<?php
// define variables and set to empty values
$file = fopen("list.txt", "a+");
$dateA = date("d-m-Y");
$timeA = date("H:i");
$t = $h = $hic = $val = "";

$headers = array('http'=>array('method'=>'GET','header'=>'Content: type=application/json \r\n'.'$agent \r\n'.'$hash'));

$context=stream_context_create($headers);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sonzori";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$current_data = file_get_contents('list.txt');
$array_data = json_decode($current_data, true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $t1 = test_input($_GET["t"]);
    $h1 = test_input($_GET["h"]);
    $val1 = test_input($_GET["val"]);
    $hic1 = test_input($_GET["hic"]);
    $dataJson = Array("h"=>$h1, "t"=>$t1, "val"=>$val1, "hic"=>$hic1, "time"=>$timeA, "date"=>$dateA);
    $array_data[] = $dataJson;
    $final_data = json_encode($array_data);
    file_put_contents("list.txt", $final_data);




}/*
    echo json_decode($myFile, true);
    $h = json_decode(fgets($myFile),true)["h"];
    $t = json_decode(fgets($myFile))->t;
    $val = json_decode(fgets($myFile))->val;
    $hic = json_decode(fgets($myFile))->hic;
    $dateA = json_decode(fgets($myFile))->data;
    $timeA = json_decode(fgets($myFile))->time;


*/
$current_data = file_get_contents('list.txt');
$array_data = json_decode($current_data, true);
$t1 = "25";
$h1 = "43";
$val1 = "21";
$hic1 = "45";
$dataJson = Array("h"=>$h1, "t"=>$t1, "val"=>$val1, "hic"=>$hic1, "time"=>$timeA, "date"=>$dateA);
$array_data[] = $dataJson;
$final_data = json_encode($array_data);
file_put_contents("list.txt", $final_data);





$str = file_get_contents("list.txt",FILE_USE_INCLUDE_PATH,$context);

$str=utf8_encode($str);

$str=json_decode($str, true);




// display the name of the first person
foreach ($str as $rs) {
    $h = stripslashes($rs["h"])."%";
    $t = stripslashes($rs["t"])."C";
    $val = stripslashes($rs["val"])."%";
    $hic = stripslashes($rs["hic"])." C";
    /*$dateA = stripslashes($rs["date"]);
    $timeA = stripslashes($rs["time"]);*/
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return htmlspecialchars($data);
}

?>
<div class="header">
    <a href="index.php" class="logo">Podgorica</a>
    <a class="" href="last24.html">Poslednja 24h</a>
    <div class="header-right">
        <a class="active" href="#">Poslednjih 7 dana</a>


    </div>
</div >
  <div align="center" >
    <p>Vlaznost vazduha: <?php echo $h ?> </p>
    <p>Temperatura vazduha: <?php echo $t ?> </p>
    <p>Vlaznost zemljista: <?php echo $val ?> </p>
    <p>Temperatura zemljista: <?php echo $hic; ?> </p>
      <p>Poslednje vrijeme poslednjeg mjerenja: <?php echo $timeA; ?> </p>
      <p>Poslednje datum poslednjeg mjerenja: <?php echo $dateA; ?> </p>



      <footer id="footer">
          <span class="copyright" style="color: #dddddd">&copy; Zijad Kurpejovic. Design: <a href="https://www.instagram.com/kevassoul/">@kevassoul</a>.</span>
      </footer>
  </div>


</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <style>
    h1{
      text-align: center;
    }
    *{
       color: #935dd9;
       align: center;
    }
  </style>
</head>

<body>
<h1>Parse Report</h1>
<?php
// Create connection
$servername = "mars.cs.qc.cuny.edu";
$username = "khak0961";
$password = "24080961";
$dbName = 'khak0961';
$conn = mysqli_connect($servername, $username, $password, $dbName);

$stmt = $conn->prepare('SELECT * FROM source;');
$stmt->execute();
$result2 = $stmt->get_result();


echo "<table border='1'>";
echo "<tr>";
echo "<th>Source_ID</th>";
echo "<th>Source_Name</th>";
echo "<th>Source_URL</th>";
echo "<th>Source_Begin</th>";
echo "<th>Source_End</th>";
echo "<th>Source_DTM</th>";
echo "<th>Word</th>";
echo "</tr>";
while($row = mysqli_fetch_assoc($result2)) {
  echo "<tr>";
    echo "<td>" . $row['source_id']."</td>";
    echo "<td>" . $row['source_name']."</td>";
    echo "<td> <a href = $row[source_url]> URL Link </a></td>";
    echo "<td>" . $row['source_begin']."</td>";
    echo "<td>" . $row['source_end']."</td>";
    echo "<td>" . $row['parsed_dtm']."</td>";
    echo '<td><form action = "report.php" method ="POST">
    <input type = "hidden" name="source_id" value ="'.$row['source_id'].'">
    <input type = "submit" name="submit" value="Words">
    </form></td>';
    echo "</tr>";
}
echo "</table>";
?>
</body>

</html>




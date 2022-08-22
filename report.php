<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <style>
    h1{
      text-align: left;
    }
    *{
      color: black;
      align: left;
    }
  </style>
</head>
<body>
<h1>WORDS REPORT</h1>
<?php
// Create connection
$servername = "mars.cs.qc.cuny.edu";
$username = "khak0961";
$password = "24080961";
$dbName = 'khak0961';
$conn = mysqli_connect($servername, $username, $password, $dbName);

$source_id = "";
$source_id = $_POST['source_id'];


$total_num_words = $conn->prepare("SELECT SUM(freq) FROM occurence WHERE source_id=?");
$total_num_words->bind_param("i", $source_id);
$total_num_words->execute();
$result= $total_num_words->get_result();
$t = mysqli_fetch_row($result);
$total_num_words_int = $t[0];


$stmt = $conn->prepare('SELECT word, freq FROM occurence WHERE source_id=?;');
$stmt->bind_param("i", $source_id);
$stmt->execute();
$result2 = $stmt->get_result();

echo "<table border='1'>";
echo "<tr>";
echo "<th>Words</th>";
echo "<th>Frequency</th>";
echo "<th>Percentage</th>";
echo "</tr>";
while($row = mysqli_fetch_assoc($result2)) {
    echo "<tr>";
    echo "<td>" .$row['word']."</td>";
    echo "<td>" .$row['freq']."</td>";
    echo "<td>".$row["freq"]/$total_num_words_int * 100 . "%". "</td>";
    echo "</tr>";
}
echo "</table>";

?>

</body>

</html>
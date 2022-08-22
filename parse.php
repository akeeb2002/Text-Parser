<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <style>
    *{
      text-align: center;
      color: #935dd9;
    }
    .Repports{
      border: none;
      cursor: pointer;
      background-color: #8039dd;
      color: rgb(232, 232, 232);
      height: 30px;
      width: 200px;
      font-weight: bold;
    }
  </style>
</head>


<body>
<?php

$servername = "mars.cs.qc.cuny.edu";
$username = "khak0961";
$password = "24080961";
$dbName = 'khak0961';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

$source_name = $_POST["source_name"];
$source_url = $_POST["source_url"];
$source_begin = $_POST["source_begin"];
$source_end = $_POST["source_end"];


function insert_source($source_name,$source_url,$source_begin,$source_end,$conn){
  $sql = 'INSERT INTO source (source_name, source_url, source_begin, source_end) VALUES ("'.$source_name.'", "'. $source_url.'", "'.$source_begin.'", "'.$source_end.'");';
  if ($conn->query($sql) === TRUE) {
    echo "<div class='text-center'>";
    echo "Congratulations! New record created."."<br>";
    echo "</div>";
  } else {
    echo "<div class='text-center'>";
    echo "Error: " . $sql . "<br>" . $conn->error;
    echo "</div>";
  }
}

insert_source($source_name,$source_url,$source_begin,$source_end,$conn);

// send a curl get request to get the html input
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $source_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);

$dom = new DOMDocument();

@ $dom->loadHTML($html);

$bodys = $dom->getElementsByTagName('body'); 

$body_array = array();

foreach($bodys as $body) {
    $title_text = $body->textContent;
    $body_array[] = $title_text;
}
$str = $body_array[0];

//restrict the text to users specifications
function my_substr_function($str, $start, $end){  return substr($str, $start, $end - $start);}

function get_source_id($conn){
  $sql2 = 'SELECT source.source_id from source; ';
  $result = $conn->query($sql2);
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       // echo "<br> id: ". $row["source_id"] . "<br>";
        $sourceID = $row["source_id"];
    }
    } else {
    echo "0 results<br>";
    }    

    return $sourceID;
}
$source_id=get_source_id($conn);


function key_values_print($str,$conn,$source_id){
  $modString  = preg_replace('/[^a-z0-9 ]+/i', '', $str);
  $modifiedString = strtoupper($modString);
  $words = array_count_values(str_word_count($modifiedString, 1));
  arsort($words);
  insert_occurence($words,$source_id,$conn);
}

function insert_occurence($words,$source_id,$conn){
  foreach($words as $key => $value) {
    //echo "Key=" . $key . ", Value=" . $value . "<br>";
    $sql2 = 'INSERT INTO occurence(source_id, word, freq) VALUES ("'.$source_id.'", "'.$key.'", "'. $value.'");';
    $conn->query($sql2);
    }
}

//check what the user inputted for begin and end
if (($source_begin == null && $source_end == null) || (($source_begin != null && $source_end != null) && (strpos($str, $source_begin) == FALSE && strpos($str, $source_end) == FALSE))) {
  //take the whole text if either of the following is true:
  //the user did not enter anything in being and end.
  //the user did enter inputs in begin and end BUT both inputs were not in the text.
  echo "Either nothing was specified in begin and end fields,"."<br>";
  echo "or something was entered in both fields BUT those inputs weren't found in the text."."<br>";
  if ($source_begin == null && $source_end == null){echo "In this case, the begin and end were not provided."."<br>";}
  elseif (strpos($str, $source_begin) == FALSE && strpos($str, $source_end) == FALSE) {echo "In this case, both of the inputs were not in the text."."<br>";}
  echo "Hence we will parse the whole text."."<br>";
  key_values_print($str, $conn, $source_id);
} 
elseif ((($source_begin != null && $source_end == null) && (strpos($str, $source_begin) == TRUE)) || (($source_begin != null && $source_end != null)&&(strpos($str, $source_begin) == TRUE && strpos($str, $source_end) == FALSE))) {
  //take the text from where the user provided until the end of the text if either of the following is true:
  //the user provided beginning and not the end AND beginning is in the text.
  //the user provided both but only the beginning was in the text and the end was not found.
  echo "Either only the beginning was specified and was found in the text,"."<br>";
  echo "or both fields were entered and only beginning was found in the text."."<br>";
  if ($source_begin != null && $source_end == null){echo "In this case only the beginning was provided."."<br>";}
  elseif(strpos($str, $source_begin) == TRUE && strpos($str, $source_end) == FALSE){echo "In this case, both were provided but only the beginning was found in the text."."<br>";}
  echo "Hence we will parse from first instance of the beginning specified until the end of the text."."<br>";
  $inputted = my_substr_function($str,strpos($str,$source_begin),strlen($str));
  key_values_print($inputted, $conn, $source_id);
} 
elseif ((($source_begin == null && $source_end != null) && (strpos($str, $source_end) == TRUE)) || (($source_begin != null && $source_end != null)&&(strpos($str, $source_begin) == FALSE && strpos($str, $source_end) == TRUE))) {
  //take the text from the beginning until where the user provided if either of the following is true:
  //the user provided the end and not the beginning AND the end is in the text.
  //the user provided both but only the end was in the text and the beginning was not found. 
  echo "Either only the ending was specified and was found in the text,"."<br>";
  echo "or both fields were entered and only ending was found in the text."."<br>";
  if ($source_begin == null && $source_end != null){echo "In this case only the ending was provided."."<br>";}
  elseif(strpos($str, $source_begin) == FALSE && strpos($str, $source_end) == TRUE){echo "In this case, both were provided but only the ending was found in the text."."<br>";}
  echo "Hence we will parse from the beginning of the text until the last instance of the end specified."."<br>";
  $input = my_substr_function($str,0,strrpos($str,$source_end));
  key_values_print($input, $conn, $source_id);
} 
elseif(($source_begin != null && $source_end != null) && (strpos($str, $source_begin) == TRUE && strpos($str, $source_end) == TRUE)){
  //take the text from where the user specified until where they specefied if either of the following is true:
  //the user provided both the beginning and the end and both were found in the text
  echo "The inputs provided in the begin and end fields were both found in the text."."<br>";
  echo "Hence we will parse from the first instance of beginning specified until the last instance of the end specified."."<br>";
  $inputho = my_substr_function($str,strpos($str,$source_begin),strrpos($str,$source_end));
  key_values_print($inputtho, $conn, $source_id);
}

?>

<form action="https://venus.cs.qc.cuny.edu/~khak0961/cs355/source.php">
    <input type="submit" class= "Repports" value="Go to Reports" />
</form>

</body>

</html>
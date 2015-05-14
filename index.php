<?php

  $mem = new Memcached();
  $mem->addServer('localhost', 11211);

if ( isset($_REQUEST["lat"]) && isset($_REQUEST["lon"] ) )
  {
   if ( $mem->get($_REQUEST["lat"].$_REQUEST["lon"]) != NULL )
      $result_Set = $mem->get($_REQUEST["lat"].$_REQUEST["lon"]);
   else 
     {
   
     $data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$_REQUEST["lat"].','.$_REQUEST["lon"].'&key=API_KEY');
     $json = json_decode($data, true);
     $data1 = file_get_contents('https://www.thecollegestash.com/json/out.php?zip='.$json["results"][0]["address_components"][7]["long_name"]);
     $json_Obj = json_decode($data1, true);
   
    $result_Set = '<br>Temperature: '.$json_Obj["temperature"].'<br>Condition: '.$json_Obj["condition"].'<br>Place: '.$json_Obj["neighborhood"].'<br>City: '.$json_Obj["city"].'<br>State: '.$json_Obj["state_name"].'<br>Zip: '.$json_Obj["zip"].'<br>Country: '.$json_Obj["country_name"].'<br><br>';
	 $mem->set($_REQUEST["lat"].$_REQUEST["lon"], $result_Set, time()+60);
	 include_once("scripts/db_connect.php");
	 $sql = 'INSERT INTO cherry (c_lat, c_lon, c_zip, c_temp, c_date, c_country, c_state, c_city, c_time) VALUES( $_REQUEST["lat"], $_REQUEST["lon"], $json["results"][0]["address_components"][7]["long_name"], $json_Obj["temperature"], date(), $json_Obj["country_name"], $json_Obj["state_name"], $json_Obj["city"], now())';
    if(!mysqli_query($link, $sql))
	{ $result_Set = "";   
          die(mysqli_error($link));  }

	  }
  } 
else 
$result_Set = "";

?>
<!DOCTYPE html>
<html>
<head>
  <title>Track Location</title>
  <link rel="stylesheet" type="text/css" href="style/main.css">
</head>
<body>
<center>
<div><?php echo $result_Set; ?></div><br>
<a href="#" class="myButton" onClick="getLocation()">Get Live Weather</a><br>
<p id="location"></p>
</center>
<script>
var x = document.getElementById("location");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
window.location.href = "index.php?lat=" + position.coords.latitude + "&lon=" + position.coords.longitude;
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
</script>

</body>
</html>

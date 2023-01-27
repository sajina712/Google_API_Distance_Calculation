<?php


function getDistance($addressFrom, $addressTo, $unit = ''){
   
    // Google API key
    $apiKey = 'Google API Key';
    
    // Change address format
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);
    
    // Geocoding API request with start address
    $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
    $outputFrom = json_decode($geocodeFrom);
    if(!empty($outputFrom->error_message)){
        return $outputFrom->error_message;
    }
    
    // Geocoding API request with end address
    $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
    $outputTo = json_decode($geocodeTo);
    if(!empty($outputTo->error_message)){
        return $outputTo->error_message;
    }
    
    // Get latitude and longitude from the geodata
    $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
    
    // Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;
    
    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2).' km';
    }elseif($unit == "M"){
        return round($miles * 1609.344, 2).' meters';
    }else{
        return round($miles, 2).' miles';
    }
} 

if(isset($_REQUEST['btnSubmit'])){

    $addressFrom = $_REQUEST['starting_point'];
    $addressTo = $_REQUEST['end_point'];


    $distance = getDistance($addressFrom, $addressTo, 'M');

    echo $distance; 
} // end submit check 


?>
<html>
<head> </head>
<title>Google API</title>
<body>
    <form name="google_api" id="google_api" method="post" action="index.php" enctype="multipart/form-data">
      <div style="padding-top:10%; padding-left:20%;">
        <h2 style="padding-left:7%;">Google API</h2>
        <table >
            <tr>
                <td>Starting Point</td>
                <td><input type="text" name="starting_point" id="starting_point" ></td>
            </tr>
            <tr>
                <td>End Point</td>
                <td><input type="text" name="end_point" id="end_point" ></td>
            </tr>
            <tr>
                <td>&nbsp</td>
                <td><input type="submit" name="btnSubmit" id="btnSubmit" ></td>
            </tr>

           
        </table>
      </div>
    </form>
</body>
</html>
<?php // Function to calculate distance between two points using the Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    $distance = $earthRadius * $c;
    echo "dLat: $dLat, dLon: $dLon, a: $a, c: $c, distance: $distance\n";
    return round($distance, 2); // Round to 2 decimal places
}



$startLat = 18.4778752;
$startLon = 73.8557952;
$endLat = 18.4778752;
$endLon = 73.8557952;

$distance = calculateDistance($startLat, $startLon, $endLat, $endLon);
echo $distance;
?>
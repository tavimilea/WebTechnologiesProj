<?php
class geoChartController
{
    function showList($filtersArray)
    {
        
        $countries_map  = array();
        $countries_array = array();
        $i              = 0;
        
        
        if (is_array($filtersArray) || is_object($filtersArray)) {
            
            foreach ($filtersArray as $item) {
                if (is_array($item) || is_object($item))
                    foreach ($item as $key => $value) {
                        $key_string = $key ;
                
                        if (strcmp($key_string, 'country_txt') == 0) {
                            
                            if (array_key_exists($value, $countries_map)) {
                                $countries_map[$value] = $countries_map[$value] + 1;
                            } else {
                                
                                $countries_map[$value] = 1;
                                $countries_array[$i]         = $value;
                                $i                          = $i + 1;
                            }
                        }
                    }
            }
        }
        
        if (is_array($filtersArray) || is_object($filtersArray)) {
            echo "<script>
        google.charts.load('current', {
            'packages':['geochart'],
            'mapsApiKey': 'AIzaSyDtKxEnjmFmud3qf7EQAxdvUyDGrbxhXeo'
          });
          google.charts.setOnLoadCallback(function drawRegionsMap(){
              var data = google.visualization.arrayToDataTable([
              ['Country', 'Results'],";

            echo "[\"" . $countries_array[0] . "\"" . "," . $countries_map[$countries_array[0]]. ']';
            for ($i = 1; $i < sizeof($countries_array); $i++) {
                echo ",[\"" . $countries_array[$i] . "\"" . "," . $countries_map[$countries_array[$i]] . ']';
            }
            echo "]);
    
            var options = {
                width: 900,
                height: 600,
                colorAxis: {colors: ['#367ea3', '#a1436d']}};
    
            var chart = new google.visualization.GeoChart(document.getElementById('map'));
    
            google.visualization.events.addListener(chart, 'ready', function () {
                var imgUri = chart.getImageURI();
                document.getElementById('PNGdownload').href = imgUri;
                
                var svgAsXML = (new XMLSerializer).serializeToString(document.getElementsByTagName('svg')[0]);
                var svgData = 'data:image/svg+xml,' + encodeURIComponent(svgAsXML);
                
                document.getElementById('SVGdownload').href = svgData;
            });
            
            chart.draw(data, options);
          });
          </script>";
        }
    }
}

?>
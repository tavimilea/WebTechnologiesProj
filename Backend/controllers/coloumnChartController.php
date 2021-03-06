<?php
class coloumnChartController
{

    function showList($filtersArray){
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
      echo"<script>
      google.charts.load('current', {packages:['corechart']});
      google.charts.setOnLoadCallback(function drawChart(){
        var data = google.visualization.arrayToDataTable([
          ['Element', 'Density', { role: 'style' } ],";
          echo "[\"" . $countries_array[0] . "\"" . "," . $countries_map[$countries_array[0]]. ', '. "\"#669be8\"]";
            for ($i = 1; $i < sizeof($countries_array); $i++) {
                echo ",[\"" . $countries_array[$i] . "\"" . "," . $countries_map[$countries_array[$i]] . ', '. "\"#669be8\"]";
            }
            echo "]);
  
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                         { calc: 'stringify',
                           sourceColumn: 1,
                           type: 'string',
                           role: 'annotation' },
                         2]);
  
        var options = {
          title: 'Density of terrorist attack per country',
          width: 800,
          height: 600,
          bar: {groupWidth: '95%'},
          legend: { position: 'none' },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('map'));

        google.visualization.events.addListener(chart, 'ready', function () {
			var imgUri = chart.getImageURI();
			document.getElementById('PNGdownload').href = imgUri;
			
			var svgAsXML = (new XMLSerializer).serializeToString(document.getElementsByTagName('svg')[0]);
			var svgData = 'data:image/svg+xml,' + encodeURIComponent(svgAsXML);
			
			document.getElementById('SVGdownload').href = svgData;
        });
        
        chart.draw(view, options);});
        </script>";
    }
    }



?>
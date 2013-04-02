<?php
$q = "select * from user where user_type_id='3' and user_active='1'";
$r = mysql_query($q) or die(mysql_error() . "<br><b>Query:</b> $q<br>");
$rows = mysql_num_rows($r);
if ($rows > 0) {
    $arr = mysql_fetch_rowsarr($r);
    $graphString = "";
    for ($i = 0; $i < $rows; $i++) {
        $graphString.=", ['" . $arr[$i]["user_name"] . "', " . getGuardsTotalHour($arr[$i]["user_id"]) . "]";
    }
    //echo $graphString;
}

?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['imagechart']});
	    /*
google.load('visualization', '1', {packages: ['corechart']});
function drawVisualization() {
// Create and populate the data table.
var data = google.visualization.arrayToDataTable([
  ['Month', 'Graph1',   'Graph2', 'Graph3',  'Graph4', 'Graph4', 'Graph4', 'Graph4'  ],
  ['July',   12,          10,       15,        17,       9,        11,       5       ],
  ['August', 10,           5,        9,        11,      13,         3,       6       ]
]);
// Create and draw the visualization.
new google.visualization.ColumnChart(document.getElementById('visualization')).
        draw(data,
                 {title:"Project Progress report",		 
                  width:610, height:150,
                  hAxis: {title: "Year"}}
        );
}
google.setOnLoadCallback(drawVisualization);
     */
    function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
            ['Name', 'Hours']
<?php echo $graphString; ?>
       ]);
        var options = {};
        // 'bhg' is a horizontal grouped bar chart in the Google Chart API.
        // The grouping is irrelevant here since there is only one numeric column.
        options.cht = 'bvg';
        // Add a data range.
        var min = 0;
        var max = 250;
        options.chds = min + ',' + max;
        // Now add data point labels at the end of each bar.
        // Add meters suffix to the labels.
        var meters = 'N** h';
        // Draw labels in pink.
        var color = 'ff3399';
        // Google Chart API needs to know which column to draw the labels on.
        // Here we have one labels column and one data column.
        // The Chart API doesn't see the label column.  From its point of view,
        // the data column is column 0.
        var index = 0;
        // -1 tells Google Chart API to draw a label on all bars.
        var allbars = -1;
        // 10 pixels font size for the labels.
        var fontSize = 11;
        // Priority is not so important here, but Google Chart API requires it.
        var priority = 0;
        options.chm = [meters, color, index, allbars, fontSize, priority].join(',');
        // Create and draw the visualization.
        new google.visualization.ImageChart(document.getElementById('visualization')).
            draw(data, options);
    }
    google.setOnLoadCallback(drawVisualization);

</script>

<?php if(getFileName()=='index.php'){ 
?>
<!--
You are free to copy and use this sample in accordance with the terms of the
Apache license (http://www.apache.org/licenses/LICENSE-2.0.html)
-->
<!--Google graphc-->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<!---->
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Graph1',   'Graph2'],
          ['2003',  1336060,   3817614 ],
          ['2004',  1538156,   3968305 ],
          ['2005',  1576579,   4063225 ],
          ['2006',  1600652,   4604684 ],
          ['2007',  1968113,   4013653 ],
          ['2008',  1901067,   6792087 ]
        ]);
      
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById('visualization')).
            draw(data,
                 {title:"Yearly Report",
                  width:500, height:400,
                  hAxis: {title: "Year"}}
            );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
<?php } ?>
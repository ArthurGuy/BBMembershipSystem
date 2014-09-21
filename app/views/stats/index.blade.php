<div class="page-header">
    <h1>Stats</h1>
</div>

<div class="row">
<div id="paymentMethods" style="height:400px" class="col-sm-4"></div>
</div>
<script>
google.load("visualization", "1", {packages:["corechart"]});


      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(paymentMethods);

        var options = {
          title: 'Payment Methods',
          pieHole: 0.4
        };

        var chart = new google.visualization.PieChart(document.getElementById('paymentMethods'));
        chart.draw(data, options);
      }
$(document).ready(function() {
});

</script>
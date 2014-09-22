<div class="page-header">
    <h1>Stats</h1>
</div>

<div class="row">
    <div id="paymentMethods" style="height:400px" class="col-sm-12 col-md-6 col-lg-4"></div>
    <div id="monthlyAmounts" style="height:400px" class="col-sm-12 col-md-6 col-lg-4"></div>
    <div class="col-sm-12 col-md-6 col-lg-4">
        <p class="text-center">
            <br />
            <br />
            Average Monthly Amount<br />
            <strong>&pound;{{ $averageMonthlyAmount }}</strong>
        </p>
    </div>
</div>
<script>
google.load("visualization", "1", {packages:["corechart"]});


    google.setOnLoadCallback(drawPaymentMethodsChart);
    function drawPaymentMethodsChart() {
        var data = google.visualization.arrayToDataTable(paymentMethods);

        var options = {
          title: 'Payment Methods',
          pieHole: 0.4
        };

        var chart = new google.visualization.PieChart(document.getElementById('paymentMethods'));
        chart.draw(data, options);
    }


    google.setOnLoadCallback(drawChart);
    function drawChart() {

        var data = google.visualization.arrayToDataTable(monthlyAmounts);

        var options = {
            title: 'Monthly Subscription Amounts',
            legend: { position: 'none' },
            vAxis: {format: '#', gridlines: {}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('monthlyAmounts'));
        chart.draw(data, options);

    }

</script>
<div class="page-header">
    <h1>Stats</h1>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-4">
        <h3 class="text-center">Payment Methods</h3>
        <div id="paymentMethods" style="height:400px"></div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">
        <h3 class="text-center">Monthly Subscription Amounts</h3>
        <div id="monthlyAmounts" style="height:400px"></div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4">

        <h3 class="text-center">Average Monthly Subscription</h3>
        <p class="text-center">
            <span class="key-figure">&pound;{{ $averageMonthlyAmount }}</span>
        </p>


        <h3 class="text-center">Active Members</h3>
        <p class="text-center">
            <span class="key-figure">{{ $numActiveUsers }}</span>
        </p>
    </div>
</div>
<script>
google.load("visualization", "1", {packages:["corechart"]});


    google.setOnLoadCallback(drawPaymentMethodsChart);
    function drawPaymentMethodsChart() {
        var data = google.visualization.arrayToDataTable(paymentMethods);

        var options = {
            //title: 'Payment Methods',
            pieHole: 0.4
        };

        var chart = new google.visualization.PieChart(document.getElementById('paymentMethods'));
        chart.draw(data, options);
    }


    google.setOnLoadCallback(drawChart);
    function drawChart() {

        var data = google.visualization.arrayToDataTable(monthlyAmounts);

        var options = {
            //title: 'Monthly Subscription Amounts',
            legend: { position: 'none' },
            vAxis: {format: '#', gridlines: {}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('monthlyAmounts'));
        chart.draw(data, options);

    }

</script>
@extends('layouts.main')

@section('meta-title')
Stats
@stop

@section('page-title')
Stats
@stop

@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="well">
            <h3 class="text-center">Payment Methods</h3>
            <div id="paymentMethods" style="height:400px"></div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="well">
            <h3 class="text-center">Monthly Subscription Amounts</h3>
            <div id="monthlyAmounts" style="height:400px"></div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="well">

            <h3 class="text-center">Average Monthly Subscription</h3>
            <p class="text-center">
                <span class="key-figure">&pound;{{ $averageMonthlyAmount }}</span>
            </p>


            <h3 class="text-center">Paying Members</h3>
            <p class="text-center">
                <span class="key-figure">{{ $numMembers }}</span>
            </p>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="well">
            <h3 class="text-center">Members Visiting the Maker Space</h3>

            <h4 class="text-center">Last 30 days</h4>
            <p class="text-center">
                <span class="key-figure">{{ $numActiveUsers }}</span>
            </p>

            <h4 class="text-center">Last 90 days</h4>
            <p class="text-center">
                <span class="key-figure">{{ $numActiveUsersQuarter }}</span>
            </p>
        </div>
    </div>
</div>
<script>

    BB.chartData = BB.chartData || {};
    BB.chartData.paymentMethods = {!! json_encode($paymentMethods) !!};
    BB.chartData.monthlyAmounts = {!! json_encode($monthlyAmountsData) !!};

    google.load("visualization", "1", {packages:["corechart"]});


    google.setOnLoadCallback(drawPaymentMethodsChart);
    function drawPaymentMethodsChart() {
        var data = google.visualization.arrayToDataTable(BB.chartData.paymentMethods);

        var options = {
            //title: 'Payment Methods',
            pieHole: 0.4
        };

        var chart = new google.visualization.PieChart(document.getElementById('paymentMethods'));
        chart.draw(data, options);
    }


    google.setOnLoadCallback(drawChart);
    function drawChart() {

        var data = google.visualization.arrayToDataTable(BB.chartData.monthlyAmounts);

        var options = {
            //title: 'Monthly Subscription Amounts',
            legend: { position: 'none' },
            vAxis: {format: '#', gridlines: {}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('monthlyAmounts'));
        chart.draw(data, options);

    }

</script>

@stop
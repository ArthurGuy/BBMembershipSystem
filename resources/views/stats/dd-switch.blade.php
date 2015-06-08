@extends('layouts.main')

@section('meta-title')
Stats
@stop

@section('page-title')
GoCardless > Switch to variable DD
@stop

@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6 col-xl-4">
        <div class="well">
            <h3 class="text-center">GoCardless Switchover</h3>
            <div id="paymentMethods" style="height:400px"></div>
        </div>
    </div>
</div>
<script>

    BB.chartData = BB.chartData || {};
    BB.chartData.paymentMethods = {!! json_encode($paymentMethods) !!};

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

</script>

@stop
<div class="page-header">
    <h1>Stats</h1>
</div>

<canvas id="paymentMethods" width="400" height="400"></canvas>

<script>

var doughnutChartOptions = {
   //Boolean - Whether we should show a stroke on each segment
   segmentShowStroke : true,

   //String - The colour of each segment stroke
   segmentStrokeColor : "#000",

   //Number - The width of each segment stroke
   segmentStrokeWidth : 2,

   //Number - The percentage of the chart that we cut out of the middle
   percentageInnerCutout : 50, // This is 0 for Pie charts

   //Number - Amount of animation steps
   animationSteps : 100,

   //String - Animation easing effect
   animationEasing : "easeOutBounce",

   //Boolean - Whether we animate the rotation of the Doughnut
   animateRotate : true,

   //Boolean - Whether we animate scaling the Doughnut from the centre
   animateScale : false

};

$(document).ready(function() {
    // Get the context of the canvas element we want to select
    var paymentMethodsCtx = document.getElementById("paymentMethods").getContext("2d");
    var myDoughnutChart = new Chart(paymentMethodsCtx).Doughnut(paymentMethods, doughnutChartOptions);
});

</script>
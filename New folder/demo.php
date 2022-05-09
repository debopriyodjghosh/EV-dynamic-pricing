<!DOCTYPE html>
<html>
  <head>
    <title>ChartJS - LineGraph</title>
    <style>
      .chart-container {
        width: 640px;
        height: auto;
      }
    </style>
  </head>
  <body>
    <div class="chart-container">
      <canvas id="mycanvas"></canvas>
    </div>
    
    <!-- javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        $(document).ready(setInterval(function(){
        $.ajax({
            url : "data.php",
            type : "GET",
            success : function(data){
            console.log(data);
            const d=JSON.parse(data)
            console.log(d);

            var userid = [];
            var facebook_follower = [];
            for(var i in d) {
                userid.push(d[i].date+" "+d[i].time+":00:00");
                facebook_follower.push(d[i].price);
            }

            var chartdata = {
                labels: userid,
                datasets: [
                {
                    label: "facebook",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(59, 89, 152, 0.75)",
                    borderColor: "rgba(59, 89, 152, 1)",
                    pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
                    pointHoverBorderColor: "rgba(59, 89, 152, 1)",
                    data: facebook_follower
                },
                ]
            };

            var ctx = $("#mycanvas");

            var LineGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata
            });
            },
            error : function(data) {

            }
        });
        },2000));
    </script>
  </body>
</html>
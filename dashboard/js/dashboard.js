$(document).ready(function () {

   $('#dashboard-slider').slick({
      autoplay: true,
      autoplaySpeed: 2000,
      prevArrow: $(".slider-wrapper .prev"),
      nextArrow: $(".slider-wrapper .next"),
   });


   // monthwise bar graph
   // Set new default font family and font color to mimic Bootstrap's default styling
   Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
   Chart.defaults.global.defaultFontColor = '#292b2c';

   // Bar Chart Example
   var ctx = document.getElementById("myBarChart");
   var monthArray = [25, 64, 87, 54, 45, 102, 87, 98, 98, 75, 52, 55];
   var myLineChart = new Chart(ctx, {
      type: 'line',
      data: {
         labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
         datasets: [{
            label: "Boomcoins",
            backgroundColor: "#fff",
            borderColor: "#ea1821",
            data: monthArray,
         }],
      },
      options: {
         scales: {
            xAxes: [{
               time: {
                  unit: 'month'
               },
               gridLines: {
                  display: false
               },
               ticks: {
                  maxTicksLimit: 6
               }
            }],
            yAxes: [{
               ticks: {
                  min: 0,
                  maxTicksLimit: 5
               },
               gridLines: {
                  display: true
               }
            }],
         },
         legend: {
            display: false
         }
      }
   });

})
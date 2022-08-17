<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "calmecac";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM records_agosto";
$result = $conn->query($sql);

$myArray = array();
if ($result->num_rows > 0) {
// output data of each row
  while($row = $result->fetch_assoc()) {
    $myArray[] = $row;
  }
} 
else 
{
  echo "0 results";
}
$conn->close();


?>

<!-- HTML -->

<!DOCTYPE html>
<html lang="en">


<!-- Resources -->
<link rel="stylesheet" href="style.css">
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<body>
  <h1> <img src="logo.jpg" id="imgprinc"></h1>
  <h2>Promedio Push Press</h2>
<div class="conten">
 <div class="split left">
  <div >
    <img src="pushpress.gif" alt="Avatar woman" class="imgleft">
  </div>
</div>

<div class="split right">
  <div  id="chartdiv">
  </div>
</div> 
</div>



<!-- Chart code -->
<script>
  var query_result =  <?php echo json_encode($myArray); ?>;
  console.log(query_result);
  var total_bench_press = 0;
  console.log(query_result[1].bench_press);

  query_result.forEach((member) => {
    console.log(member.bench_press);
    member.bench_press=Number(member.bench_press);
    member.push_press=Number(member.push_press);
    member.back_squat=Number(member.back_squat);
    total_bench_press+=member.bench_press;
  });
  var push_press_array = [];
  var bench_press_array = [];
  var back_squat_array = [];
  for(var i in query_result){
    push_press_array.push(query_result[i].push_press);
    bench_press_array.push(query_result[i].bench_press);
    back_squat_array.push(query_result[i].back_squat);

  }

  var min_push_press=Math.min.apply(null, push_press_array);
  var min_bench_press=Math.min.apply(null, bench_press_array);
  var min_back_squat=Math.min.apply(null, back_squat_array);

  var max_push_press=Math.max.apply(null, push_press_array);
  var max_bench_press=Math.max.apply(null, bench_press_array);
  var max_back_squat=Math.max.apply(null, back_squat_array);

  var avg_push_press= push_press_array.reduce((a,b) => a + b, 0) / push_press_array.length;
  var avg_bench_press=push_press_array.reduce((a,b) => a + b, 0) / bench_press_array.length;
  var avg_back_squat= push_press_array.reduce((a,b) => a + b, 0) / back_squat_array.length;



  console.log(avg_push_press);
  console.log(Math.min.apply(null, bench_press_array));
  console.log(back_squat_array);


  console.log(total_bench_press);
//console.log(query_result[1].start);
/*
for (let i = 0; i < query_result.length; i++) {
  query_result[i].start=new Date(query_result[i].start*1000);
}
*/

am5.ready(function() {



// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
  ]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root.container.children.push(
  am5radar.RadarChart.new(root, {
    panX: false,
    panY: false,
    startAngle: 180,
    endAngle: 360,

  })
  );



chart.getNumberFormatter().set("numberFormat", "#");

// Create axis and its renderer
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
var axisRenderer = am5radar.AxisRendererCircular.new(root, {
  innerRadius: -40
});

axisRenderer.grid.template.setAll({
  stroke: root.interfaceColors.get("background"),
  visible: true,
  strokeOpacity: 0.8
});

//Valores iniciales
var xAxis = chart.xAxes.push(
  am5xy.ValueAxis.new(root, {
    maxDeviation: 0,
    min: min_push_press,
    max: max_push_press,
    strictMinMax: true,
    renderer: axisRenderer
  })
  );

// Add clock hand
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
var axisDataItem = xAxis.makeDataItem({});


//Circulo del centro
var clockHand = am5radar.ClockHand.new(root, {
  pinRadius: 50,
  radius: am5.percent(100),
  innerRadius: 50,
  bottomWidth: 0,
  topWidth: 0
});

clockHand.pin.setAll({
  fillOpacity: 0,
  strokeOpacity: 0.5,
  stroke: am5.color(0x000000),
  strokeWidth: 1,
  strokeDasharray: [2, 2]
});
clockHand.hand.setAll({
  fillOpacity: 0,
  strokeOpacity: 0.5,
  stroke: am5.color(0x000000),
  strokeWidth: 0.5
});

var bullet = axisDataItem.set(
  "bullet",
  am5xy.AxisBullet.new(root, {
    sprite: clockHand
  })
  );

xAxis.createAxisRange(axisDataItem);


//texto del circulo del centro
var label = chart.radarContainer.children.push(
  am5.Label.new(root, {
    centerX: am5.percent(50),
    textAlign: "center",
    centerY: am5.percent(50),
    fontSize: "1.5em"
  })
  );


axisDataItem.set("value", min_push_press);
bullet.get("sprite").on("rotation", function () {
  var value = axisDataItem.get("value");
  label.set("text", Math.round(value).toString());
});

setInterval(function () {
  var value = Math.round(Math.random() * 100);

  axisDataItem.animate({
    key: "value",
    to: avg_push_press,
    duration: 500,
    easing: am5.ease.out(am5.ease.cubic)
  });

  axisRange0.animate({
    key: "endValue",
    to: avg_push_press,
    duration: 500,
    easing: am5.ease.out(am5.ease.cubic)
  });

  axisRange1.animate({
    key: "value",
    to: avg_push_press,
    duration: 500,
    easing: am5.ease.out(am5.ease.cubic)
  });
});

chart.bulletsContainer.set("mask", undefined);

var colorSet = am5.ColorSet.new(root, {});
//valor inicial y final de la izquierda
var axisRange0 = xAxis.createAxisRange(
  xAxis.makeDataItem({
    above: true,
    value: min_push_press,
    endValue: min_push_press
  })
  );

axisRange0.get("axisFill").setAll({
  visible: true,
  fill: "black"
});

axisRange0.get("label").setAll({
  forceHidden: true
});

//valor inicial y final de la derecha
var axisRange1 = xAxis.createAxisRange(
  xAxis.makeDataItem({
    above: true,
    value: min_push_press,
    endValue: max_push_press
  })
  );

//Color de la derecha
axisRange1.get("axisFill").setAll({
  visible: true,
  fill: colorSet.getIndex(10)
});

axisRange1.get("label").setAll({
  forceHidden: true
});

// Make stuff animate on load
chart.appear(1000, 100);


}); // end am5.ready()

</script>

</body>
</html>
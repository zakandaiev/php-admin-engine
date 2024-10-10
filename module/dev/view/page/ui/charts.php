<?php Theme::template('ui-header', ['title' => 'Charts']); ?>

<div class="mt-2">
  <div class="row cols-xs-1 cols-md-2 gap-xs">

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Line chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"line","data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"datasets":[{"label":"Sales ($)","fill":true,"backgroundColor":"transparent","borderColor":"#3B7DDD","data":[2115,1562,1584,1892,1487,2223,2966,2448,2905,3838,2917,3327]},{"label":"Orders","fill":true,"backgroundColor":"transparent","borderColor":"#adb5bd","borderDash":[4,4],"data":[958,724,629,883,915,1214,1476,1212,1554,2128,1466,1827]}]},"options":{"maintainAspectRatio":false,"legend":{"display":false},"tooltips":{"intersect":false},"hover":{"intersect":true},"plugins":{"filler":{"propagate":false}},"scales":{"xAxes":[{"reverse":true,"gridLines":{"color":"rgba(0,0,0,0.05)"}}],"yAxes":[{"ticks":{"stepSize":500},"display":true,"borderDash":[5,5],"gridLines":{"color":"rgba(0,0,0,0)","fontColor":"#fff"}}]}}}
          </canvas>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Bar chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"bar","data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"datasets":[{"label":"Last year","backgroundColor":"#3B7DDD","borderColor":"#3B7DDD","hoverBackgroundColor":"#3B7DDD","hoverBorderColor":"#3B7DDD","data":[54,67,41,55,62,45,55,73,60,76,48,79],"barPercentage":0.75,"categoryPercentage":0.5},{"label":"This year","backgroundColor":"#dee2e6","borderColor":"#dee2e6","hoverBackgroundColor":"#dee2e6","hoverBorderColor":"#dee2e6","data":[69,66,24,48,52,51,44,53,62,79,51,68],"barPercentage":0.75,"categoryPercentage":0.5}]},"options":{"maintainAspectRatio":false,"legend":{"display":false},"scales":{"yAxes":[{"gridLines":{"display":false},"stacked":false,"ticks":{"stepSize":20}}],"xAxes":[{"stacked":false,"gridLines":{"color":"transparent"}}]}}}
          </canvas>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Doughnut chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"doughnut","data":{"labels":["Social","Search Engines","Direct","Other"],"datasets":[{"data":[260,125,54,146],"backgroundColor":["#3b7ddd","#1cbb8c","#fcb92c","#dee2e6"],"borderColor":"transparent"}]},"options":{"maintainAspectRatio":false,"cutoutPercentage":65,"legend":{"display":false}}}
          </canvas>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Pie chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"pie","data":{"labels":["Social","Search Engines","Direct","Other"],"datasets":[{"data":[260,125,54,146],"backgroundColor":["#3b7ddd","#1cbb8c","#fcb92c","#dee2e6"],"borderColor":"transparent"}]},"options":{"maintainAspectRatio":false,"legend":{"display":false}}}
          </canvas>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Radar chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"radar","data":{"labels":["Speed","Reliability","Comfort","Safety","Efficiency"],"datasets":[{"label":"Model X","backgroundColor":"rgba(0, 123, 255, 0.2)","borderColor":"#3b7ddd","pointBackgroundColor":"#3b7ddd","pointBorderColor":"#fff","pointHoverBackgroundColor":"#fff","pointHoverBorderColor":"#3b7ddd","data":[70,53,82,60,33]},{"label":"Model S","backgroundColor":"rgba(220, 53, 69, 0.2)","borderColor":"#dc3545","pointBackgroundColor":"#dc3545","pointBorderColor":"#fff","pointHoverBackgroundColor":"#fff","pointHoverBorderColor":"#dc3545","data":[35,38,65,85,84]}]},"options":{"maintainAspectRatio":false}}
          </canvas>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Polar area chart</h4>
        </div>
        <div class="box__body">
          <canvas class="chart">
            {"type":"polarArea","data":{"labels":["Speed","Reliability","Comfort","Safety","Efficiency"],"datasets":[{"label":"Model S","data":[35,38,65,70,24],"backgroundColor":["#3b7ddd","#1cbb8c","#dc3545","#fcb92c","#148a9c"]}]},"options":{"maintainAspectRatio":false}}
          </canvas>
        </div>
      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>

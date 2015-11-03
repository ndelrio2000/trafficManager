{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

{literal}
<script type="text/javascript">
      google.load("visualization", "1", {packages:["gauge"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Memoria', {/literal}{$memoryStatus}{literal}],
          ['CPU', {/literal}{$cpuStatus}{literal}],
        ]);

        var options = {
          width: 300, height: 120,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
	    backgroundColor: '#E4E4E4',
          minorTicks: 5,
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        setInterval(function() {
          //data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 13000);
        setInterval(function() {
          //data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 5000);
      }
    </script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Hosts', 'Estado Actual'],
          ['Primarios UP',     {/literal}{$hostStatusSummary.UP}{literal}],
          ['Primarios DOWN',      {/literal}{$hostStatusSummary.DOWN}{literal}],
          ['Failover UP',  {/literal}{$hostStatusSummary.FAILUP}{literal}],
          ['Failover DOWN', {/literal}{$hostStatusSummary.FAILDOWN}{literal}]
        ]);

        var options = {
	    backgroundColor: '#E4E4E4'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>


{/literal}



        <div class="col-sm-2 col-md-4">
            <div class="well">
                <h3>Estado del Sistema</h3>
		<div id="chart_div" style="width: 100%; height: 90%;"></div>
		<hr>	
		<h5>    Uptime: {$serverUptime}</h5>
            </div>
        </div>

        <div class="col-sm-2 col-md-5">
            <div class="well">
                <h3>Estado de los Servicios</h3>

		<table class="table table-bordered" id="ServiceStatus">
    	    		<tr class="header"> <th></th><th>Servicio</th> <th>Estado</th><tr>

			{if $primaryDnsStatus == true}
    	    		    <tr class="success"><td><img src="img/bind.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor de Nombres</td> <td class="small">OK</td></tr>
			{else}
    	    		    <tr class="danger"><td><img src="img/bind.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor de Nombres</td> <td class="small">DOWN</td></tr>
			{/if}


			{if $icingaStatus == true}
    	    		    <tr class="success"><td><img src="img/icinga.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor de monitoreo</td> <td class="small">OK</td></tr>
			{else}
    	    		    <tr class="danger"><td><img src="img/icinga.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor de monitoreo</td> <td class="small">DOWN</td></tr>
			{/if}
			{if $httpStatus == true}
    	    		    <tr class="success"><td><img src="img/apache.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor Web</td> <td class="small">OK</td></tr>
			{else}
    	    		    <tr class="danger"><td><img src="img/apache.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Servidor Web</td> <td class="small">DOWN</td></tr>
			{/if}
			{if $mysqlStatus == true}
    	    		    <tr class="success"><td><img src="img/mysql.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Base de Datos</td> <td class="small">OK</td></tr>
			{else}
    	    		    <tr class="danger"><td><img src="img/mysql.jpg" class="img-thumbnail" width="30" height="30"></td> <td class="small">Base de Datos</td> <td class="small">DOWN</td></tr>
			{/if}
		</table>


            </div>
        </div>

        <div class="col-sm-2 col-md-4">
            <div class="well">
                <h3>Estadistica de Hosts</h3>
		<div id="piechart" style="width: 100%; height: 90%;"></div>
            </div>
        </div>



        <div class="col-sm-2 col-md-5">
            <div class="well">
                    <h3>Estado de los Hosts</h3>
		<table class="table table-bordered" id="hostStatus">
    	    		<tr class="header"> <th>Host</th> <th>Ultimo Chequeo</th> <th>Proximo Chequeo</th> </tr>
                {foreach from=$hostStatus item=h}
		    {if $h.HOST_CURRENT_STATE == 0}		    
    	    		<tr class="success"> <td class="small">{$h.HOST_NAME}</td> <td class="small">{$h.HOST_LAST_CHECK}</td> <td class="small">{$h.HOST_NEXT_CHECK}</td> </tr>
		    {elseif $h.HOST_CURRENT_STATE == 1}
			<tr class="danger"> <td class="small">{$h.HOST_NAME}</td> <td class="small">{$h.HOST_LAST_CHECK}</td> <td class="small">{$h.HOST_NEXT_CHECK}</td> </td> </tr>
		    {else}
			<tr class="warning"> <td class="small">{$h.HOST_NAME}</td> <td class="small">{$h.HOST_LAST_CHECK}</td> <td class="small">{$h.HOST_NEXT_CHECK}</td> </td> </tr>
		    {/if}
		{/foreach}
		</table>
            </div>
        </div>
    </div>
</div>


{include file="footer.tpl"}

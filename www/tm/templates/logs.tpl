{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

{literal}

<div class="container">
<form method="post" class="form-inline" action="">
    <div class='col-md-3'>
    Desde:
        <div class="form-group">
            <div class='input-group date' id='f_desde'>
                <input type='text' class="form-control" name='f_desde' />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-md-3'>
    Hasta:
        <div class="form-group">
            <div class='input-group date' id='f_hasta'>
                <input type='text' class="form-control" name='f_hasta' />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-md-1'>
	<button class="btn btn-lg btn-primary btn-block" type="submit">Filtrar</button>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        $('#f_desde').datetimepicker({locale: 'es', format: 'YYYY-MM-DD HH:mm:ss'});
        $('#f_hasta').datetimepicker({locale: 'es', format: 'YYYY-MM-DD HH:mm:ss'});
        $("#f_desde").on("dp.change", function (e) {
            $('#f_hasta').data("DateTimePicker").minDate(e.date);
        });
        $("#f_hasta").on("dp.change", function (e) {
            $('#f_desde').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
{/literal}


        <div class="col-sm-8 col-md-10">
            <div class="well">
                    <h1>Logs</h1>
		<table class="table table-bordered table-hover" id="hostStatus">
    	    		<tr class="header"> <th>Host</th> <th>Fecha y Hora</th> <th>Datos</th> </tr>
                {foreach from=$logs item=l}
		    {assign var="logdata" value=";"|explode:$l.logentry_data}
		    {if $logdata[2] == 'HARD'}
			{if $l.logentry_type == 1024}		    
    	    		    <tr class="success"> <td class="small">{$l.name1}</td> <td class="small">{$l.logentry_time|date_format:"%d/%m/%y - %H:%M:%S"}</td> <td class="small">{$logdata[1]} - {$logdata[4]}</td> </tr>
			{elseif $l.logentry_type == 2048}
    	    		    <tr class="danger"> <td class="small">{$l.name1}</td> <td class="small">{$l.logentry_time|date_format:"%d/%m/%y - %H:%M:%S"}</td> <td class="small">{$logdata[1]} - {$logdata[4]}</td> </tr>
			{else}
    	    		    <tr class="warning"> <td class="small">{$l.name1}</td> <td class="small">{$l.logentry_time|date_format:"%d/%m/%y - %H:%M:%S"}</td> <td class="small">{$logdata[1]} - {$logdata[4]}</td> </tr>
			{/if}
		    {/if}
		{/foreach}
		</table>
            </div>
        </div>
    </div>
</div>


</div>

{include file="footer.tpl"}

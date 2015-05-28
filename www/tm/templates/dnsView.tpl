{include file="header.tpl" title="Traffic Manager"}

{include file="menu.tpl"}

    <div class="container">
        <div class="row">
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2>Administrar Zonas de DNS</h2>
			<h1>{if isset($message)}{$message}{/if}</h1>
                    </div>

		<table class="table table-bordered table-hover table-striped" id="hostStatus">
    	    		<thead"> <th>Nombre de la Zona</th> <th>DNS Primario</th> <th>DNS Secundario</th> <th>Acciones</th> </thead>
                {foreach from=$domains item=z}
    	    		<tr> <td>{$z.domainame}</td> <td>{$z.primaryserver}</td> <td>{$z.seccondaryserver}</td> <td><a href="/tm/modules/dns/index.php?action=delete&domainame={$z.domainame}"><img src="/tm/images/b_drop.png" class="img-responsive" alt="Eliminar">Eliminar</a></td> </tr>
		{/foreach}
		</table>


                </div>
            </section>
        </div>
    </div>




{include file="footer.tpl"}

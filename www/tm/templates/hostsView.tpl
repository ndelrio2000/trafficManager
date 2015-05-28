{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

    <div class="container">
        <div class="row">
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2>Administrar Hosts</h2>
			<h1>{if isset($message)}{$message}{/if}</h1>
                    </div>

		<table class="table table-bordered table-hover table-striped" id="hostStatus">
    	    		<thead"> <th>Host</th> <th>Dominio</th> <th>Servicio de Chequeo</th> <th>Acciones</th> </thead>
                {foreach from=$hosts item=h}
    	    		<tr> <td>{$h.name}</td> <td>{$h.domainame}</td> <td>{$h.displayName}</td> <td><a href="/tm/modules/host/index.php?action=edit&id={$h.id}"><img src="/tm/images/b_edit.png" class="img-responsive" alt="Editar">Editar</a><a href="/tm/modules/host/index.php?action=delete&id={$h.id}"><img src="/tm/images/b_drop.png" class="img-responsive" alt="Eliminar">Eliminar</a></td> </tr>
		{/foreach}
		</table>


                </div>
            </section>
        </div>
    </div>




{include file="footer.tpl"}

{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

    <div class="container">
        <div class="row">
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2>Administrar Usuarios</h2>
			<h1>{if isset($message)}{$message}{/if}</h1>
                    </div>

		<table class="table table-bordered table-hover table-striped" id="userStatus">
    	    		<thead"> <th>Nombre de usuario</th> <th>Descripcion</th> <th>Ultimo Login</th> <th>Acciones</th> </thead>
                {foreach from=$users item=u}
    	    		<tr> <td>{$u.username}</td> <td>{$u.description}</td> <td>{if {$u.lastlogin} == ""}Nunca{else}{$u.lastlogin}{/if}</td> <td><a href="/tm/modules/user/index.php?action=edit&username={$u.username}"><img src="/tm/images/b_edit.png" class="img-responsive" alt="Editar">Editar</a>{if $u.username ne $loggedUser}<a href="/tm/modules/user/index.php?action=delete&username={$u.username}"><img src="/tm/images/b_drop.png" class="img-responsive" alt="Eliminar">Eliminar</a>{/if}</td> </tr>
		{/foreach}
		</table>


                </div>
            </section>
        </div>
    </div>




{include file="footer.tpl"}

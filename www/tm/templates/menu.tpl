<div class="sidebar-nav">
    <div class="row">
        <div class="col-sm-2 col-md-2">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
<span class="text-primary">
<small>Usuario Logueado:<br> {$loggedUserDescription} ({$loggedUser})
<br>
Ultimo Login: <br>{$loggedUserLastLogin} horas<br>
</small></span>

                        </h6>                <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-folder-close">
                            </span>Inicio</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-th text-primary"></span><a href="/tm/modules/dashboard/index.php">Estado General</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-new-window text-success"></span><a href="/tm/modules/host/index.php">Agregar Equipo</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-edit text-danger"></span><a href="/tm/modules/host/index.php?action=view">Administrar Equipos</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-warning"></span><a href="/tm/modules/log/index.php">Logs</a>
                                        
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-folder-close">
                            </span>DNS</a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-new-window text-success"></span><a href="/tm/modules/dns/index.php">Agregar Zona de DNS</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-edit text-danger"></span><a href="/tm/modules/dns/index.php?action=view">Administrar Zonas de DNS</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-info-sign text-primary"></span><a href="/tm/modules/dns/index.php?action=seccondaries">Servidores DNS Secundarios</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>





                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-folder-close">
                            </span>Usuarios</a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-new-window text-success"></span><a href="/tm/modules/user/index.php">Agregar usuario al Sistema</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-edit text-danger"></span><a href="/tm/modules/user/index.php?action=view">Administrar usuarios</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list-alt text-warning"></span><a href="/tm/modules/log/index.php?type=audit">Auditoria</a>
                                        
                                    </td>
                        	</tr>

                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-log-out"></span><a href="/tm/index.php?action=logout">Salir</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>






            </div>
        </div>

{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

    <div class="container">
        <div class="row">
            <!-- form: -->
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2>{$title}</h2>
			<h1>{if isset($message)}{$message}{/if}</h1>
                    </div>

                    <form id="defaultForm" method="post" class="form-horizontal" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nombre</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="hostname" {if isset($hostname)}value="{$hostname}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Dominio</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="domainame" data-popover-position="right" data-popover-offset="0,15">
				    {if !isset($domainame)}
		            		<option value="">Seleccione uno...</option>
				    {/if}
            			    {foreach from=$zones item=z}
					{if isset($domainame) && $domainame == $z.domainame}
		            		    <option value="{$z.domainame}" selected>{$z.domainame}</option>
					{else}
					    <option value="{$z.domainame}">{$z.domainame}</option>
					{/if}
				    {/foreach}
		        	</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tipo de registro de DNS</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="type" data-popover-position="right" data-popover-offset="0,15">
					{if !(isset($type)) }
		            		    <option value="">Seleccione uno...</option>
					{/if}
					{if (isset($type)) && ($type == 'A') }
		            		    <option value="A" selected>A</option>
					{else}
		            		    <option value="A">A</option>
					{/if}
					{if isset($type) and $type == 'MX10'}
		            		    <option value="MX10" selected>MX prioridad 10</option>
					{else}
		            		    <option value="MX10">MX prioridad 10</option>
					{/if}
					{if isset($type) and $type == 'MX20'}
		            		    <option value="MX20" selected>MX prioridad 20</option>
					{else}
		            		    <option value="MX20">MX prioridad 20</option>
					{/if}
		        	</select>
                            </div>
			</div>
		    <div class="panel panel-default">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP Primaria para DNS</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="ipaddr" {if isset($ipaddr)}value="{$ipaddr}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP Primaria para monitoreo</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="ipmonitaddr" {if isset($ipmonitaddr)}value="{$ipmonitaddr}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Servicio para Monitoreo IP primaria</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="service" data-popover-position="right" data-popover-offset="0,15">
				    {if !isset($service)}
		            		<option value="">Seleccione uno...</option>
				    {/if}
            			    {foreach from=$services item=s}
					{if isset($service) && $service == $s.name}
		            		    <option value="{$s.name}" selected>{$s.displayName}</option>
					{else}
	    	            		    <option value="{$s.name}">{$s.displayName}</option>
					{/if}
				    {/foreach}
		        	</select>
                            </div>
			</div>
		    </div>
		    <div class="panel panel-default">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP de failover para DNS</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="failoveripaddr" {if isset($failoveripaddr)}value="{$failoveripaddr}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP de failover para monitoero</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="failoveripmonitaddr" {if isset($failoveripmonitaddr)}value="{$failoveripmonitaddr}"{/if} />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Servicio para Monitoreo IP de failover</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="servicefailover" data-popover-position="right" data-popover-offset="0,15">
				    {if !isset($servicefailover)}
		            		<option value="">Seleccione uno...</option>
				    {/if}
            			    {foreach from=$services item=s}
					{if isset($servicefailover) && $servicefaiover == $s.name}
		            		    <option value="{$s.name}" selected>{$s.displayName}</option>
					{else}
	    	            		    <option value="{$s.name}">{$s.displayName}</option>
					{/if}
				    {/foreach}
		        	</select>
                            </div>
		    </div>
			</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Intervalo entre chequeos</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="checkinterval" data-popover-position="right" data-popover-offset="0,15">
					{if isset($checkinterval) && $checkinterval == 30}
		            		    <option value="30" selected>30 segundos</option>
					{else}
		            		    <option value="30">30 segundos</option>
					{/if}
					{if isset($checkinterval) && $checkinterval == 60}
		            		    <option value="60" selected>60 segundos</option>
					{else}
		            		    <option value="60">60 segundos</option>
					{/if}
					{if isset($checkinterval) && $checkinterval == 120}
		            		    <option value="120" selected>120 segundos</option>
					{else}
		            		    <option value="120">120 segundos</option>
					{/if}
					{if isset($checkinterval) && $checkinterval == 300}
		            		    <option value="300" selected>300 segundos (5 minutos)</option>
					{else}
		            		    <option value="300">300 segundos (5 minutos)</option>
					{/if}
		        	</select>
                            </div>
			</div>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary">{if isset($action) && $action == 'edit'}Editar{else}Agregar{/if}</button>
                            </div>
                        </div>
			{if isset($id)}<input type=hidden name="id" value="{$id}">{/if}
                    </form>
                </div>
            </section>
            <!-- :form -->
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm').bootstrapValidator({
        message: 'Valor ingresado no valido',
        fields: {
            hostname: {
                message: 'El nombre de host ingresado no es valido',
                validators: {
                    notEmpty: {
                        message: 'El nombre de host no puede ser vacio'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'El nombre ebe contener entre 1 y 30 caracteres'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9\-\.]+$/,
                        message: 'El nombre solo puede contener caracteres alfanumericos, punto y el caracter -'
                    },
                }
            },
            domainame: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un dominio'
                    }
                }
            },
            type: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un tipo de registro DNS'
                    }
                }
            },
            ipaddr: {
                validators: {
                    notEmpty: {
                        message: 'La direccion IP no puede ser vacia'
                    },
                    regexp: {
                        regexp: /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-4]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/,
                        message: 'La direccion IP ingresada no es valida'
                    },
                }

            },
            ipmonitaddr: {
                validators: {
                    notEmpty: {
                        message: 'La direccion IP no puede ser vacia'
                    },
                    regexp: {
                        regexp: /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-4]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/,
                        message: 'La direccion IP ingresada no es valida'
                    },
                }

            },
            failoveripaddr: {
                validators: {
                    notEmpty: {
                        message: 'La direccion IP no puede ser vacia'
                    },
                    regexp: {
                        regexp: /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-4]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/,
                        message: 'La direccion IP ingresada no es valida'
                    },
                }

            },
            failoveripmonitaddr: {
                validators: {
                    notEmpty: {
                        message: 'La direccion IP no puede ser vacia'
                    },
                    regexp: {
                        regexp: /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-4]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/,
                        message: 'La direccion IP ingresada no es valida'
                    },
                }

            },
            service: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un servicio para el monitoreo'
                    }
                }
            },
            servicefailover: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un servicio para el monitoreo'
                    }
                }
            },
        }
    });
});
</script>



{include file="footer.tpl"}

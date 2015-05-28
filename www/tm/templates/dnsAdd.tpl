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
                            <label class="col-lg-3 control-label">Dominio completo de la Zona</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="zonename" {if isset($zonename)}value="{$zonename}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Minimo Cache (minimum):</label>
        		    <div class="col-lg-5">
				<select class="form-control" name="minimum" data-popover-position="right" data-popover-offset="0,15">
				    {if !isset($minimum)}
		            		<option value="">Seleccione uno...</option>
				    {/if}
					{if isset($minimum) && $minimum == 300}
		            		    <option value="300" selected>300 segundos (5 minutos)</option>
					{else}
		            		    <option value="300">300 segundos (5 minutos)</option>
					{/if}
					{if isset($minimum) && $minimum == 600}
		            		    <option value="600" selected>600 segundos (10 minutos)</option>
					{else}
		            		    <option value="600">600 segundos (10 minutos)</option>
					{/if}
					{if isset($minimum) && $minimum == 1800}
		            		    <option value="1800" selected>1800 segundos (30 minutos)</option>
					{else}
		            		    <option value="1800">1800 segundos (30 minutos)</option>
					{/if}
					{if isset($minimum) && $minimum == 3600}
		            		    <option value="3600" selected>3600 segundos (1 hora)</option>
					{else}
		            		    <option value="3600">3600 segundos (1 hora)</option>
					{/if}
					{if isset($minimum) && $minimum == 10800}
		            		    <option value="10800" selected>10800 segundos (3 horas)</option>
					{else}
		            		    <option value="10800">10800 segundos (3 horas)</option>
					{/if}
		        	</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP DNS Primario (dns1.dominio)</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="primaryDns" {if isset($primaryDns)}value="{$primaryDns}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IP DNS Secundario (dns.dominio)</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="seccondaryDns" {if isset($seccondaryDns)}value="{$seccondaryDns}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary">{if isset($action) && $action == 'edit'}Editar{else}Agregar{/if}</button>
                            </div>
                        </div>
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
            zonename: {
                message: 'El nombre de Zona ingresado no es valido',
                validators: {
                    notEmpty: {
                        message: 'El nombre de Zona no puede ser vacio'
                    },
                    stringLength: {
                        min: 1,
                        max: 100,
                        message: 'El nombre ebe contener entre 1 y 100 caracteres'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9\-\.]+$/,
                        message: 'El nombre solo puede contener caracteres alfanumericos, punto y el caracter -'
                    },
                }
            },
            minimum: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione un tiempo minimo para cache de la zona'
                    }
                }
            },
            primaryDns: {
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
            seccondaryDns: {
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
        }
    });
});
</script>



{include file="footer.tpl"}

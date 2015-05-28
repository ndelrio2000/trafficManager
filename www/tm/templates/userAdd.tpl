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
                            <label class="col-lg-3 control-label">Apellido y Nombre</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="description" {if isset($description)}value="{$description}"{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nombre de usuario</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="username" {if isset($username)}value="{$username}" readonly{/if} />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="password1"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Reingrese la Password</label>
                            <div class="col-lg-5">
                                <input type="password" class="form-control" name="password2"/>
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
            description: {
                message: 'Ingrese apellido y nombre del usuario',
                validators: {
                    notEmpty: {
                        message: 'Ingrese apellido y nombre del usuario'
                    },
                    stringLength: {
                        min: 5,
                        max: 100,
                        message: 'El nombre debe contener entre 1 y 100 caracteres'
                    },
                }
            },
            username: {
                message: 'Ingrese nombre de usuario',
                validators: {
                    notEmpty: {
                        message: 'Ingrese nombre de usuario'
                    },
                    stringLength: {
                        min: 4,
                        max: 15,
                        message: 'El nombre de usuario debe contener entre 4 y 15 caracteres'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9\.]+$/,
                        message: 'El nombre solo puede contener caracteres alfanumericos'
                    },
                }
            },
            password1: {
                validators: {
		    {if not isset($action)}
                    notEmpty: {
                        message: 'La password debe contener al menos 6 caracteres'
                    },
		    {/if}
                    stringLength: {
                        min: 6,
                        max: 15,
                        message: 'La password debe contener al menos 6 caracteres'
                    },
                }
            },
        }
    });
});
</script>



{include file="footer.tpl"}

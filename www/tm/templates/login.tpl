{include file="header.tpl" title="Traffic Manager Login"}

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
	    <h1 class="text-center login-title">{if isset($message)}{$message}{/if}</h1>
            <h1 class="text-center login-title">Debe Autenticarse para Ingresar</h1>
            <div class="account-wall">
                <img class="profile-img" src="images/tmlogo.jpg"
                    alt="">
                <form class="form-signin" action="">
                <input type="text" class="form-control" placeholder="usuario" required autofocus name=usuario>
                <input type="password" class="form-control" placeholder="contrase&ntilde;a" required name=password>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>

{include file="footer.tpl"}

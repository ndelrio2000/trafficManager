{include file="header.tpl" title="Traffic Manager Login"}

{include file="menu.tpl"}

    <div class="container">
        <div class="row">
            <!-- form: -->
            <section>
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="page-header">
                        <h2>Configuracion de servidores DNS secundarios</h2>
			    Para que la transferencia de zonas entre el servidor DNS Primario y Secundario sea operativa, es necesario realizar configuraciones manuales sobre el servidor DNS Secundario.
			    
			    <br><br>El servidor Traffic Manager no posee acceso directo para gestionar al servidor secundario, sino a través de transferencias de zna de DNS.
			    <br><br>La sintaxis que a continuación se describe permite realizar la configuración sobre un servidor ISC Bind.
			    <p></p>
                    </div> 
                   	    <h3>Configuraciones generales de autenticación</h3>

			    En el archivo de configuración global del Servidor DNS se debe definir la llave que será utilizada para realizar la transferencia de zonas hacia el servidor Primario del modo que se muestra en el siguiente cuadro:<br><br>
<pre class="screen">
key "trafficmanager" {
   algorithm HMAC-MD5;
   secret "{$dnsKey}";
};

server <b>_* Direccion IP del Servidor DNS Primario_</b> {
 keys { trafficmanager; };
};
</pre>
<small>*<b>Nota:</b> Deberá reemnplazarse por la dirección IP con la que es alcanzado el servidor DNS Primario por parte del Secundario.</small>


                   	    <h3>Configuraciones particulares para cada Zona</h3>

			    Para la definición de zonas sobre el DNS secundario, debe utilizarse la siguiente plantilla, modificando los datos particulares de cada una:<br><br>
<pre class="screen">
zone "<b>_*1 Nombre de la zona de DNS (FQDN)_</b>" in {
        type slave;
        notify yes;
        file "/etc/bind/local/<b>_*1 Nombre de la zona de DNS (FQDN)_.sec</b>";
        masters { <b>_*2 Direccion IP del Servidor DNS Primario_</b>; };
};
</pre>
<small><b>Notas:</b> *1 Deberá reemplazarse por el nombre completo del dominio (FQDN).    *2 Deberá reemnplazarse por la dirección IP con la que es alcanzado el servidor DNS Primario por parte del Secundario.</small>


<br><br>En todos los casos para que los cambios tomen efecto, deberá reiniciarse el servicio DNS en el servidor secundario.
        </div>
    </div>



{include file="footer.tpl"}

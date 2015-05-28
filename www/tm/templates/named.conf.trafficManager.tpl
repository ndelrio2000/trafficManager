#File Generated From TrafficManager - DO not Edit by Hand

{foreach from=$zones item=z}
{literal}    zone "{/literal}{$z.domainame}{literal}" {
        type master;
	notify yes;
        file "{/literal}{$bindZonesDir}{$z.domainame}{literal}.conf";
        allow-update { key {/literal}{$dnsKeyName}{literal}; };
        allow-transfer { key {/literal}{$dnsKeyName}{literal}; };
    };{/literal}
{/foreach}

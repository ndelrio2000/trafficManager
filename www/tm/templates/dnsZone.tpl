$ORIGIN .
$TTL {$dnsDefaultGlobalTtl}
{$zonename}          IN SOA  {$zonename}. root.{$zonename}. (
                                201501011  ; serial
                                28800      ; refresh (8 hours)
                                7200       ; retry (2 hours)
                                2419200    ; expire (4 weeks)
                                {$minimum}      ;
                                )
                        NS      dns1.{$zonename}.
                        NS      dns2.{$zonename}.
                        TXT     "Traffic Manager Managed Zone"
$ORIGIN {$zonename}.
dns1                    A       {$primaryDns}
dns2                    A       {$seccondaryDns}

#Host Defined From TrafficManager - DO not Edit by Hand
object Host "{$hostname}" {
  check_command = "{$check}"
  address = "{$ipaddr}"
  event_command = "{$eventhandler}"
  enable_event_handler = {$eventHandlerEnabled}
  check_interval = {$checkinterval}
  retry_interval = 1
  max_check_attempts = 2
  check_period = "24x7"
  vars.failoverdnsaddress = "{$failoverdnsaddress}"
  vars.dnsaddress = "{$dnsaddress}"
  vars.domain = "{$domain}"
  vars.isfailoverhost = {$isFailoverHost}
  vars.trafficManager = 1
}

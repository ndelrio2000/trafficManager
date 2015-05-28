#!/bin/bash

# Variables
KEYNAME="tm"
HASH="xq6tQuPF8b8NAVlUYtko4xclMN57eeJXHiiMnY4y67+NjUv0iXnGST0QIdCSlIlN9ClzPEKpbDHsoUkWPnMhJw=="
INTERFACE="eth0"
SERVER="localhost"
ZONE="ndelrio.com.ar"
HOST='pepe2.ndelrio.com.ar'

# Discovering IP
IP='10.16.1.1'

# Update command
NSUPDATE="/usr/bin/nsupdate -y $KEYNAME:$HASH"

# DNS data
EXEC="server $SERVER\nzone $ZONE\nupdate delete $HOST A\nupdate add $HOST 1440 A $IP\nshow\nsend\n"

# Updating DNS
echo -e $EXEC | $NSUPDATE
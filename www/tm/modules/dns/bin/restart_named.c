/***********************************************************
 * Script created by Ian L. of Jafty.com
 * Desc: restarts named DNS service
 ***********************************************************/

#include <stdio.h>
#include <stdlib.h>

int main() {
  if (!setuid(geteuid())) {
    system("/bin/echo '/usr/sbin/service bind9 restart > /dev/null 2>&1' | /usr/bin/at now");
  } else {
    printf("Couldn't set UID to effective UIDn");
    return 1;
  }
  return 0;
}

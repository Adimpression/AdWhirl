#!/bin/sh

if ps aux | grep -v grep | grep -v checkInvoker | grep -i adwhirl | grep -i Invoker  > /dev/null
then
  :
else
  INSTANCE=`curl -s http://169.254.169.254/2007-03-01/meta-data/instance-id`
  HOSTNAME=`curl -s http://169.254.169.254/2007-03-01/meta-data/public-hostname`

  DATE=`date`

  echo -e "AdWhirl Invoker was restarted at $DATE\n\nInstance: $INSTANCE\nHostname: $HOSTNAME\n\n\n" | mail -s "**PROBLEM - AdWhirl Invoker was restarted at $DATE" jpincar@google.com
  echo -e "AdWhirl Invoker was restarted at $DATE\n\nInstance: $INSTANCE\nHostname: $HOSTNAME\n\n\n" >> /root/adwhirl/current/restarts.txt

  cd /root/adwhirl/current/ && cp /root/adwhirl/current/Invoker.log /mnt/adwhirl/Invoker.log.old
  cd /root/adwhirl/current/ && cp /root/adwhirl/current/Invoker.err /mnt/adwhirl/Invoker.err.old
  cd /root/adwhirl/current/ && /root/adwhirl/current/adwhirl start_invoker
fi

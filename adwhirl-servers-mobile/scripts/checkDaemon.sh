#!/bin/sh

if ps aux | grep -v grep | grep java | grep Daemon  > /dev/null
then
    :
else
    echo "AdWhirl Daemon is down" | mail -s "**PROBLEM - AdWhirl Daemon is CRITICAL" jpincar@admob.com #page-adwhirl@admob-moat.pagerduty.com #page-adwhirl@yell.admob.com
fi

#!/usr/bin/python

# setup:
# add an new cronjob in crontab: 
# crontab -e
# * * * * * python /home/pi/rollo/rollo.py

from datetime import datetime
import urllib2
import json
import os.path
import sys

# rollo control functions
def shutRollo():
    urllib2.urlopen("http://localhost/api/rollo.php?down=#").read()

def openRollo():
    urllib2.urlopen("http://localhost/api/rollo.php?up=#").read()

# check if the timer is enabled
timerEnablePath = "/var/www/html/api/timer_config.txt";
if (os.path.exists(timerEnablePath)):
    timerEnableFile = open(timerEnablePath, "r")
    if (timerEnableFile.read() == "0"):
        sys.exit()
else:
    sys.exit()

# read timer config out of the config file
timerConfigPath = "/var/www/html/api/timings.txt"
if (os.path.exists(timerConfigPath)):
    timerConfigFile = open(timerConfigPath, "r")
    week = json.loads(timerConfigFile.read())
else:
    sys.exit()

# get open/shut times of the current weekday
now = datetime.now()
weekday = now.weekday()
openShut = week[weekday]
upTime = datetime.strptime(openShut[0], "%H:%M")
shutTime = datetime.strptime(openShut[1], "%H:%M")

# check if the open and the shut time equal 00:00, if so, the timer for today is disabled
if (openShut[0] == "00:00" and openShut[1] == "00:00"):
    sys.exit()

# check if we reached the open time
if (now.hour == upTime.hour and now.minute == upTime.minute):
    openRollo()
    print "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo opened!"

# check if we reached the shut time
if (now.hour == shutTime.hour and now.minute == shutTime.minute):
    shutRollo()
    print "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo closed!"

# write current call time to heartbeat file
heartbeatFile = open("./heartbeat.txt", "w")
heartbeatFile.write(now.strftime("%d.%m.%Y %H:%M:%S"))
heartbeatFile.close()
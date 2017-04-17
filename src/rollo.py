import time
from datetime import datetime
import urllib2

# timer configuration
# weekdays and their open and shut times
week = [
    #open:    shut:
    ["07:00", "20:30"], # MON
    ["07:00", "20:30"], # TUE
    ["07:00", "20:30"], # WED
    ["07:00", "20:30"], # THU
    ["07:00", "20:30"], # FRI
    ["09:00", "20:30"], # SAT
    ["09:00", "20:30"]  # SUN
]

# rollo control functions
def shutRollo():
    urllib2.urlopen("http://localhost/api/rollo.php?down=#").read()

def openRollo():
    urllib2.urlopen("http://localhost/api/rollo.php?up=#").read()

def stopRollo():
    urllib2.urlopen("http://localhost/api/rollo.php?stop=#").read()

opens = False
shuts = False

while 1:
    now = datetime.now()

    # get open/shut times of the current weekday
    weekday = now.weekday()
    openShut = week[weekday]
    upTime = datetime.strptime(openShut[0], "%H:%M")
    shutTime = datetime.strptime(openShut[1], "%H:%M")

    # if the rollo is not already open, check if we reached the open time
    if (opens == False and now.hour == upTime.hour and now.minute == upTime.minute):
        openRollo()
        opens = True
        shuts = False
        print "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo opened!"
    
    # if the rollo is not already shut, check if we reached the shut time
    if (shuts == False and now.hour == shutTime.hour and now.minute == shutTime.minute):
        shutRollo()
        shuts = True
        opens = False
        print "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo closed!"
    
    time.sleep(1)
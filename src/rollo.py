import time
from datetime import datetime
import RPi.GPIO as GPIO

# timer configuration
# weekdays and their open and shut times
week = [
    #open:    shut:
    ["07:00", "20:00"], # MON
    ["07:00", "20:00"], # TUE
    ["07:00", "20:00"], # WED
    ["07:00", "20:00"], # THU
    ["07:00", "20:00"], # FRI
    ["09:00", "20:00"], # SAT
    ["09:00", "20:00"]  # SUN
]

# pin configuration
upPin = 3
downPin = 5
stopPin = 7

# setup
def setup():
    GPIO.setmode(GPIO.BOARD)

    GPIO.setup(upPin, GPIO.OUT)
    GPIO.setup(downPin, GPIO.OUT)
    GPIO.setup(stopPin, GPIO.OUT)

def reset():
    GPIO.output(upPin, GPIO.HIGH)
    GPIO.output(downPin, GPIO.HIGH)
    GPIO.output(stopPin, GPIO.HIGH)

# rollo control functions
def shutRollo():
    global downPin
    #reset()
    GPIO.output(downPin, GPIO.LOW)

def openRollo():
    global upPin
    #reset()
    GPIO.output(upPin, GPIO.LOW)

def stopRollo():
    global stopPin
    #reset()
    GPIO.output(stopPin, GPIO.LOW)

opens = False
shuts = False

setup()
reset()

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

GPIO.cleanup()
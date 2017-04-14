import time
from datetime import datetime
import RPi.GPIO as GPIO
from sys import exit

# rollo control functions
def shutRollo():
    global downPin
    GPIO.output(downPin, GPIO.LOW)

def openRollo():
    global upPin
    GPIO.output(upPin, GPIO.LOW)

def stopRollo():
    global stopPin
    GPIO.output(stopPin, GPIO.LOW)


upPin = 3
downPin = 5
stopPin = 7

GPIO.setmode(GPIO.BOARD)

GPIO.setup(upPin, GPIO.OUT)
GPIO.setup(downPin, GPIO.OUT)
GPIO.setup(stopPin, GPIO.OUT)

GPIO.output(upPin, GPIO.HIGH)
GPIO.output(downPin, GPIO.HIGH)
GPIO.output(stopPin, GPIO.HIGH)

upTime = datetime.strptime("09:00", "%H:%M")
shutTime = datetime.strptime("20:00", "%H:%M")
opens = False
shuts = False

while 1:
    now = datetime.now()

    if (opens == False and now.hour == upTime.hour and now.minute == upTime.minute):
        openRollo()
        opens = True
        shuts = False
        print "[" + now + "] Rollo opened!"
    
    if (shuts == False and now.hour == shutTime.hour and now.minute == shutTime.minute):
        shutRollo()
        shuts = True
        opens = False
        print "[" + now + "] Rollo closed!"
    
    time.sleep(1)

GPIO.cleanup()
import time
from datetime import datetime
import urllib2

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

# rollo control functions
def shutRollo():
    urllib2.urlopen("http://localhost/rollo.php?down=#").read()

def openRollo():
    urllib2.urlopen("http://localhost/rollo.php?up=#").read()

def stopRollo():
    urllib2.urlopen("http://localhost/rollo.php?stop=#").read()

# email
def sendEmail(recipient, subject, body):
    import smtplib

    gmail_user = "b.bosse1991@gmail.com"
    gmail_pwd = "t!ct@cto3"
    FROM = "Rollo-Control"
    TO = recipient
    SUBJECT = subject
    TEXT = body

    # prepare actual message
    message = """From: %s\nTo: %s\nSubject: %s\n\n%s
    """ % (FROM, ", ".join(TO), SUBJECT, TEXT)
    try:
        server = smtplib.SMTP("smtp.gmail.com", 587)
        server.ehlo()
        server.starttls()
        server.login(gmail_user, gmail_pwd)
        server.sendmail(FROM, TO, message)
        server.close()
        print "successfully sent the mail"
    except:
        print "failed to send mail"

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

        msg = "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo opened!"
        print msg
        sendEmail("b.bosse1991@gmail.com", "Rollo opened!", "")
    
    # if the rollo is not already shut, check if we reached the shut time
    if (shuts == False and now.hour == shutTime.hour and now.minute == shutTime.minute):
        shutRollo()
        shuts = True
        opens = False

        msg = "[" + now.strftime("%d.%m.%Y %H:%M:%S") + "] Rollo closed!"
        print msg
        sendEmail("b.bosse1991@gmail.com", "Rollo shut!", "")
    
    time.sleep(1)
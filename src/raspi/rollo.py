#!/usr/bin/python

import subprocess
import time

while 1:
    subprocess.call(["/usr/bin/php", "/home/pi/rollo/rollo.php"])
    time.sleep(1)

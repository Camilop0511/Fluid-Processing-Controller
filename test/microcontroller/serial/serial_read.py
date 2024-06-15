#!/usr/bin/env python
import time
import serial 

ser = serial.Serial(
	port='/dev/ttyS0',
	baudrate = 57600,
	parity=serial.PARITY_NONE,
	stopbits=serial.STOPBITS_ONE,
        bytesize=serial.EIGHTBITS,
        timeout=1
)
while True:
    data = ser.read(1)
    if data:
        print(data)

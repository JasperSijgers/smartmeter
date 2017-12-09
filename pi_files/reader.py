import sys
import serial

#Set up serial
ser = serial.Serial()
ser.baudrate=115200
ser.bytesize=serial.SEVENBITS
ser.parity=serial.PARITY_EVEN
ser.stopbits=serial.STOPBITS_ONE
ser.xonxoff=0
ser.rtscts=0
ser.timeout=20
ser.port="/dev/ttyUSB0"

#Open COM port
try:
    ser.open()
except:
    sys.exit("Could not open %s. Program exiting..." % ser.name)

counter = 0
raw_line = ''
stack = []

while counter < 27:
    try:
        raw_line = ser.readline()
    except:
        sys.exit("Could not read port %s. Program exiting..." % ser.name)
    stack.append(str(raw_line).strip())
    counter = counter +1

counter = 0

while counter < 27:
    print(stack[counter])
    counter = counter +1

try:
    ser.close()
except:
    sys.exit("ERROR %s" % ser.name)

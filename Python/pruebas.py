from rflib import *
import time
import re
import bitstring
print("Listening for then signals in ASK")
d=RfCat()
d.setFreq(434000000)
d.setMdmModulation(MOD_ASK_OOK)
d.setMdmDRate(1/0.0005)
d.setMaxPower()
# d.lowball()

capture = ""

while (1):
    g = raw_input("Cpature signal ? y/n ")
    if g=="n":
        break
    try:
        y,z=d.RFrecv()
        capture=y.encode('hex')
        # capture=capture[::-1]
        print capture
    except ChipconUsbTimeoutException:
        pass

sizemessage=len(capture)
print sizemessage
tam=2
paquetes=sizemessage/tam
g=""
while (1):
    g = raw_input("Send signal ? y/n ")
    if g=="n":
        break
    for x in range(paquetes):
        formated=""
        payload=capture[tam*x: tam*x+tam]
        print "Current being formated to binary:" + payload
        binary =bin(int(payload,16))[2:]
        print binary
        print "Converting binary to bytes:  "
        formated=bitstring.BitArray(bin=(binary)).tobytes()
        print "Print Byte "+formated
        d.RFxmit(formated)

print "Transmision Complete"

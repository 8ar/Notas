from rflib import *
import time
import re
import bitstring
print("Listening for then signals in ASK")
d=RfCat()
d.setFreq(434000000)
# d.setMdmModulation(MOD_ASK_OOK)
d.setMdmModulation(MOD_2FSK)
d.setMdmDRate(19200)
# d.setMaxPower()
d.lowball()


while 1:

    capture = raw_input("Write message: ")
    print capture
    sizemessage=len(capture)
    print sizemessage
    tam=2
    paquetes=sizemessage/tam


    for x in range(paquetes):
        formated=""
        payload=capture[tam*x: tam*x+tam]
        print "Current being formated to binary:" + payload
        binary =bin(int(payload,16))[2:]
        print binary
        print "Converting binary to bytes:  "
        formated=bitstring.BitArray(bin=(binary)).tobytes()
        print "Sending Bytes with padding"
        # d.RFxmit(formated)

    print "Transmision Complete"

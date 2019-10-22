from rflib import *
import time
import re
import bitstring
print("Listening for then signals in ASK")
d=RfCat()
d.setFreq(434000000)
d.setMdmModulation(MOD_ASK_OOK)
# d.makePktFLEN(4)
# d.setMdmModulation(MOD_2FSK)
d.setMdmDRate(4800)
d.setMdmSyncMode(0)
d.setMaxPower()
d.lowball()

capture = ""

while (1):

    try:
        y,z=d.RFrecv()
        capture=y.encode('hex')
        print capture
    except ChipconUsbTimeoutException:
        pass
    if capture:
        break

#Parse Hex from capture by reducing 0#
payloads=re.split('0000*',capture)
print "CurreNT:"
print payloads

#-----------------Start Parse and Create Payload-------------#
for payload in payloads:
    formated=""
    if(len(payload)>6 and ((len(payload) % 2) == 0)):
        print "Current being formated to binary:" + payload
        binary =bin(int(payload,16))[2:]
        print binary
        print "Converting binary to bytes:  "
        formated=bitstring.BitArray(bin=(binary)).tobytes()
    else:
        continue

    time.sleep(2)
    print "Sending Bytes with padding"
    d.RFxmit((formated+"\x00\x00\x00\x00\x00\x00"))
    print "Transmision Complete"

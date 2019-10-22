from rflib import *
import time
import re
import bitstring
print("Listening for then signals in ASK")
d=RfCat()
# d.setFreq(434000000)
d.setFreq(434000000)
d.setMdmModulation(MOD_ASK_OOK)
# d.setMdmModulation(MOD_2FSK)
d.setMdmDRate(19200)
# d.setMaxPower()
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
# payloads=re.split('0000*',capture)
# print "Payloads"
# print payloads
# print "Sleep 5 seconds"
# time.sleep(5)
# print "Wake up"

sizemessage=len(capture)
print sizemessage

paquetes=sizemessage/4


for x in range(paquetes):
    formated=""
    payload=capture[4*x: 4*x+4]
    print "Current being formated to binary:" + payload
    binary =bin(int(payload,16))[2:]
    print binary
    print "Converting binary to bytes:  "
    formated=bitstring.BitArray(bin=(binary)).tobytes()
    print "Sending Bytes with padding"
    print formated
    # d.RFxmit(formated)
    print "Transmision Complete"



# #-----------------Start Parse and Create Payload-------------#
# for payload in payloads:
#     formated=""
#     message=""
#     if(((len(payload) % 2) == 0)):
#         print "Current being formated to binary:" + payload
#         binary =bin(int(payload,16))[2:]
#         print binary
#         print "Converting binary to bytes:  "
#         formated=bitstring.BitArray(bin=(binary)).tobytes()
#         # print formated
#     else:
#         continue

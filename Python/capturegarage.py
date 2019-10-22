#!/usr/bin/python

# **************************
# Importacion de todas las librerias que se van a usar para el programa
# **************************
import rflib
import os
import bitstring
from scipy.io import wavfile
from matplotlib import pyplot as plt
import numpy as np
import csv


fieldnames = ['archivo', 'prefijo', 'mensaje','sufijo']
path_room='./Garage/'
path_csv=path_room+'datoscortinas.csv'
doc_wav='C_G_P2.wav'
path_dir=path_room+'Copias/'
# path_wav=path_dir+doc_wav


# with open(path_csv, 'w') as outcsv:
#     writer = csv.DictWriter(outcsv, fieldnames = fieldnames)
#     writer.writeheader()
# outcsv.close()
#
# for doc_wav in os.listdir(path_dir):
path_wav=path_dir+doc_wav
print path_wav
# **************************
# Leer el archivo wav obtenido de Gqrx y modificado en Audacity para que sea mas sencillo su empaquetamiento
# **************************

fs, data = wavfile.read(path_wav)
# print fs
# print data
# **************************
# Crear dos arreglos para poder graficar la senal que se esta obteniendo
# **************************
x=[]
y=[]
# **************************
# Este ciclo se encarga de eliminar los numeros negativos de la senal
# **************************
for j in range(len(data)):
    amplitude=data[j][0]
    x.append(j)
    if amplitude<0:
        y.append(0)
    else:
        y.append(amplitude)


# **************************
# Fin del ciclo
# **************************

# **************************
# Despues de obtener los datos de la senal se obtiene la media para poder determinar
# cuales son 0 y 1 en la senal
# **************************
m=np.mean(y)
# **************************
# En Este ciclo se determina cuales son 0 y cuales son 1 a partir de la media de la senal
# **************************

for j in range(len(data)):
    amplitude=y[j]
    if amplitude>m:
        y[j]=1
    else:
        y[j]=0
# Amplitudes en un tiempo 1 y 2
a1=0
a2=0
# Flancos de subida y bajada
fls=False
flb=False
# tiempo detectado subida o bajada
xs=0
xb=0
# arreglo que guarda la diferencia entre flancos
tmin=[]

# **************************
# for para encontrar el tiempo que hay en la distancia entre los flancos de subida y bajada
# **************************
for j in range(len(data)-1):

    if j>0:
        a2=y[j]
        if a2>a1:
            fls=True
            # print "flanco de subida"
            xs=x[j]
        elif a1>a2:
            # print "flanco de bajada"
            flb=True
            xb=x[j]
        if fls and flb:
            dx=abs(xb-xs)

            tmin.append(dx)
            fls=False
            flb=False
            if a2>a1:
                fls=True
            elif a1>a2:
                flb=True
#guardar el valor del dato
        a1=a2
    else:
        a1=y[j]

# **************************
# ****   Fin del ciclo *****
# **************************
# Se calcula cual es el tiempo minimo entre flancos para poder estimar cuanto tiemmpo dura un 1 y 0 en la senal
fc=1.1
spacemin=int(min(tmin)*fc)
# print "El dx mas chico es " + str(spacemin)
# print str(max(tmin))
# **************************
# ****En este ciclo se va a concatenar los 0 y 1 dependiendo del ultimo flanco de subida o de bajada,*****
# ****si el ultimo flanco fue de bajada se agregaran 1s, si el flanco fue de subida se agregaran 0s y
# ****este numero de digitos que se agreguen dependera del
# ****el tiempo calculado entre flancos entre el tiempo minimo en toda la senal
# **************************


# Calculo de string de 0 y 1 que se envia a la senal
mess=""
# Amplitudes en un tiempo 1 y 2
a1=0
a2=0
# Flancos de subida y bajada
fls=False
flb=False
# tiempo detectado subida o bajada
xs=0
xb=0

for j in range(len(data)):
    if j>0:
        a2=y[j]
        if a2>a1:
            fls=True
            # print "flanco de subida"
            xs=x[j]
        elif a1>a2:
            # print "flanco de bajada"
            flb=True
            xb=x[j]
        if fls and flb:
            dx=abs(xb-xs)
            fls=False
            flb=False
            s=(dx)/spacemin

            if a2>a1:
                fls=True
                mess=mess+("0"*int(s))
            elif a1>a2:
                flb=True
                mess=mess+("1"*int(s))
            # print mess
#guardar el valor del dato
        a1=a2
    else:
        a1=y[j]

# print mess
sub=""
codehex=""
for j in range(0,len(mess),4):
    sub=str(hex(int(mess[j:j+4],2)))
    codehex=codehex+sub.replace("0x","",1)
# print mess
print codehex



# **************************
# Enviar la informacion encontrada en la senal wav
# **************************

full_pwm = mess
# print('Sending full PWM key: {}'.format(full_pwm))
# Convert the data to hex
rf_data = bitstring.BitArray(bin=full_pwm).tobytes()
# Start up RfCat
d = rflib.RfCat()
# Set Modulation. We using On-Off Keying here
d.setMdmModulation(rflib.MOD_ASK_OOK)
# Configure the radio
d.makePktFLEN(len(rf_data)) # Set the RFData packet length
d.setMdmDRate(7800)         # Set the Baud Rate
d.setMdmSyncMode(0)         # Disable preamble
d.setFreq(315017000)        # Set the frequency 314994
d.setMaxPower()
# Send the data string a few times
d.RFxmit(rf_data, repeat=4)
d.setModeIDLE()
# **************************
# Fin del envio de informacion por radio frecuencia
# **************************

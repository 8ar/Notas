from scipy.io import wavfile
from matplotlib import pyplot as plt
import numpy as np
#Plotting to our canvas


fs, data = wavfile.read('./Cortinas/PruebaDeco.wav')
# print fs
# print data
x=[]
y=[]
# eliminar los numeros negativos
for j in range(len(data)):
    amplitude=data[j][0]
    x.append(j)
    if amplitude<0:
        y.append(0)
    else:
        y.append(amplitude)
# obtener la media de los datos
m=np.mean(y, axis=0)


for j in range(len(data)):
    amplitude=y[j]
    if amplitude>m:
        y[j]=1
    else:
        y[j]=0

a1=0
a2=0
# Flancos de subida y bajada

fls=False
flb=False
# tiempo detectado subida o bajada
xs=0
xb=0
tmin=[]
# for para encontrar el tiempo que hay en la distancia entre los flancos de subida y bajada
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


nume=min(tmin)
# Calculo de string de 0 y 1
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



# plt.plot(x,y)
# plt.show()

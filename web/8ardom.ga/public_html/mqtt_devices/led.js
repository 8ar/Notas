
/*
******************************
****** PROCESOS  *************
******************************
*/

function process_led_n(){
  for (var i = 0; i < 9; i++) {
    
    var id_button = '#input_led'.concat(i);
    var num_led='led'.concat(i);
    if ($(id_button).is(":checked")){
      console.log("Encendido");

      client.publish(num_led, 'on', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }else{
      console.log("Apagado");
      client.publish(num_led, 'off', (error) => {
        console.log(error || 'Mensaje enviado!!!')
      })
    }
  }

}




/*
******************************
****** CONEXION  *************
******************************
*/

// connect options
const options = {
      connectTimeout: 4000,

      // Authentication
      clientId: 'Prueba2',
      username: 'web_client',
      password: '121212',

      keepalive: 60,
      clean: true,
}

var connected = false;

// WebSocket connect url
const WebSocket_URL = 'wss://8ardom.ga:8094/mqtt'


const client = mqtt.connect(WebSocket_URL, options)


client.on('connect', (error) => {
    console.log(error || 'Mqtt conectado por WS! Exito!')

    client.subscribe('values', { qos: 0 }, (error) => {
      if (!error) {
        console.log('Suscripción exitosa!')
      }else{
        console.log('Suscripción fallida!')
      }
    })

    // publish(topic, payload, options/callback)
    client.publish('fabrica', 'esto es un verdadero éxito', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
})

client.on('message', (topic, message) => {
  console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString())
  process_msg(topic, message);
})

client.on('reconnect', (error) => {
    console.log('Error al reconectar', error)
})

client.on('error', (error) => {
    console.log('Error de conexión:', error)
})

//Funciones de ajax para interactuar con las bases de datos por el back end
function GetData() {

  var data = {
      // Extraer informaciòn de que aire condicionado es
          device:$( "#garage_1" ).find(":selected").val(),
     // Extraer del boton de encendido o apagado
          switch: $('input[id="switch_1"]').is(':checked'),
    };
    console.log(data);
    obtainmessage(data);


};


function obtainmessage(data) {

  let url='https://8ardom.ga/mqtt_devices/MySQL/obtainaircondiotionermessage.php';
  $.post(url, data, (response) => {
    console.log(response);
    SI_AirConditioner(data,response);

  });
};

//Funciones mqtt
function SI_AirConditioner(data,message){
  //Send Instructions Air Conditioner
  var usertopic=usersinfo[0].topic;
  var device_id=data.device;
  var subtopic="airc/"+device_id;
  var switch_topic=usertopic.replace("#",subtopic);
// const item = {
// "N_M":4,
// "order":[1,2,3,4],
// "values":[ 0xC3, 0xE2070005, 0x00040000,0x0000A0F5],
// "bits":[8,32,32,32]
// };
  var myJSON = message;
  console.log(switch_topic);
  console.log(myJSON);

  client.publish(switch_topic, myJSON, (error) => {
    console.log(error || 'Mensaje enviado!!!')
  })
};


/*
******************************
****** CONEXION  *************
******************************
*/
// connect options
var usersinfo = function () {
    let url='https://8ardom.ga/mqtt_devices/MySQL/switch-obtain-usertopic.php';
    var tmp = null;
    $.ajax({
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'html',
        'url': url,
        'data': { 'username': "", 'topic':"" },
        'success': function (data) {
            tmp = JSON.parse(data);
        }
    });
    return tmp;
}();
// connect options
const options = {
      connectTimeout: 4000,
      // Authentication
      clientId: 'Prueba2',
      username: usersinfo[0].username,
      password: usersinfo[0].password,
      keepalive: 60,
      clean: true,
}
var connected = false;
// WebSocket connect url
const WebSocket_URL = 'wss://8ardom.ga:8094/mqtt'
const client = mqtt.connect(WebSocket_URL, options)
client.on('connect', (error) => {
    console.log(error || 'Mqtt conectado por WS! Exito!')
    client.subscribe(usersinfo[0].topic, { qos: 0 }, (error) => {
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

//acciones de documento para que reaccione el front end
$(document).ready(function() {
  // location.assign("https://8ardom.ga/switch.php");
console.log('jquery is working!');
//Buscar los botones para poderlos imprimir en la pantalla
$(document).on('change', 'form[id="modulosswitch"]', (e) => {
e.preventDefault();
const element = $(this)[0].activeElement;
UpdateStatus(element);

});
});

$(document).ready(function() {
console.log('jquery is working!');
//Buscar los botones para poderlos imprimir en la pantalla
$(document).on('change', 'form[id="air_conditioner"]', (e) => {
e.preventDefault();
console.log("Hubo un cambio");
GetData();
});

});

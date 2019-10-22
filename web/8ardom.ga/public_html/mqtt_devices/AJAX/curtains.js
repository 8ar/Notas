//Funciones de ajax para interactuar con las bases de datos por el back end
function GetData() {
    var data = {
        // Extraer informaciòn de que aire condicionado es
            device:$( "#cortinas_1" ).find(":selected").val(),
       // Extraer del boton de subir o bajar
            up: $('input[id="subir"]').is(':checked'),
      //Bajar
            down: $('input[id="bajar"]').is(':checked'),
       //Se encarga de poder parar las cortinas si es necesario
            stop: $('input[id="detener_cortina"]').is(':checked'),
       //Toma el canal que se quiere cambiar
            channel:$("#canal_cort").find(":selected").val()
      };
    console.log(data);
    obtainmessage(data);
};

function change_switches(element){
     // Extraer del boton de subir o bajar
      const    up= $('input[id="subir"]')[0];
    //Bajar
      const    down= $('input[id="bajar"]')[0];
     //Se encarga de poder parar las cortinas si es necesario
      const    stop= $('input[id="parar"]')[0];

    if ((stop == element)){

      $('input[id="subir"]').prop('checked', false);
      $('input[id="parar"]').prop('checked', true);
      $('input[id="bajar"]').prop('checked', false);

    }else if ((up==element)){


      $('input[id="subir"]').prop('checked', true);
      $('input[id="parar"]').prop('checked', false);
      $('input[id="bajar"]').prop('checked', false);

    }else if ((down==element) ) {
      $('input[id="subir"]').prop('checked', false);
      $('input[id="parar"]').prop('checked', false);
      $('input[id="bajar"]').prop('checked', true);
    }

}


function obtainmessage(data) {

  let url='https://8ardom.ga/mqtt_devices/MySQL/message_curtains_rf.php';
  $.post(url, data, (response) => {
    console.log(response);
    SI_rfcurtain(data,response);

  });
};

//Funciones mqtt
function SI_rfcurtain(data,message){
  //Send Instructions Air Conditioner
  var usertopic=usersinfo[0].topic;
  var device_id=data.device;
  var subtopic="curtain_rf/"+device_id;
  var switch_topic=usertopic.replace("#",subtopic);



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
$(document).on('change', 'form[id="curtain_rf"]', (e) => {
e.preventDefault();
const element = $(this)[0].activeElement;
change_switches(element);
GetData();

});
});

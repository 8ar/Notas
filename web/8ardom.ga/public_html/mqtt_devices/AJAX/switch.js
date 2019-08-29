//Funciones de ajax para interactuar con las bases de datos por el back end
function UpdateStatus(element) {
  var status=0;
  if($(element).prop("checked")== true){
  status=1;
  }
  const postData = {
    status: status,
    id: $(element).attr('id')
  };
  let url='https://8ardom.ga/mqtt_devices/MySQL/switch-update-status.php';
  $.post(url, postData, (response) => {
    console.log(response);
  });
};
//Funciones mqtt
function SendInstructionSwitch(element){
  var usertopic=usersinfo[0].topic;
  var device_id=$(element).attr('device_id');
  var subtopic="switch/"+device_id;
  var switch_topic=usertopic.replace("#",subtopic);
  var num_led=$(element).attr('num_led');
item = {led:"",status:""};
item ["led"] = num_led;

  if ($(element).is(":checked")){
    console.log("Encendido");
    item ["status"] = 'on';
  }else{
    console.log("Apagado");
    item ["status"] = 'off';
  }
  // console.log(item);
  // jsonObj.push(item);
  // console.log(jsonObj);
  var myJSON = JSON.stringify(item);
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
$(document).on('change', 'form[id="modulosswitch"]', (e) => {
e.preventDefault();
const element = $(this)[0].activeElement;
SendInstructionSwitch(element);
});
});

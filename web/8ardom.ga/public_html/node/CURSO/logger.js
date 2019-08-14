
const EventEmitter = require('events');

var url ='http://mylogger.ga/log';
class Logger extends EventEmitter {

  log(message){

  console.log(message);
  // raise an event
  this.emit('messageLogged',{id:1,url:'http//'});
  }



}


// module.exports.log=log; // Como objeto
module.exports=Logger; //como funcion

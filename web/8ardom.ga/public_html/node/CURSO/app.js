// var logger = require('./logger');
// console.log('message');
// // console.log(module);

//  Path Module
// const path=require('path');
// var pathObj = path.parse(__filename);
// console.log(pathObj);

// OS module
// const os = require('os');
//
// var totalMemory = os.totalmem();
//
// var freeMemory = os.freemem();
//
// console.log('Total Memor: '+totalMemory)

// Events
const EventEmitter = require('events');
// register a listener
// emitter.on('messageLogged',function(arg){
//   console.log('Listener called',arg);
// });



const Logger = require('./logger');
const logger = new Logger();

logger.on('messageLogged',(arg)=>{
  console.log('Listener called',arg);
});

logger.log('message');

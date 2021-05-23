var amqp = require('amqplib/callback_api');
var fs = require('fs');
var path = require('path');

amqp.connect('amqp://rabbitmq', function(error0, connection) {
    if (error0) {
        console.log("Sistema di Log NON Funzionante " + error0.toString());
        return;
    }
    connection.createChannel(function(error1, channel) {
        if (error1) {
            console.log("Sistema di Log NON Funzionante " + error0.toString());
            return;
        }
        var queue = 'MSG_QUEUE';

        channel.assertQueue(queue, {
            durable: true
        });
        channel.prefetch(1);

        console.log("--ATTENDO MESSAGGI DALLA CODA %s--", queue);

        channel.consume(queue, function(msg) {

            console.log("--> Ricevuto %s ", msg.content.toString());


            //distingue se il messaggio è un errore o è un successo, si possono aggiungere anche altri casi
            var date = new Date();

            path_ = path.join(__dirname, '/share_folder')
            var header = msg.content.toString().substring(0,8);
            var time = date.toLocaleTimeString();
            var date_as_string = date.toLocaleDateString().split('/').reverse().join('_') + '.log';
            var temp_path;


            var log = time + ' - ' + msg.content.toString();

            if(header == '[ERRORE]'){
                temp_path = path.join(path_, '/ERROR.log')
                fs.appendFileSync(temp_path, log + '\n');
            }

            temp_path = path.join(path_, '/'+date_as_string);

            fs.appendFileSync(temp_path, log + '\n');
            channel.ack(msg);

        }, {

            noAck: false
        });
    });

    /*
    process.on('SIGTERM', ()=>{
        channel.close();
        connection.close();
        process.exit(0);
   });
*/


});

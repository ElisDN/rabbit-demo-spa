let WebSocket = require('ws');

let fs = require('fs');
let jwt = require('jsonwebtoken');
let dotenv = require('dotenv');
let amqp = require('amqplib/callback_api');

dotenv.load();

let server = new WebSocket.Server({ port: 8000 });
let jwtKey = fs.readFileSync(process.env.WS_JWT_PUBLIC_KEY);

server.on('connection', function (ws, request) {
  console.log('connected: %s', request.connection.remoteAddress);

  ws.on('message', function (message) {
    let data = JSON.parse(message);
    if (data.type && data.type === 'auth') {
      try {
        let token = jwt.verify(data.token, jwtKey, {algorithms: ['RS256']});
        console.log('user_id: %s', token.sub);
        ws.user_id = token.sub;
      } catch (err) {
        console.log(err);
      }
    }
  });
});

amqp.connect(process.env.WS_AMQP_URI, function(err, conn) {
  if (err) {
    console.log(err);
    return;
  }
  conn.createChannel(function(err, ch) {
    let queue = 'notifications';
    ch.consume(queue, function(message) {
      console.log('consumed: %s', message.content);
      let value = JSON.parse(message.content);
      server.clients.forEach(ws => {
        if (ws.user_id === value.user_id) {
          ws.send(message.content.toString());
        }
      })
    }, {noAck: true});
  });
});
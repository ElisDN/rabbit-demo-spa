let WebSocket = require('ws');

let fs = require('fs');
let jwt = require('jsonwebtoken');
let dotenv = require('dotenv');
let kafka = require('kafka-node');

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

let client = new kafka.KafkaClient({
  kafkaHost: process.env.WS_KAFKA_BROKER_LIST
});

let consumer = new kafka.Consumer(
  client,
  [
    {topic: 'notifications', partition: 0}
  ], {
    groupId: 'websocket'
  }
);

consumer.on('message', function (message) {
  console.log('consumed: %s', message.value);
  let value = JSON.parse(message.value);
  server.clients.forEach(ws => {
    if (ws.user_id === value.user_id) {
      ws.send(message.value);
    }
  })
});
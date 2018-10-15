let WebSocket = require('ws');

let server = new WebSocket.Server({ port: 8000 });

server.on('connection', function (ws, request) {
  console.log('connected: %s', request.connection.remoteAddress);

  ws.on('message', function (message) {
    console.log('received: %s', message);
  });
});
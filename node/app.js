const crypto = require('crypto'),
      fs = require("fs"),
      http = require("https");

var privateKey = fs.readFileSync('privatekey.pem').toString();
var certificate = fs.readFileSync('certificate.pem').toString();

var credentials = crypto.createCredentials({key: privateKey, cert: certificate});

var handler = function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
};

var app = http.createServer();
var io = require('socket.io').listen(app);

app.setSecure(credentials);
app.addListener("request", handler);
app.listen(1776);

io.sockets.on('connection', function (socket) {
  socket.emit('news', { hello: 'world' });
  socket.on('my other event', function (data) {
    console.log(data);
  });
});
const { Client } = require('whatsapp-web.js');
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const QRCode = require('qrcode');

const app = express();

// Initialize WhatsApp Client for Multi-Device
const client = new Client({
    puppeteer: { args: ['--no-sandbox'] }
});

client.on('qr', (qr) => {
    // Generate QR and display it in the console
    QRCode.toString(qr, { type: 'terminal' }, function (err, url) {
        if (err) {
            console.error('Error generating QR', err);
        } else {
            console.log(url);
        }
    });
    console.log("Generating QR...");
});

client.on('ready', () => {
    console.log('Client is ready!');
});

client.on('auth_failure', message => {
    console.log('Authentication failure:', message);
});

client.on('disconnected', reason => {
    console.log('Client disconnected:', reason);
});

// Initialize the client
client.initialize();

// Set up Express middleware
app.use(helmet());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors());
app.use(morgan('combined'));

// Example route
app.get('/', (req, res) => {
    res.send({ status: 'Server is running and WhatsApp client is initialized' });
});

// Listen on port 3001
app.listen(3001, () => {
    console.log('Listening on port 3001');
});

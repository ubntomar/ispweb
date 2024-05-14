const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const fs = require('fs');
const whatsappClient = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const { sendEmail, sendEmailPayment, sendEmailToCompany, backup, sendPdfFactura } = require('./src/mail');

const SESSION_FILE_PATH = './session.json';
let sessionData;

const app = express();
const ads = [{ title: 'Hello, world (again)!' }];

// Configurar WhatsApp
const client = new whatsappClient.Client({
  puppeteer: { args: ['--no-sandbox'] }
});

console.log('Cliente de WhatsApp Web inicializado:', client);

let cont=0;
client.on('qr', (qr) => {
  qrcode.generate(qr, { small: true });
  console.log("Generando QR"+(cont++));
});

client.on('ready', () => {
  console.log('Client is ready!');
});

if (fs.existsSync(SESSION_FILE_PATH)) {
  sessionData = require(SESSION_FILE_PATH);
  client.loadState(sessionData);
}

let isAuthenticated = false;


client.on('authenticated', (session) => {
  console.log('Evento autenticado disparado.');
  if (session) {
    console.log('Datos de la sesión:', session);
    fs.writeFile(SESSION_FILE_PATH, JSON.stringify(session, null, 2), (err) => {
      if (err) {
        console.error('Error al guardar la sesión:', err);
      } else {
        console.log('Sesión guardada exitosamente en', SESSION_FILE_PATH);
      }
    });
  } else {
    console.log('No hay datos de sesión disponibles.');
  }
});

client.on('auth_failure', message => {
  console.log('Fallo de autenticación:', message);
});


client.initialize();

// Configurar Express
app.use(helmet());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors());
app.use(morgan('combined'));

app.get('/', (req, res) => {
  res.send(ads);
});

app.post('/newuser', async (req, res) => {
  const data = req.body;
  let resultado = await sendEmail(data);
  if (resultado === true) {
    res.status(200).send({ "mailStatus": "success" });
  } else {
    res.status(200).send({ "mailStatus": "fail" });
  }
});

app.post('/mail', async (req, res) => {
  const data = req.body;
  let resultado = await sendEmailPayment(data);
  if (resultado === true) {
    res.status(200).send({ "mailStatus": "success" });
  } else {
    res.status(200).send({ "mailStatus": "fail" });
  }
});

app.post('/mailCompany', async (req, res) => {
  const data = req.body;
  let resultado = await sendEmailToCompany(data);
  if (resultado === true) {
    res.status(200).send({ "mailStatus": "success" });
  } else {
    res.status(200).send({ "mailStatus": "fail" });
  }
});

app.post('/pdfFactura', async (req, res) => {
  const data = req.body;
  let resultado = await sendPdfFactura(data);
  if (resultado === true) {
    res.status(200).send({ "mailStatus": "success" });
  } else {
    res.status(200).send({ "mailStatus": "fail" });
  }
});

app.get('/bk', async (req, res) => {
  let resultado = await backup();
  if (resultado === true) {
    let message = `success ${new Date()}`;
    res.status(200).send({ "mailStatus": message });
  } else {
    res.status(200).send({ "mailStatus": "fail" });
  }
});

app.get('/wapi', async (req, res) => {
  const { phone, message } = req.query;

  if (!phone || !message) {
    return res.status(400).send({ error: 'Falta el número de teléfono o el mensaje' });
  }

  const formattedPhone = `${phone}@c.us`;

  try {
    await client.isRegisteredUser(formattedPhone);
    await client.sendMessage(formattedPhone, message);
    res.status(200).send({ success: true });
  } catch (error) {
    res.status(500).send({ error: error.message });
  }
});

app.listen(3001, () => {
  console.log('listening on port 3001');
});
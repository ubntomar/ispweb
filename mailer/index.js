const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const fs = require('fs');

const { sendEmail, sendEmailPayment, sendEmailToCompany, backup, sendPdfFactura } = require('./src/mail');



const app = express();



// Configurar Express
app.use(helmet());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors());
app.use(morgan('combined'));

app.get('/', (req, res) => {
  res.send('Welcome to the mailer service');
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



app.listen(3001, () => {
  console.log('listening on port 3001');
});

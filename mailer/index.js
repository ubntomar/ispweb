const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const {sendEmail,sendEmailPayment,sendEmailToCompany} = require('./src/mail')
const app = express();
// defining an array to work as the database (temporary solution)
const ads = [{
  title: 'Hello, world (again)!'
}];
// adding Helmet to enhance your API's security 
app.use(helmet());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
  extended: true
}));
app.use(cors());
// adding morgan to log HTTP requests
app.use(morgan('combined'));

app.get('/', (req, res) => {
  res.send(ads);
});


app.post('/newuser', async (req, res) => {
  const data = req.body;
  let resultado=await sendEmail(data)
  if (resultado == true) {
    res.status(200).send({
      "mailStatus": "success"
    })
  } else {
    res.status(200).send({
      "mailStatus": "fail"
    });
  }
});
app.post('/mail', async (req, res) => {
  const data = req.body;
  let resultado=await sendEmailPayment(data)
  if (resultado == true) {
    res.status(200).send({
      "mailStatus": "success"
    })
  } else {
    res.status(200).send({
      "mailStatus": "fail"
    });
  }
});
app.post('/mailCompany', async (req, res) => {
  const data = req.body;
  let resultado=await sendEmailToCompany(data)
  if (resultado == true) {
    res.status(200).send({
      "mailStatus": "success"
    })
  } else {
    res.status(200).send({
      "mailStatus": "fail"
    });
  }
});
app.post('/bk', async (req, res) => {
  const data = req.body;
  let resultado=await backup(data)
  if (resultado == true) {
    res.status(200).send({
      "mailStatus": "success"
    })
  } else {
    res.status(200).send({
      "mailStatus": "fail"
    });
  }
});
app.listen(3001, () => {
  console.log('listening on port 3001');
});


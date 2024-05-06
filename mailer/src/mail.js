  async function sendEmail(data = "") {
      var response = "xxx"
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY) //first paste export....... in ~/.profile, run: . ~/.profile , check: printenv 
      console.log("la api ke" + process.env.SENDGRID_API_KEY)
      const msg = {
          to: data.email,
          from: 'ventas@agingeneiria.online',
          templateId: data.template,
          dynamicTemplateData: {
              subject: 'Bienvenido ',
              fullName: data.fullName,
              paymentDay: data.paymentDay,
              periodo: data.periodo,
              valorPlan: data.valorPlan,
              idClient: data.idClient
          }
      }
      console.log("Voy a empezar la promesa")
      await sgMail
          .send(msg)
          .then((res) => {
              response = true
          })
          .catch((error) => {
              response = false
          })
      console.log("ya terminé la promesa y devolvió el valor:" + response)
      return response

  }
  async function sendEmailPayment(data = "") {
      var response = "xxx"
      require('dotenv').config();
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY)
      const msg = {
          to: data.email,
          from: 'ventas@agingeneiria.online',
          templateId: data.template,
          dynamicTemplateData: {
              subject: 'Bienvenido ',
              fullName: data.fullName,
          }
      }
      console.log("Voy a empezar la promesa")
      await sgMail
          .send(msg)
          .then((res) => {
              response = true
          })
          .catch((error) => {
              response = false
          })
      console.log("ya terminé la promesa y devolvió el valor:" + response)
      return response

  }
  async function sendEmailToCompany(data = "") {
      var response = "xxx"
      require('dotenv').config();
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY)
      console.log("la api ke" + process.env.SENDGRID_API_KEY)
      const msg = {
          to: data.email,
          from: 'ventas@agingeneiria.online',
          templateId: data.template,
          dynamicTemplateData: {
              subject: 'Bienvenido ',
              fullName: data.fullName,
              paymentDay: data.paymentDay,
              periodo: data.periodo,
              valorPlan: data.valorPlan,
              idClient: data.idClient
          }
      }
      console.log("Voy a empezar la promesa")
      await sgMail
          .send(msg)
          .then((res) => {
              response = true
          })
          .catch((error) => {
              response = false
          })
      console.log("ya terminé la promesa y devolvió el valor:" + response)
      return response

  }

  async function backup() {
      console.log("sending email...")
      const sgMail = require('@sendgrid/mail');
      sgMail.setApiKey(process.env.SENDGRID_API_KEY);
      console.log("la api ke" + process.env.SENDGRID_API_KEY)
      const fs = require("fs");
      pathToAttachment = "/tmp/db_redesagi.tar.gz";
      attachment = fs.readFileSync(pathToAttachment).toString("base64");
      const msg = {
          to: 'omar.a.hernandez.d@gmail.com',
          from: 'ventas@agingeneiria.online',
          subject: 'Backup base de datos Ispexperts.com',
          html: '<strong>Adjunto  backup diario!</strong>',
          attachments: [{
              content: attachment,
              filename: "db_redesagi.tar.gz",
              type: "application/tar.gz",
              disposition: "attachment"
          }]
      }
      console.log("Voy a empezar la promesa")
      await sgMail
          .send(msg)
          .then((res) => {
              response = true
          })
          .catch((error) => {
              response = false
          })
      console.log("ya terminé la promesa y devolvió el valor:" + response)
      return response

  }
      
    async function sendPdfFactura(data = "") {
        console.log("sending email...")
        const sgMail = require('@sendgrid/mail');
        sgMail.setApiKey(process.env.SENDGRID_API_KEY);
        console.log("la api ke" + process.env.SENDGRID_API_KEY)
        const fs = require("fs");
        pathToAttachment = data.pathToAttachment;
        attachment = fs.readFileSync(pathToAttachment).toString("base64");
        const html = `
            <div>
                <h1>Aviso Importante</h1>
                <p>Estimado cliente:${data.cliente}</p>
                <p>Le informamos que adjunto recibirá la factura  correspondiente al servicio de internet del mes en curso.</p>
                <p>Agradecemos su puntualidad en el pago para evitar interrupciones en su servicio.</p>
                <p>Si tiene alguna duda o inquietud, no dude en ponerse en contacto con nuestro equipo de atención al cliente.</p>
                <p>¡Gracias por confiar en nuestros servicios!</p>
                <p>Atentamente,<br>Equipo de AG INGENIERIA WIST</p>
            </div>
        `;

      const msg = {
          to: data.email,
          from: 'ventas@agingeneiria.online',
          subject: 'Adjunto PDF factura de servicio de internet',
          html,
          attachments: [{
              content: attachment,
              filename: data.filename,
              type: "application/pdf",
              disposition: "attachment"
          }]
      }
      console.log("Voy a empezar la promesa")
      await sgMail
          .send(msg)
          .then((res) => {
              response = true
          })
          .catch((error) => {
              response = false
          })
      console.log("ya terminé la promesa y devolvió el valor:" + response)
      return response

  }  



     


  module.exports = {
      sendEmail,
      sendEmailPayment,
      sendEmailToCompany,
      backup,
      sendPdfFactura
  };
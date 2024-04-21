  async function sendEmail(data = "") {
      var response = "xxx"
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY) //first paste export....... in ~/.profile, run: . ~/.profile , check: printenv 
      console.log("la api ke" + process.env.SENDGRID_API_KEY)
      const msg = {
          to: data.email,
          from: 'ventas@agingenieria.tech',
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
          from: 'ventas@agingenieria.tech',
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
          from: 'ventas@agingenieria.tech',
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
          from: 'ventas@agingenieria.tech',
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

  module.exports = {
      sendEmail,
      sendEmailPayment,
      sendEmailToCompany,
      backup
  };
  async function sendEmail(data = "") {
      var response = "xxx"
      //require('dotenv').config();
      const path = require('path'); 
      require('dotenv').config({ path: path.join(__dirname, '.env') });
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY)
      console.log("la api ke"+process.env.SENDGRID_API_KEY)
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
      console.log("ya terminé la promesa y devolvió el valor:"+response )
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
      console.log("ya terminé la promesa y devolvió el valor:"+response )
      return response
      
  }


  module.exports = {
      sendEmail,
      sendEmailPayment
  };
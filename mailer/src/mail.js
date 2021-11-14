  async function sendEmail(data = "") {
      var response = "xxx"
      require('dotenv').config();
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY)
      const msg = {
          to: data.email,
          from: 'ventas@agingenieria.tech',
          templateId: data.template,
          dynamicTemplateData: {
              subject: 'Ya casi esta lista esta sopita',
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


  module.exports = {
      sendEmail
  };
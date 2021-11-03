  async function sendEmail(data = "") {
      var response = "xxx"
      require('dotenv').config();
      const sgMail = require('@sendgrid/mail')
      sgMail.setApiKey(process.env.SENDGRID_API_KEY)
      const msg = {
          to: 'omar.a.hernandez.d@gmail.com',
          from: 'ventas@agingenieria.tech',
          templateId: 'd-4bdc152f4ac04ddfbacd49948f570213',
          dynamicTemplateData: {
              subject: 'Ya casi esta lista esta sopita',
              name: data.name,
              city: 'Guamal-Meta',
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
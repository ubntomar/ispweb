const sgMail = require('@sendgrid/mail');
sgMail.setApiKey(process.env.SENDGRID_API_KEY);

const fs = require("fs");

pathToAttachment = "/tmp/bill/web-example.pdf";
attachment = fs.readFileSync(pathToAttachment).toString("base64");

const msg = {
    to: 'omar.a.hernandez.d@gmail.com',
    from: 'ventas@agingenieria.tech',
    subject: 'Factura generada',
    html: '<strong>and easy to do anywhere, even with Node.js</strong>',
    attachments: [
        {
          content: attachment,
          filename: "Factura_Noviembre.pdf",
          type: "application/pdf",
          disposition: "attachment"
        }
    ]
}

sgMail.send(msg).catch(err => {
  console.log(err);
});
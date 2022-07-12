const sgMail = require('@sendgrid/mail');
sgMail.setApiKey(process.env.SENDGRID_API_KEY);

const fs = require("fs");

pathToAttachment = "/tmp/redesagi_facturacion_bk.sql";
attachment = fs.readFileSync(pathToAttachment).toString("base64");

const msg = {
    to: 'ag.ingenieria.wist@gmail.com',
    from: 'ventas@agingenieria.tech',
    subject: 'Backup base de datos Ispexperts.com',
    html: '<strong>Adjunto backup</strong>',
    attachments: [
        {
          content: attachment,
          filename: "redesagi_facturacion_bk.sql",
          type: "application/sql",
          disposition: "attachment"
        }
    ]
}
 
sgMail.send(msg).catch(err => {
  console.log(err);
});
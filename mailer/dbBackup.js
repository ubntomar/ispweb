console.log("sending email...")
const sgMail = require('@sendgrid/mail');
sgMail.setApiKey(process.env.SENDGRID_API_KEY);
console.log("la api ke"+process.env.SENDGRID_API_KEY)
const fs = require("fs");

pathToAttachment = "/tmp/db_redesagi.tar.gz";
attachment = fs.readFileSync(pathToAttachment).toString("base64"); 

const msg = {
    to: 'omar.a.hernandez.d@gmail.com',
    from: 'ventas@agingenieria.tech',
    subject: 'Backup base de datos Ispexperts.com',
    html: '<strong>Adjunto  backup diario!</strong>',
    attachments: [
        {
          content: attachment,
          filename: "db_redesagi.tar.gz",
          type: "application/tar.gz",
          disposition: "attachment"
        }
    ]
}
 
sgMail.send(msg).catch(err => {
  console.log(err);
});
console.log("end sending email...")
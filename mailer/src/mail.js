

function sendEmail(data){
    console.log("recibido"+JSON.stringify(data))
    return { message: 'New email sent' }
}



module.exports = {
    sendEmail
  };
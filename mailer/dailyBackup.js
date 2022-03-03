fetch("localhost:3001/bk",{

    method:"POST",
    body: JSON.stringify({
            email:"omar.a.hernandez.d@gmail.com"
    }),
    headers:{
            "Content-type":"application/json"
    }
}).then(response=>response.json()).then(json=>console.log(json)).catch(e=>console.log(e))
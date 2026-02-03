async function login(){
    const res=await fetch("http://localhost:3000/login",{
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify({email:email.value,password:pass.value})
    });
    const d=await res.json();
    msg.innerText=d.success?"Login Success":"Invalid Login";
}

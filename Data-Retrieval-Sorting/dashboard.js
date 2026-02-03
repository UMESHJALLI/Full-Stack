async function load(){
    const sort=document.getElementById("sort").value;
    const res=await fetch(`http://localhost:3000/students?sort=${sort}`);
    const data=await res.json();
    document.getElementById("data").innerHTML=
      data.map(s=>`<tr><td>${s.name}</td><td>${s.department}</td></tr>`).join("");
}

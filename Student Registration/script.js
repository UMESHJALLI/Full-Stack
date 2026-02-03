function save() {
    fetch("http://localhost:3000/add", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            name: name.value,
            email: email.value,
            dob: dob.value,
            department: dept.value,
            phone: phone.value
        })
    });
    alert("Saved");
}

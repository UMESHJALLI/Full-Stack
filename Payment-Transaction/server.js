app.post("/pay",(req,res)=>{
    const amt=req.body.amount;
    db.beginTransaction(()=>{
        db.query("UPDATE accounts SET balance=balance-? WHERE name='User' AND balance>=?",
        [amt,amt],(e,r)=>{
            if(r.affectedRows==0) return db.rollback(()=>res.send("Failed"));
            db.query("UPDATE accounts SET balance=balance+? WHERE name='Merchant'",
            [amt],()=>db.commit(()=>res.send("Success")));
        });
    });
});

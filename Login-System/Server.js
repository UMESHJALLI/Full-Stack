app.post("/login",(req,res)=>{
    const q="SELECT * FROM users WHERE email=? AND password=?";
    db.query(q,[req.body.email,req.body.password],
    (e,r)=>res.json({success:r.length>0}));
});

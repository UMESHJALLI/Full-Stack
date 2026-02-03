app.get("/students",(req,res)=>{
    let q="SELECT * FROM students";
    if(req.query.sort) q+=` ORDER BY ${req.query.sort}`;
    db.query(q,(e,r)=>res.json(r));
});

app.get("/orders",(req,res)=>{
    const q=`
    SELECT c.name,p.name AS product,o.qty,p.price
    FROM orders o
    JOIN customers c ON o.cid=c.id
    JOIN products p ON o.pid=p.id
    `;
    db.query(q,(e,r)=>res.json(r));
});

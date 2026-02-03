const express = require("express");
const mysql = require("mysql2");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(express.json());

const db = mysql.createConnection({
    host:"localhost",
    user:"root",
    password:"",
    database:"student_db"
});

app.post("/add",(req,res)=>{
    const q="INSERT INTO students VALUES (NULL,?,?,?,?,?)";
    db.query(q,Object.values(req.body),()=>res.send("Inserted"));
});

app.listen(3000);

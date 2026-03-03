<!-- teacher-create-exam.html -->
<!DOCTYPE html>
<html>
<head>
<title>Teacher - Create Exam</title>
<style>
body{
  font-family: Segoe UI, sans-serif;
  background:#f4f6f8;
  margin:0;
}
header{
  background:#1f2937;
  color:white;
  padding:15px 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
header a{
  color:white;
  text-decoration:none;
  background:#374151;
  padding:8px 16px;
  border-radius:6px;
  font-size:14px;
}
.container{
  width:680px;
  margin:40px auto;
  background:white;
  padding:30px;
  border-radius:10px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
h2{
  margin-bottom:20px;
}
.section-title{
  margin-top:30px;
  font-weight:600;
}
input, textarea, select{
  width:100%;
  padding:12px;
  margin-top:10px;
  border:1px solid #d1d5db;
  border-radius:6px;
  box-sizing: border-box;
}
.question-box{
  background:#f9fafb;
  padding:20px;
  border-radius:8px;
  margin-top:20px;
  border:1px solid #e5e7eb;
}
.question-row{
  background:#ffffff;
  border:1px solid #e2e8f0;
  border-radius:8px;
  padding:15px;
  margin-bottom:15px;
  position: relative;
}
.remove-btn{
  position: absolute;
  top:10px;
  right:10px;
  background:#ef4444;
  color:white;
  border:none;
  border-radius:4px;
  width:30px;
  height:30px;
  cursor:pointer;
  font-weight:bold;
  display: flex;
  align-items: center;
  justify-content: center;
}
.remove-btn:hover{
  background:#dc2626;
}
button{
  padding:12px;
  width:100%;
  margin-top:10px;
  border:none;
  border-radius:6px;
  font-size:15px;
  cursor:pointer;
}
.add-btn{
  background:#2563eb;
  color:white;
  margin-top:20px;
}
.publish-btn{
  background:#16a34a;
  color:white;
  margin-top:30px;
}
.exam-id-box{
  display:none;
  margin-top:25px;
  padding:15px;
  background:#ecfdf5;
  border:1px solid #10b981;
  border-radius:6px;
  text-align:center;
  font-size:16px;
}
.exam-id-box span{
  font-weight:bold;
  color:#047857;
}
.note{
  font-size:13px;
  color:#6b7280;
  margin-top:8px;
}
.rank-section{
  margin-top: 40px;
  padding-top: 20px;
  border-top: 2px dashed #cbd5e1;
}
.rank-btn{
  background:#7c3aed;
  color:white;
  margin-top: 10px;
}
.rank-display{
  background: #f1f5f9;
  padding: 15px;
  border-radius: 8px;
  margin-top: 15px;
  white-space: pre-line;
  font-family: monospace;
}
.question-count{
  font-size:14px;
  color:#4b5563;
  margin-top:10px;
}
</style>
</head>
<body>

<header>
  <h2>📚 Teacher Panel - Create Exam</h2>
</header>

<div class="container">

  <!-- EXAM TITLE -->
  <label class="section-title">Exam Title</label>
  <input type="text" id="title" placeholder="e.g. HTML & CSS Test" required>

  <!-- QUESTIONS -->
  <div class="section-title">Add Questions (add one or multiple)</div>
  
  <div id="questionsContainer">
    <!-- First question row will be added here dynamically -->
  </div>

  <button class="add-btn" onclick="addQuestionRow()">+ Add Another Question</button>

  <!-- TIMER -->
  <div class="section-title">Exam Duration</div>
  <input type="number" id="duration" min="1" placeholder="Duration in minutes (e.g. 30)" required>
  <div class="note">
    Timer starts automatically when the student enters the exam.
  </div>

  <!-- PUBLISH -->
  <button class="publish-btn" onclick="publishExam()">Publish Exam</button>

  <!-- EXAM ID DISPLAY -->
  <div class="exam-id-box" id="examBox">
    ✅ Exam Published Successfully<br>
    Exam ID: <span id="examId"></span>
  </div>

  <!-- RANK SECTION (Admin view ranks) -->
  <div class="rank-section">
    <h3>📊 View Student Ranks</h3>
    <input type="text" id="rankExamId" placeholder="Enter Exam ID (e.g. EXAM1234)">
    <button class="rank-btn" onclick="showRanks()">Show Ranks</button>
    <div class="rank-display" id="rankDisplay">Ranks will appear here...</div>
  </div>
</div>

<script>
  // Simple teacher auth simulation
  if(!localStorage.getItem("teacherLoggedIn")){
    localStorage.setItem("teacherLoggedIn","true");
  }

  // Initialize with one question row
  let questionCount = 0;

  function addQuestionRow() {
    const container = document.getElementById("questionsContainer");
    const rowId = "qRow_" + Date.now() + "_" + Math.random().toString(36).substr(2, 5);
    
    const questionRow = document.createElement("div");
    questionRow.className = "question-row";
    questionRow.id = rowId;
    
    questionRow.innerHTML = `
      <button class="remove-btn" onclick="removeQuestionRow('${rowId}')">×</button>
      <textarea placeholder="Enter question text" class="qText" required></textarea>
      <input type="text" placeholder="Option A" class="optA" required>
      <input type="text" placeholder="Option B" class="optB" required>
      <input type="text" placeholder="Option C" class="optC" required>
      <input type="text" placeholder="Option D" class="optD" required>
      <select class="correctOpt" required>
        <option value="">Correct Option</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select>
    `;
    
    container.appendChild(questionRow);
    questionCount++;
  }

  function removeQuestionRow(rowId) {
    const row = document.getElementById(rowId);
    if(row) {
      row.remove();
      questionCount--;
    }
  }

  // Add first question row on page load
  window.onload = function(){
    addQuestionRow();
    
    if(!localStorage.getItem("teacherExams")){
      let demo = [{
        examId: "EXAM2412",
        title: "HTML & CSS Basics",
        duration: 5,
        questions: [
          { question: "What does HTML stand for?", options: ["Hyper Text Markup Language","Home Tool Markup Language","Hyperlinks and Text Markup Language","Hyper Tool Markup Language"], answer: "A" },
          { question: "Which CSS property changes text color?", options: ["font-color","text-color","color","background-color"], answer: "C" }
        ],
        status: "Active"
      }];
      localStorage.setItem("teacherExams", JSON.stringify(demo));
      let res = { "EXAM2412": [] };
      localStorage.setItem("examResults", JSON.stringify(res));
    }
  };

  function publishExam(){
    const title = document.getElementById("title").value;
    const duration = document.getElementById("duration").value;

    if(title==="" || duration===""){
      alert("Please enter exam title and duration");
      return;
    }

    // Collect all questions from rows
    const questionRows = document.querySelectorAll(".question-row");
    let questions = [];
    let isValid = true;

    questionRows.forEach((row, index) => {
      const qText = row.querySelector(".qText").value;
      const optA = row.querySelector(".optA").value;
      const optB = row.querySelector(".optB").value;
      const optC = row.querySelector(".optC").value;
      const optD = row.querySelector(".optD").value;
      const correctOpt = row.querySelector(".correctOpt").value;

      if(!qText || !optA || !optB || !optC || !optD || !correctOpt){
        alert(`Please fill all fields in question ${index + 1}`);
        isValid = false;
        return;
      }

      questions.push({
        question: qText,
        options: [optA, optB, optC, optD],
        answer: correctOpt  // 'A','B','C','D'
      });
    });

    if(!isValid || questions.length === 0){
      alert("Please add at least one complete question");
      return;
    }

    const examId = "EXAM" + Math.floor(1000 + Math.random()*9000);

    let exams = JSON.parse(localStorage.getItem("teacherExams")) || [];

    exams.push({
      examId,
      title,
      duration,
      questions: questions,
      status: "Active"
    });

    localStorage.setItem("teacherExams", JSON.stringify(exams));

    // Initialize results container
    let results = JSON.parse(localStorage.getItem("examResults")) || {};
    if(!results[examId]) results[examId] = [];
    localStorage.setItem("examResults", JSON.stringify(results));

    document.getElementById("examId").innerText = examId;
    document.getElementById("examBox").style.display = "block";

    // Clear form for next exam
    document.getElementById("title").value = "";
    document.getElementById("duration").value = "";
    
    // Clear questions container and add one empty row
    document.getElementById("questionsContainer").innerHTML = "";
    addQuestionRow();
    
    // Scroll to show the exam ID
    document.getElementById("examBox").scrollIntoView({ behavior: 'smooth' });
  }

  function showRanks(){
    const eid = document.getElementById("rankExamId").value.trim();
    if(!eid) { alert("Enter exam ID"); return; }

    let results = JSON.parse(localStorage.getItem("examResults")) || {};
    let exams = JSON.parse(localStorage.getItem("teacherExams")) || [];

    let exam = exams.find(ex => ex.examId === eid);
    if(!exam) { 
      alert("Exam ID not found"); 
      return; 
    }

    let rankList = results[eid] || [];
    if(rankList.length === 0){
      document.getElementById("rankDisplay").innerText = `No submissions yet for exam: ${exam.title} (${eid})`;
      return;
    }

    // Sort by score descending
    rankList.sort((a,b) => b.score - a.score);
    let displayText = `📊 Exam: ${exam.title} (${eid})\n`;
    displayText += `Total Submissions: ${rankList.length}\n`;
    displayText += `━━━━━━━━━━━━━━━━━━━━\n`;
    
    rankList.forEach((r, idx) => {
      const percentage = Math.round(r.score/r.total*100);
      displayText += `${idx+1}. ${r.studentName}\n`;
      displayText += `   Score: ${r.score}/${r.total} (${percentage}%)\n`;
    });
    
    document.getElementById("rankDisplay").innerText = displayText;
  }
</script>

</body>
</html>
<!-- student-exam.html -->
<!DOCTYPE html>
<html>
<head>
<title>Student - Take Exam</title>
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
  width:720px;
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
  margin-top:20px;
  font-weight:600;
}
input, select{
  width:100%;
  padding:12px;
  margin-top:8px;
  border:1px solid #d1d5db;
  border-radius:6px;
  box-sizing: border-box;
}
button{
  padding:12px 24px;
  border:none;
  border-radius:6px;
  font-size:15px;
  cursor:pointer;
}
.primary-btn{
  background:#2563eb;
  color:white;
  width:100%;
}
.success-btn{
  background:#16a34a;
  color:white;
}
.warning-btn{
  background:#d97706;
  color:white;
}
.next-btn{
  background:#2563eb;
  color:white;
}
.exam-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:#f1f5f9;
  padding:15px;
  border-radius:8px;
  margin-bottom:20px;
}
.timer{
  font-size:24px;
  font-weight:bold;
  color:#b91c1c;
}
.question-card{
  background:#f9fafb;
  border:1px solid #e5e7eb;
  border-radius:10px;
  padding:20px;
  margin-bottom:20px;
}
.options{
  margin-top:15px;
}
.option{
  display:flex;
  align-items:center;
  gap:10px;
  padding:8px 0;
}
.option input[type="radio"]{
  width:auto;
  margin:0;
}
.rank-container{
  display:flex;
  flex-direction:column;
  gap:20px;
  margin:20px 0;
}
.user-rank-box{
  background:#fef3c7;
  border-left:8px solid #b45309;
  border-radius:10px;
  padding:20px;
  margin-bottom:20px;
  box-shadow:0 4px 6px rgba(0,0,0,0.1);
}
.user-rank-box h3{
  color:#92400e;
  margin-bottom:10px;
  font-size:20px;
}
.user-rank-box .score{
  font-size:28px;
  font-weight:bold;
  color:#b45309;
}
.others-rank-box{
  background:#f1f5f9;
  border-left:8px solid #2563eb;
  border-radius:10px;
  padding:20px;
}
.others-rank-box h3{
  color:#1e40af;
  margin-bottom:15px;
  font-size:18px;
}
.rank-card{
  background:white;
  border-radius:8px;
  padding:12px 15px;
  margin-bottom:10px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  border:1px solid #e2e8f0;
}
.rank-card .rank{
  font-weight:bold;
  color:#2563eb;
  width:50px;
}
.rank-card .name{
  flex:2;
  font-weight:500;
}
.rank-card .score{
  font-weight:600;
  color:#047857;
}
.hidden{
  display:none;
}
.question-navigation{
  display:flex;
  gap:10px;
  margin-top:20px;
  justify-content: space-between;
}
.question-counter{
  background:#e2e8f0;
  padding:12px 20px;
  border-radius:6px;
  font-weight:600;
}
.question-palette{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  margin-bottom:20px;
  padding:15px;
  background:#f1f5f9;
  border-radius:8px;
}
.palette-item{
  width:40px;
  height:40px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#e2e8f0;
  border-radius:6px;
  cursor:pointer;
  font-weight:600;
}
.palette-item.answered{
  background:#16a34a;
  color:white;
}
.palette-item.current{
  border:3px solid #2563eb;
  font-weight:bold;
}
</style>
</head>
<body>

<header>
  <h2>🧑‍🎓 Student Exam Portal</h2>
</header>

<div class="container" id="entryContainer">
  <h2>Enter Exam Details</h2>
  <div class="section-title">Your Name</div>
  <input type="text" id="studentName" placeholder="Enter your full name" >

  <div class="section-title">Exam ID</div>
  <input type="text" id="examIdInput" placeholder="Enter exam ID" >

  <button class="primary-btn" onclick="startExam()" style="margin-top:30px;">▶ Start Exam</button>

  <div id="errorMsg" style="color:#b91c1c; margin-top:15px;"></div>
</div>

<!-- Exam Container (hidden initially) -->
<div class="container hidden" id="examContainer">
  <div class="exam-header">
    <h3 id="examTitleDisplay">Loading...</h3>
    <div class="timer" id="timerDisplay">00:00</div>
  </div>
  
  <!-- Question Palette -->
  <div class="question-palette" id="questionPalette"></div>
  
  <!-- Current Question Display -->
  <div id="questionsArea"></div>
  
  <!-- Navigation Buttons -->
  <div class="question-navigation">
    <button class="warning-btn" onclick="previousQuestion()" id="prevBtn" style="width:100px;">← Prev</button>
    <span class="question-counter" id="questionCounter">Question 1/X</span>
    <button class="next-btn" onclick="nextQuestion()" id="nextBtn" style="width:100px;">Next →</button>
  </div>
  
  <!-- Submit Button (hidden initially) -->
  <div style="display:flex; gap:10px; margin-top:20px;" id="submitButtonContainer">
    <button class="success-btn" onclick="submitExam()" style="flex:2;" id="submitBtn">✅ Submit Exam</button>
    <button class="warning-btn" onclick="cancelExam()" style="flex:1;">Cancel</button>
  </div>
</div>

<!-- Rank Container (hidden after exam) -->
<div class="container hidden" id="rankContainer">
  <h2>🏆 Rank Board</h2>
  <div id="rankExamInfo" style="margin-bottom:20px;"></div>
  
  <div class="rank-container" id="rankList">
    <!-- User's rank box will be here -->
    <!-- Others rank box will be here -->
  </div>
  
  <button class="primary-btn" onclick="backToEntry()" style="margin-top:20px;">← Back to Entry</button>
</div>

<script>
  let currentExam = null;
  let currentExamId = null;
  let studentName = "";
  let endTime = null;
  let timerInterval = null;
  let userAnswers = []; // store selected indices
  let currentQuestionIndex = 0;

  function startExam(){
    const name = document.getElementById("studentName").value.trim();
    const eid = document.getElementById("examIdInput").value.trim();

    if(!name || !eid){
      alert("Enter both name and exam ID");
      return;
    }

    // Retrieve exams from localStorage (created by teacher)
    let exams = JSON.parse(localStorage.getItem("teacherExams")) || [];
    let exam = exams.find(ex => ex.examId === eid);
    if(!exam){
      document.getElementById("errorMsg").innerText = "Invalid Exam ID";
      return;
    }

    studentName = name;
    currentExamId = eid;
    currentExam = exam;

    // Initialize userAnswers array (null for each question)
    userAnswers = new Array(exam.questions.length).fill(null);
    currentQuestionIndex = 0;

    // Set timer
    let durationMinutes = parseInt(exam.duration) || 5;
    endTime = Date.now() + durationMinutes * 60 * 1000;

    // Hide entry, show exam
    document.getElementById("entryContainer").classList.add("hidden");
    document.getElementById("examContainer").classList.remove("hidden");
    document.getElementById("rankContainer").classList.add("hidden");

    // Render exam
    renderQuestionPalette();
    renderCurrentQuestion();
    updateNavigationButtons();
    startTimer();
  }

  function renderQuestionPalette(){
    let palette = document.getElementById("questionPalette");
    palette.innerHTML = "";
    
    for(let i = 0; i < currentExam.questions.length; i++){
      let item = document.createElement("div");
      item.className = `palette-item ${userAnswers[i] !== null ? 'answered' : ''} ${i === currentQuestionIndex ? 'current' : ''}`;
      item.textContent = i + 1;
      item.onclick = () => jumpToQuestion(i);
      palette.appendChild(item);
    }
  }

  function jumpToQuestion(index){
    // Save current answer before jumping
    saveCurrentAnswer();
    currentQuestionIndex = index;
    renderCurrentQuestion();
    updateNavigationButtons();
    renderQuestionPalette();
  }

  function saveCurrentAnswer(){
    let radios = document.getElementsByName(`q${currentQuestionIndex}`);
    for(let r of radios){
      if(r.checked){
        userAnswers[currentQuestionIndex] = parseInt(r.value);
        break;
      }
    }
  }

  function renderCurrentQuestion(){
    let area = document.getElementById("questionsArea");
    area.innerHTML = "";
    
    let q = currentExam.questions[currentQuestionIndex];
    let idx = currentQuestionIndex;
    
    let qDiv = document.createElement("div");
    qDiv.className = "question-card";
    qDiv.innerHTML = `<strong>Q${idx+1}:</strong> ${q.question}`;

    let optsDiv = document.createElement("div");
    optsDiv.className = "options";

    q.options.forEach((opt, optIdx) => {
      let letter = String.fromCharCode(65 + optIdx); // A,B,C,D
      let optDiv = document.createElement("div");
      optDiv.className = "option";
      optDiv.innerHTML = `
        <input type="radio" name="q${idx}" value="${optIdx}" id="q${idx}opt${optIdx}" ${userAnswers[idx] === optIdx ? 'checked' : ''}>
        <label for="q${idx}opt${optIdx}">${letter}. ${opt}</label>
      `;
      optsDiv.appendChild(optDiv);
    });
    qDiv.appendChild(optsDiv);
    area.appendChild(qDiv);
    
    // Update question counter
    document.getElementById("questionCounter").textContent = 
      `Question ${currentQuestionIndex + 1}/${currentExam.questions.length}`;
  }

  function updateNavigationButtons(){
    let prevBtn = document.getElementById("prevBtn");
    let nextBtn = document.getElementById("nextBtn");
    let submitContainer = document.getElementById("submitButtonContainer");
    
    // Show/hide prev button
    if(currentQuestionIndex === 0){
      prevBtn.style.visibility = "hidden";
    } else {
      prevBtn.style.visibility = "visible";
    }
    
    // Show/hide next button and submit button
    if(currentQuestionIndex === currentExam.questions.length - 1){
      // Last question - show submit button, hide next
      nextBtn.style.display = "none";
      submitContainer.style.display = "flex";
    } else {
      // Not last question - show next button, hide submit
      nextBtn.style.display = "block";
      submitContainer.style.display = "none";
    }
  }

  function previousQuestion(){
    saveCurrentAnswer();
    if(currentQuestionIndex > 0){
      currentQuestionIndex--;
      renderCurrentQuestion();
      renderQuestionPalette();
      updateNavigationButtons();
    }
  }

  function nextQuestion(){
    saveCurrentAnswer();
    
    // Check if answer is selected for current question
    let radios = document.getElementsByName(`q${currentQuestionIndex}`);
    let selected = false;
    for(let r of radios){
      if(r.checked){
        selected = true;
        break;
      }
    }
    
    if(!selected){
      if(!confirm("You haven't selected an answer for this question. Continue anyway?")){
        return;
      }
    }
    
    if(currentQuestionIndex < currentExam.questions.length - 1){
      currentQuestionIndex++;
      renderCurrentQuestion();
      renderQuestionPalette();
      updateNavigationButtons();
    }
  }

  function startTimer(){
    if(timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => {
      let remaining = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
      let mins = Math.floor(remaining / 60);
      let secs = remaining % 60;
      document.getElementById("timerDisplay").innerText = 
        `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
      
      if(remaining <= 0){
        clearInterval(timerInterval);
        alert("⏰ Time's up! Submitting automatically.");
        submitExam();
      }
    }, 500);
  }

  function submitExam(){
    // Save last question answer
    saveCurrentAnswer();

    // Calculate score
    let correctCount = 0;
    currentExam.questions.forEach((q, idx) => {
      let selected = userAnswers[idx];
      if(selected !== null){
        // q.answer is 'A','B','C','D' => map to index 0,1,2,3
        let correctIndex = q.answer.charCodeAt(0) - 65; // 'A'->0, 'B'->1, etc.
        if(selected === correctIndex) correctCount++;
      }
    });

    // Save result
    let results = JSON.parse(localStorage.getItem("examResults")) || {};
    if(!results[currentExamId]) results[currentExamId] = [];
    results[currentExamId].push({
      studentName: studentName,
      score: correctCount,
      total: currentExam.questions.length,
      timestamp: Date.now()
    });
    localStorage.setItem("examResults", JSON.stringify(results));

    // Stop timer
    if(timerInterval) clearInterval(timerInterval);

    // Show rank board
    showRankBoard(currentExamId);
  }

  function showRankBoard(eid){
    let results = JSON.parse(localStorage.getItem("examResults")) || {};
    let exams = JSON.parse(localStorage.getItem("teacherExams")) || [];
    let exam = exams.find(ex => ex.examId === eid);

    let rankList = results[eid] || [];
    // Sort by score descending
    rankList.sort((a,b) => b.score - a.score);
    
    // Add rank numbers
    let rankedList = rankList.map((r, idx) => ({
      ...r,
      rank: idx + 1
    }));

    let infoDiv = document.getElementById("rankExamInfo");
    infoDiv.innerHTML = `<strong>${exam ? exam.title : 'Exam'}</strong> (ID: ${eid})`;

    let rankContainer = document.getElementById("rankList");
    rankContainer.innerHTML = "";
    
    if(rankedList.length === 0){
      rankContainer.innerHTML = "<p>No submissions yet.</p>";
    } else {
      // Find current user's result
      let userResult = rankedList.find(r => r.studentName === studentName);
      let otherResults = rankedList.filter(r => r.studentName !== studentName);
      
      // Create User's Rank Box (Top box)
      if(userResult){
        let userBox = document.createElement("div");
        userBox.className = "user-rank-box";
        userBox.innerHTML = `
          <h3>🎯 Your Result</h3>
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
              <p style="font-size:18px; margin:5px 0;">Rank: <strong>#${userResult.rank}</strong></p>
              <p style="font-size:16px; margin:5px 0;">${userResult.studentName}</p>
            </div>
            <div class="score">${userResult.score}/${userResult.total}</div>
          </div>
          <div style="margin-top:10px; color:#666;">
            Score: ${Math.round(userResult.score/userResult.total*100)}%
          </div>
        `;
        rankContainer.appendChild(userBox);
      }
      
      // Create Others Rank Box
      if(otherResults.length > 0){
        let othersBox = document.createElement("div");
        othersBox.className = "others-rank-box";
        othersBox.innerHTML = "<h3>📊 Other Students</h3>";
        
        otherResults.forEach(r => {
          let card = document.createElement("div");
          card.className = "rank-card";
          card.innerHTML = `
            <span class="rank">#${r.rank}</span>
            <span class="name">${r.studentName}</span>
            <span class="score">${r.score}/${r.total}</span>
          `;
          othersBox.appendChild(card);
        });
        
        rankContainer.appendChild(othersBox);
      }
    }

    // Switch views
    document.getElementById("entryContainer").classList.add("hidden");
    document.getElementById("examContainer").classList.add("hidden");
    document.getElementById("rankContainer").classList.remove("hidden");
  }

  function cancelExam(){
    if(confirm("Cancel exam? Your progress will be lost.")){
      if(timerInterval) clearInterval(timerInterval);
      backToEntry();
    }
  }

  function backToEntry(){
    document.getElementById("entryContainer").classList.remove("hidden");
    document.getElementById("examContainer").classList.add("hidden");
    document.getElementById("rankContainer").classList.add("hidden");
    // Clear any interval
    if(timerInterval) clearInterval(timerInterval);
    // Reset fields
    document.getElementById("studentName").value = "Alex";
    document.getElementById("examIdInput").value = "EXAM2412";
  }

  // Pre-fill demo message
  window.onload = function(){
    // ensure demo exam exists
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
    }
    if(!localStorage.getItem("examResults")){
      localStorage.setItem("examResults", JSON.stringify({}));
    }
    
    // Add some demo results for testing
    let results = JSON.parse(localStorage.getItem("examResults")) || {};
    if(!results["EXAM2412"] || results["EXAM2412"].length === 0){
      results["EXAM2412"] = [
        { studentName: "Alex", score: 2, total: 2, timestamp: Date.now() },
        { studentName: "Sarah", score: 1, total: 2, timestamp: Date.now() },
        { studentName: "Mike", score: 1, total: 2, timestamp: Date.now() },
        { studentName: "John", score: 0, total: 2, timestamp: Date.now() }
      ];
      localStorage.setItem("examResults", JSON.stringify(results));
    }
  };
</script>

</body>
</html>
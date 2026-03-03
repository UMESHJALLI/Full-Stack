<?php
include 'db_connect.php';

// Handle exam start request
if (isset($_POST['start_exam'])) {
    $examId = $conn->real_escape_string($_POST['exam_id']);
    $studentName = $conn->real_escape_string($_POST['student_name']);
    
    // Get exam details
    $sql = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $examId);
    $stmt->execute();
    $examResult = $stmt->get_result();
    
    if ($examResult->num_rows > 0) {
        $exam = $examResult->fetch_assoc();
        
        // Get questions
        $sql2 = "SELECT * FROM questions WHERE exam_id = ? ORDER BY question_order";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $examId);
        $stmt2->execute();
        $questionsResult = $stmt2->get_result();
        $questions = [];
        while ($q = $questionsResult->fetch_assoc()) {
            $questions[] = [
                'question' => $q['question_text'],
                'options' => [$q['option_a'], $q['option_b'], $q['option_c'], $q['option_d']],
                'answer' => $q['correct_answer']
            ];
        }
        
        // Store in session for exam taking
        $_SESSION['current_exam'] = [
            'exam_id' => $examId,
            'title' => $exam['title'],
            'duration' => $exam['duration'],
            'questions' => $questions,
            'student_name' => $studentName
        ];
        
        header("Location: student-exam.php?take_exam=1");
        exit();
    } else {
        $error = "Invalid Exam ID";
    }
}

// Handle exam submission
if (isset($_POST['submit_exam']) && isset($_SESSION['current_exam'])) {
    $exam = $_SESSION['current_exam'];
    $answers = $_POST['answers'] ?? [];
    
    // Calculate score
    $correctCount = 0;
    foreach ($exam['questions'] as $index => $q) {
        if (isset($answers[$index])) {
            $selectedIndex = (int)$answers[$index];
            $correctIndex = ord($q['answer']) - 65; // A->0, B->1, etc.
            if ($selectedIndex === $correctIndex) {
                $correctCount++;
            }
        }
    }
    
    // Save result to database
    $sql = "INSERT INTO results (exam_id, student_name, score, total) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", 
        $exam['exam_id'], 
        $exam['student_name'], 
        $correctCount, 
        count($exam['questions'])
    );
    $stmt->execute();
    
    // Store results for display
    $_SESSION['last_result'] = [
        'exam_id' => $exam['exam_id'],
        'student_name' => $exam['student_name']
    ];
    
    // Clear current exam
    unset($_SESSION['current_exam']);
    
    header("Location: student-exam.php?view_results=1");
    exit();
}

// Get results for display
if (isset($_GET['view_results']) && isset($_SESSION['last_result'])) {
    $resultExamId = $_SESSION['last_result']['exam_id'];
    $resultStudentName = $_SESSION['last_result']['student_name'];
    
    // Get exam details
    $sql = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resultExamId);
    $stmt->execute();
    $examResult = $stmt->get_result();
    $exam = $examResult->fetch_assoc();
    
    // Get all results for this exam
    $sql2 = "SELECT * FROM results WHERE exam_id = ? ORDER BY score DESC";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $resultExamId);
    $stmt2->execute();
    $allResults = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student - Take Exam</title>
<style>
/* Your existing CSS remains exactly the same */
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
.error-message{
  color:#b91c1c;
  margin-top:15px;
  text-align:center;
}
</style>
</head>
<body>

<header>
  <h2>🧑‍🎓 Student Exam Portal</h2>
  <a href="index.php">← Back to Home</a>
</header>

<?php if (isset($_GET['take_exam']) && isset($_SESSION['current_exam'])): 
    $exam = $_SESSION['current_exam'];
?>
<!-- Exam Container -->
<div class="container" id="examContainer">
  <div class="exam-header">
    <h3 id="examTitleDisplay"><?php echo htmlspecialchars($exam['title']); ?></h3>
    <div class="timer" id="timerDisplay">00:00</div>
  </div>
  
  <!-- Question Palette -->
  <div class="question-palette" id="questionPalette"></div>
  
  <!-- Current Question Display -->
  <div id="questionsArea"></div>
  
  <!-- Navigation Buttons -->
  <div class="question-navigation">
    <button class="warning-btn" onclick="previousQuestion()" id="prevBtn" style="width:100px;">← Prev</button>
    <span class="question-counter" id="questionCounter">Question 1/<?php echo count($exam['questions']); ?></span>
    <button class="next-btn" onclick="nextQuestion()" id="nextBtn" style="width:100px;">Next →</button>
  </div>
  
  <!-- Submit Button (hidden initially) -->
  <form method="POST" action="" id="submitExamForm">
    <div style="display:flex; gap:10px; margin-top:20px;" id="submitButtonContainer">
      <button type="button" class="success-btn" onclick="submitExam()" style="flex:2;" id="submitBtn">✅ Submit Exam</button>
      <button type="button" class="warning-btn" onclick="cancelExam()" style="flex:1;">Cancel</button>
    </div>
    <input type="hidden" name="submit_exam" value="1">
    <div id="answersContainer"></div>
  </form>
</div>

<script>
  let currentExam = <?php echo json_encode($exam['questions']); ?>;
  let currentQuestionIndex = 0;
  let userAnswers = new Array(<?php echo count($exam['questions']); ?>).fill(null);
  let endTime = null;
  let timerInterval = null;

  // Set timer
  let durationMinutes = <?php echo (int)$exam['duration']; ?>;
  endTime = Date.now() + durationMinutes * 60 * 1000;

  // Render initial exam
  renderQuestionPalette();
  renderCurrentQuestion();
  updateNavigationButtons();
  startTimer();

  function renderQuestionPalette(){
    let palette = document.getElementById("questionPalette");
    if(!palette) return;
    palette.innerHTML = "";
    
    for(let i = 0; i < currentExam.length; i++){
      let item = document.createElement("div");
      item.className = `palette-item ${userAnswers[i] !== null ? 'answered' : ''} ${i === currentQuestionIndex ? 'current' : ''}`;
      item.textContent = i + 1;
      item.onclick = () => jumpToQuestion(i);
      palette.appendChild(item);
    }
  }

  function jumpToQuestion(index){
    saveCurrentAnswer();
    currentQuestionIndex = index;
    renderCurrentQuestion();
    updateNavigationButtons();
    renderQuestionPalette();
  }

  function saveCurrentAnswer(){
    let radios = document.getElementsByName(`q${currentQuestionIndex}`);
    if(radios.length > 0){
      for(let r of radios){
        if(r.checked){
          userAnswers[currentQuestionIndex] = parseInt(r.value);
          break;
        }
      }
    }
  }

  function renderCurrentQuestion(){
    let area = document.getElementById("questionsArea");
    if(!area) return;
    area.innerHTML = "";
    
    let q = currentExam[currentQuestionIndex];
    let idx = currentQuestionIndex;
    
    let qDiv = document.createElement("div");
    qDiv.className = "question-card";
    qDiv.innerHTML = `<strong>Q${idx+1}:</strong> ${q.question}`;

    let optsDiv = document.createElement("div");
    optsDiv.className = "options";

    q.options.forEach((opt, optIdx) => {
      let letter = String.fromCharCode(65 + optIdx);
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
    
    let counter = document.getElementById("questionCounter");
    if(counter){
      counter.textContent = `Question ${currentQuestionIndex + 1}/${currentExam.length}`;
    }
  }

  function updateNavigationButtons(){
    let prevBtn = document.getElementById("prevBtn");
    let nextBtn = document.getElementById("nextBtn");
    let submitContainer = document.getElementById("submitButtonContainer");
    
    if(!prevBtn || !nextBtn || !submitContainer) return;
    
    if(currentQuestionIndex === 0){
      prevBtn.style.visibility = "hidden";
    } else {
      prevBtn.style.visibility = "visible";
    }
    
    if(currentQuestionIndex === currentExam.length - 1){
      nextBtn.style.display = "none";
      submitContainer.style.display = "flex";
    } else {
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
    
    if(currentQuestionIndex < currentExam.length - 1){
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
      let timerDisplay = document.getElementById("timerDisplay");
      if(timerDisplay){
        timerDisplay.innerText = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
      }
      
      if(remaining <= 0){
        clearInterval(timerInterval);
        alert("⏰ Time's up! Submitting automatically.");
        submitExam();
      }
    }, 500);
  }

  function submitExam(){
    saveCurrentAnswer();
    
    // Add answers to form
    let answersContainer = document.getElementById("answersContainer");
    if(answersContainer){
      answersContainer.innerHTML = "";
      
      userAnswers.forEach((answer, index) => {
        if(answer !== null){
          let input = document.createElement("input");
          input.type = "hidden";
          input.name = `answers[${index}]`;
          input.value = answer;
          answersContainer.appendChild(input);
        }
      });
    }
    
    document.getElementById("submitExamForm").submit();
  }

  function cancelExam(){
    if(confirm("Cancel exam? Your progress will be lost.")){
      window.location.href = "student-exam.php";
    }
  }
</script>

<?php elseif (isset($_GET['view_results']) && isset($allResults) && isset($exam)): ?>
<!-- Results Container -->
<div class="container" id="rankContainer">
  <h2>🏆 Rank Board</h2>
  <div id="rankExamInfo" style="margin-bottom:20px;">
    <strong><?php echo htmlspecialchars($exam['title']); ?></strong> (ID: <?php echo htmlspecialchars($resultExamId); ?>)
  </div>
  
  <div class="rank-container" id="rankList">
    <?php
    // Add rank numbers
    $rankedList = [];
    foreach ($allResults as $idx => $r) {
        $r['rank'] = $idx + 1;
        $rankedList[] = $r;
    }
    
    // Find current user
    $userResult = null;
    $otherResults = [];
    foreach ($rankedList as $r) {
        if ($r['student_name'] === $resultStudentName) {
            $userResult = $r;
        } else {
            $otherResults[] = $r;
        }
    }
    
    // Display user result
    if ($userResult):
    ?>
    <div class="user-rank-box">
      <h3>🎯 Your Result</h3>
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
          <p style="font-size:18px; margin:5px 0;">Rank: <strong>#<?php echo $userResult['rank']; ?></strong></p>
          <p style="font-size:16px; margin:5px 0;"><?php echo htmlspecialchars($userResult['student_name']); ?></p>
        </div>
        <div class="score"><?php echo $userResult['score']; ?>/<?php echo $userResult['total']; ?></div>
      </div>
      <div style="margin-top:10px; color:#666;">
        Score: <?php echo round(($userResult['score'] / $userResult['total']) * 100); ?>%
      </div>
    </div>
    <?php endif; ?>
    
    <!-- Display other students -->
    <?php if (count($otherResults) > 0): ?>
    <div class="others-rank-box">
      <h3>📊 Other Students</h3>
      <?php foreach ($otherResults as $r): ?>
      <div class="rank-card">
        <span class="rank">#<?php echo $r['rank']; ?></span>
        <span class="name"><?php echo htmlspecialchars($r['student_name']); ?></span>
        <span class="score"><?php echo $r['score']; ?>/<?php echo $r['total']; ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <?php if (empty($allResults)): ?>
    <p>No submissions yet.</p>
    <?php endif; ?>
  </div>
  
  <button class="primary-btn" onclick="window.location.href='student-exam.php'" style="margin-top:20px;">← Back to Entry</button>
</div>

<?php else: ?>
<!-- Entry Container -->
<div class="container" id="entryContainer">
  <h2>Enter Exam Details</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message"><?php echo $error; ?></div>
  <?php endif; ?>
  
  <form method="POST" action="">
    <div class="section-title">Your Name</div>
    <input type="text" name="student_name" placeholder="Enter your full name" required>

    <div class="section-title">Exam ID</div>
    <input type="text" name="exam_id" placeholder="Enter exam ID" required>

    <button type="submit" name="start_exam" class="primary-btn" style="margin-top:30px;">▶ Start Exam</button>
  </form>
</div>
<?php endif; ?>

</body>
</html>
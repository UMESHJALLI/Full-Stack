<?php
include 'db_connect.php';

// Handle exam publishing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish_exam'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $duration = (int)$_POST['duration'];
    $examId = "EXAM" . rand(1000, 9999);
    
    // Insert exam
    $sql = "INSERT INTO exams (exam_id, title, duration) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $examId, $title, $duration);
    
    if ($stmt->execute()) {
        // Insert questions
        $questions = json_decode($_POST['questions_data'], true);
        $order = 1;
        
        foreach ($questions as $q) {
            $qText = $conn->real_escape_string($q['question']);
            $optA = $conn->real_escape_string($q['options'][0]);
            $optB = $conn->real_escape_string($q['options'][1]);
            $optC = $conn->real_escape_string($q['options'][2]);
            $optD = $conn->real_escape_string($q['options'][3]);
            $correct = $q['answer'];
            
            $sql2 = "INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_answer, question_order) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("sssssssi", $examId, $qText, $optA, $optB, $optC, $optD, $correct, $order);
            $stmt2->execute();
            $order++;
        }
        
        $success = true;
        $newExamId = $examId;
    }
}

// Handle rank viewing
if (isset($_GET['view_ranks'])) {
    $viewEid = $conn->real_escape_string($_GET['rank_exam_id']);
    $rankData = [];
    
    // Get exam details
    $examSql = "SELECT * FROM exams WHERE exam_id = ?";
    $stmt = $conn->prepare($examSql);
    $stmt->bind_param("s", $viewEid);
    $stmt->execute();
    $examResult = $stmt->get_result();
    $viewExam = $examResult->fetch_assoc();
    
    if ($viewExam) {
        // Get results
        $resultSql = "SELECT * FROM results WHERE exam_id = ? ORDER BY score DESC";
        $stmt = $conn->prepare($resultSql);
        $stmt->bind_param("s", $viewEid);
        $stmt->execute();
        $rankData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Teacher - Create Exam</title>
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
.success-message{
  color:#16a34a;
  margin-top:10px;
  text-align:center;
}
</style>
</head>
<body>

<header>
  <h2>📚 Teacher Panel - Create Exam</h2>
  <a href="index.php">← Back to Home</a>
</header>

<div class="container">

  <!-- Display success message if exam published -->
  <?php if (isset($success) && $success): ?>
    <div class="exam-id-box" style="display:block;">
      ✅ Exam Published Successfully<br>
      Exam ID: <span><?php echo $newExamId; ?></span>
    </div>
  <?php endif; ?>

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

  <!-- RANK SECTION (View ranks) -->
  <div class="rank-section">
    <h3>📊 View Student Ranks</h3>
    <form method="GET" action="">
      <input type="text" name="rank_exam_id" placeholder="Enter Exam ID (e.g. EXAM1234)" required>
      <button type="submit" name="view_ranks" class="rank-btn">Show Ranks</button>
    </form>
    
    <?php if (isset($viewExam)): ?>
      <div class="rank-display">
        <?php if ($viewExam): ?>
          <strong>📊 Exam: <?php echo htmlspecialchars($viewExam['title']); ?> (<?php echo htmlspecialchars($viewEid); ?>)</strong><br>
          Total Submissions: <?php echo count($rankData); ?><br>
          ━━━━━━━━━━━━━━━━━━━━<br>
          <?php 
          if (count($rankData) > 0) {
              foreach ($rankData as $idx => $r) {
                  $percentage = round(($r['score'] / $r['total']) * 100);
                  echo ($idx + 1) . ". " . htmlspecialchars($r['student_name']) . "\n";
                  echo "   Score: " . $r['score'] . "/" . $r['total'] . " (" . $percentage . "%)\n";
              }
          } else {
              echo "No submissions yet.";
          }
          ?>
        <?php else: ?>
          Exam ID not found.
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  // Initialize with one question row
  let questions = [];

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
  }

  function removeQuestionRow(rowId) {
    const row = document.getElementById(rowId);
    if(row) {
      row.remove();
    }
  }

  // Add first question row on page load
  window.onload = function(){
    addQuestionRow();
  };

  function publishExam(){
    const title = document.getElementById("title").value;
    const duration = document.getElementById("duration").value;

    if(title === "" || duration === ""){
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
        answer: correctOpt
      });
    });

    if(!isValid || questions.length === 0){
      alert("Please add at least one complete question");
      return;
    }

    // Create a form and submit to PHP
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';

    const titleInput = document.createElement('input');
    titleInput.type = 'hidden';
    titleInput.name = 'title';
    titleInput.value = title;
    form.appendChild(titleInput);

    const durationInput = document.createElement('input');
    durationInput.type = 'hidden';
    durationInput.name = 'duration';
    durationInput.value = duration;
    form.appendChild(durationInput);

    const questionsInput = document.createElement('input');
    questionsInput.type = 'hidden';
    questionsInput.name = 'questions_data';
    questionsInput.value = JSON.stringify(questions);
    form.appendChild(questionsInput);

    const publishInput = document.createElement('input');
    publishInput.type = 'hidden';
    publishInput.name = 'publish_exam';
    publishInput.value = '1';
    form.appendChild(publishInput);

    document.body.appendChild(form);
    form.submit();
  }
</script>

</body>
</html>
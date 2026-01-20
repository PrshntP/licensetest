<?php
$questions = include "questions.php";
$questions_json = json_encode($questions);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Nepali License Pro - Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --accent: #38bdf8;
            --success: #10b981;
            --danger: #ef4444;
            --nav-height: 60px;
        }

        body { 
            background-color: var(--bg); 
            color: #f8fafc; 
            font-family: 'Inter', sans-serif;
            height: 100vh; /* Force full height */
            width: 100vw;
            overflow: hidden; /* Disable body scroll */
            display: flex;
            flex-direction: column;
        }

        /* Nav fixed height */
        .glass-nav {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            height: var(--nav-height);
            flex-shrink: 0;
            z-index: 1000;
        }

        /* Main Container fills remaining space */
        .quiz-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            /* Adjust top padding to account for navbar */
            padding-top: calc(var(--nav-height) + 10px); 
        }

        /* Card is flexible but bounded */
        .main-card {
            background: var(--card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5);
            width: 100%;
            max-height: 100%; /* Don't go taller than container */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Keep content inside rounded corners */
            position: relative;
        }

        /* Content Areas */
        #question-screen, #result-screen {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 1.5rem;
        }

        /* Header (Q number & score) - Fixed at top of card */
        .quiz-header {
            flex-shrink: 0;
            margin-bottom: 1rem;
        }

        /* Scrollable Middle Section (Question + Img + Options) */
        .quiz-body {
            flex: 1;
            overflow-y: auto; /* Scroll only this part if needed */
            padding-right: 5px; /* Space for scrollbar */
            
            /* Custom Scrollbar for sleek look */
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        
        .quiz-body::-webkit-scrollbar { width: 6px; }
        .quiz-body::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }

        /* Footer (Buttons) - Fixed at bottom of card */
        .quiz-footer {
            flex-shrink: 0;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Typography sizing */
        #question-text {
            font-size: clamp(1rem, 2.5vh, 1.25rem); /* Scales with height */
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        /* Image Logic - Crucial for fitting */
        #image-container {
            text-align: center;
            margin-bottom: 1rem;
            flex-shrink: 1; /* Allow shrinking */
            min-height: 0;  /* Allow flex item to shrink below content size */
        }

        #image-container img {
            border-radius: 12px;
            max-height: 25vh; /* Max 25% of viewport height */
            max-width: 100%;
            object-fit: contain;
            background: #fff;
            padding: 5px;
        }

        /* Compact Options */
        .option-btn {
            display: block;
            width: 100%;
            padding: 12px 16px; /* Reduced padding */
            margin-bottom: 8px; /* Reduced margin */
            background: #273549;
            border: 2px solid transparent;
            border-radius: 12px;
            color: #f1f5f9;
            text-align: left;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .option-btn:hover:not(.disabled) {
            background: #334155;
            border-color: var(--accent);
            transform: translateX(3px);
        }

        /* Logic Classes */
        .option-btn.correct {
            background: rgba(16, 185, 129, 0.2) !important;
            border-color: var(--success) !important;
            color: var(--success) !important;
            font-weight: bold;
        }
        .option-btn.wrong {
            background: rgba(239, 68, 68, 0.2) !important;
            border-color: var(--danger) !important;
            color: var(--danger) !important;
        }
        .option-btn.disabled { pointer-events: none; opacity: 0.8; }

        /* Media Query for very small devices */
        @media (max-height: 700px) {
            .option-btn { padding: 10px 14px; font-size: 0.9rem; margin-bottom: 6px; }
            #image-container img { max-height: 20vh; }
            .quiz-container { padding: 0.5rem; padding-top: calc(var(--nav-height) + 5px); }
            #question-screen { padding: 1rem; }
        }
    </style>
</head>
<body>

<nav class="navbar glass-nav fixed-top px-3 d-flex align-items-center">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-info m-0">PRASH <span class="text-white">LICENSE PRO</span></span>
        <a href="admin/login.php" class="btn btn-sm btn-outline-light rounded-pill px-3">Admin</a>
    </div>
</nav>

<div class="quiz-container">
    <div id="quiz-wrapper" class="main-card animate__animated animate__fadeInUp">
        
        <div id="question-screen">
            <div class="quiz-header d-flex justify-content-between text-secondary fw-bold small">
                <span id="question-number">Question 1</span>
                <span>Correct: <span id="score-count" class="text-success">0</span></span>
            </div>
            
            <div class="quiz-body">
                <h2 id="question-text" class="fw-bold">Loading...</h2>
                <div id="image-container" class="d-none"></div>
                <div id="options-list"></div>
            </div>

            <div class="quiz-footer d-flex justify-content-between align-items-center">
                <button id="end-quiz" class="btn btn-link text-secondary text-decoration-none p-0" style="font-size: 0.9rem;">End Quiz</button>
                <button id="next-btn" class="btn btn-primary rounded-pill px-4 d-none animate__animated animate__fadeInRight">Next</button>
            </div>
        </div>

        <div id="result-screen" class="text-center d-none animate__animated animate__zoomIn justify-content-center">
            <h2 class="mb-2">Session Ended</h2>
            <div class="display-1 fw-bold text-info lh-1 mb-2" id="final-ratio">0/0</div>
            <p class="text-muted mb-4">Correct Answers / Attempted</p>
            <div id="feedback-text" class="h5 mb-4 text-white fw-light"></div>
            <div>
                <button onclick="location.reload()" class="btn btn-info btn-lg rounded-pill px-5 fw-bold shadow">Restart</button>
            </div>
        </div>
    </div>
</div>

<script>
    const questions = <?= $questions_json ?>;
    let currentIdx = 0;
    let score = 0;
    let attempted = 0;
    let answered = false;

    // Shuffle questions
    const shuffledQuestions = questions.sort(() => 0.5 - Math.random());

    const questionText = document.getElementById('question-text');
    const optionsList = document.getElementById('options-list');
    const imageContainer = document.getElementById('image-container');
    const nextBtn = document.getElementById('next-btn');
    const qNumDisplay = document.getElementById('question-number');
    const scoreDisplay = document.getElementById('score-count');

    function loadQuestion() {
        answered = false;
        const q = shuffledQuestions[currentIdx];
        
        nextBtn.classList.add('d-none');
        
        // Slight fade animation on text change
        const bodySection = document.querySelector('.quiz-body');
        bodySection.classList.remove('animate__fadeIn');
        void bodySection.offsetWidth; // trigger reflow
        bodySection.classList.add('animate__fadeIn');

        questionText.innerText = q.question;
        qNumDisplay.innerText = `Question ${currentIdx + 1} / ${shuffledQuestions.length}`;

        if (q.image) {
            imageContainer.innerHTML = `<img src="${q.image}">`;
            imageContainer.classList.remove('d-none');
        } else {
            imageContainer.classList.add('d-none');
        }

        optionsList.innerHTML = '';
        
        Object.entries(q.options).forEach(([key, optText], i) => {
            const btn = document.createElement('button');
            btn.className = 'option-btn animate__animated animate__fadeInUp';
            btn.style.animationDelay = `${i * 0.05}s`; // Faster animation
            btn.innerText = optText;
            btn.onclick = () => handleSelection(parseInt(key), btn);
            optionsList.appendChild(btn);
        });
    }

    function handleSelection(selectedKey, btn) {
        if (answered) return;
        answered = true;
        attempted++;

        const q = shuffledQuestions[currentIdx];
        const allButtons = document.querySelectorAll('.option-btn');
        const isCorrect = (selectedKey === q.answer);

        if (isCorrect) {
            btn.classList.add('correct');
            score++;
            scoreDisplay.innerText = score;
        } else {
            btn.classList.add('wrong');
            Object.keys(q.options).forEach((key, index) => {
                if (parseInt(key) === q.answer) {
                    allButtons[index].classList.add('correct');
                }
            });
        }

        allButtons.forEach(b => b.classList.add('disabled'));
        nextBtn.classList.remove('d-none');
        
        // Auto scroll to bottom of card to ensure Next button is seen if on tiny screen
        const quizBody = document.querySelector('.quiz-body');
        quizBody.scrollTo({ top: quizBody.scrollHeight, behavior: 'smooth' });
    }

    nextBtn.onclick = () => {
        currentIdx++;
        if (currentIdx < shuffledQuestions.length) {
            loadQuestion();
        } else {
            showResults();
        }
    };

    document.getElementById('end-quiz').onclick = showResults;

    function showResults() {
        document.getElementById('question-screen').classList.add('d-none');
        document.getElementById('result-screen').classList.remove('d-none');
        document.getElementById('final-ratio').innerText = `${score} / ${attempted}`;
        
        const feedback = document.getElementById('feedback-text');
        const percentage = attempted > 0 ? (score / attempted) * 100 : 0;
        
        if(percentage >= 80) feedback.innerText = "बबाल छ तयारी!";
        else if(percentage >= 50) feedback.innerText = "अलि मेहेनत पुगेन!";
        else feedback.innerText = "भएन भएन एस्तो पाराले!";
    }

    loadQuestion();
</script>
</body>
</html>
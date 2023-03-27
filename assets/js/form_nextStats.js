
let nextQuestionButtons = document.querySelectorAll(".next-question-stat");
let questions = document.querySelectorAll('.question')
let totalQuestions = questions.length
nextQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        let currentQuestion = button.parentElement.parentElement.parentElement;
        console.log(currentQuestion);
        let nextQuestionNumber = button.dataset.nextQuestion;
        let nextQuestion = document.getElementById('question-' + nextQuestionNumber);
        if (nextQuestion == null ) {
            let firstQuestionNumber = 1;
            let retourDeb = document.getElementById('question-' + firstQuestionNumber);
            currentQuestion.style.display = 'none';
            retourDeb.style.display = 'block';
        }
        currentQuestion.style.display = 'none';
        nextQuestion.style.display = 'block';
    })
})


//pour previous
let previousQuestionButtons = document.querySelectorAll(".previous-question-stat");

previousQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        console.log('click')
        let currentQuestion = button.parentElement.parentElement.parentElement;
        let previousQuestionNumber = button.dataset.previousQuestion;
        let previousQuestion = document.getElementById('question-' + previousQuestionNumber);
        if (previousQuestion == null ) {
            let retourFin = document.getElementById('question-' + totalQuestions);
            currentQuestion.style.display = 'none';
            retourFin.style.display = 'block';
        }
        currentQuestion.style.display = 'none';
        previousQuestion.style.display = 'block';
    })
})

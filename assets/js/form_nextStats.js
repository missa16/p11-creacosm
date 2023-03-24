
let nextQuestionButtons = document.querySelectorAll(".next-question-stat");

nextQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        let currentQuestion = button.parentElement;
        let nextQuestionNumber = button.dataset.nextQuestion;
        let nextQuestion = document.getElementById('question-' + nextQuestionNumber);
        if (nextQuestion == null ) {
            let retourDeb = document.getElementById('question-' + 1);
            currentQuestion.style.display = 'none';
            retourDeb.style.display = 'block';
        }
        currentQuestion.style.display = 'none';
        nextQuestion.style.display = 'block';
    })
})


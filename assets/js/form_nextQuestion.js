let form = document.querySelector('form');
form.addEventListener('submit', (event) => {
    event.preventDefault();
});

let nextQuestionButtons = document.querySelectorAll('.next-question-button');
let questions = document.querySelectorAll('.question')
let totalQuestions = questions.length
let submitButton = document.querySelector('button[type="submit"]');

console.log(totalQuestions);
nextQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        let currentQuestion = button.parentElement;
        let nextQuestionNumber = button.dataset.nextQuestion;
        let nextQuestion = document.getElementById('question-' + nextQuestionNumber);

        if (nextQuestion == null ) {
            document.getElementById('confirmation-message').style.display = 'block';

        }
        currentQuestion.style.display = 'none';
        nextQuestion.style.display = 'block';
    });
});

submitButton.addEventListener('click', () => {
    form.submit();
});
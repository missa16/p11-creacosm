let form = document.querySelector('form');
form.addEventListener('submit', (event) => {
    event.preventDefault();
});

let nextQuestionButtons = document.querySelectorAll('.next-question-button');
let questions = document.querySelectorAll('.question')
let totalQuestions = questions.length
let submitButton = document.querySelector('button[type="submit"]');

// pour le bouton question precedente
let previousQuestionButtons = document.querySelectorAll('.previous-question-button');

previousQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        let currentQuestion = button.parentElement.parentElement;
        let previousQuestionNumber = button.dataset.previousQuestion;
        let previousQuestion = document.getElementById('question-' + previousQuestionNumber);
        currentQuestion.style.display = 'none';
        previousQuestion.style.display = 'block';
    });
});
nextQuestionButtons.forEach(button => {
    button.addEventListener('click', () => {
        let currentQuestion =   button.parentElement.parentElement;             //button.parentElement;
        let nextQuestionNumber = button.dataset.nextQuestion;
        let nextQuestion = document.getElementById('question-' + nextQuestionNumber);
        console.log(`Hiding question-${currentQuestion.id} and showing question-${nextQuestion.id}`);
        currentQuestion.style.display = 'none';
        nextQuestion.style.display = 'block';
    });
});


// if (currentQuestion.id === `question-${totalQuestions-1}` ) {
//     console.log('Showing confirmation message');
//     document.getElementById('confirmation-message').style.display = 'block';
//     currentQuestion.style.display = 'none';
// }

submitButton.addEventListener('click', () => {
    form.submit();
});
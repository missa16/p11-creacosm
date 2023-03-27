
let deleteBtn = document.querySelector('#delete-survey');
deleteBtn.addEventListener('click', () => {
    event.preventDefault();
    // Get the question ID from the data attribute
    const sondageId = deleteBtn.dataset.sondage;
    const confirmResult = confirm('Voulez-vous vraiment supprimer ce sondage ?');
    if (confirmResult === true) {
        // Make an AJAX request to your Symfony controller
        fetch(`/sondeur/mes-sondages/delete/${sondageId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                data: sondageId
            })
        })
            .then(response => {
                //console.log(response)
                // Handle the response from your controller
                location.replace('/sondeur/mes-sondages');
            })
            .catch(error => {
                // Handle any errors that occur during the request
                console.error(error);
            });
    }
})













// Get the button element
let buttons = document.querySelectorAll('.form-image-question-form');

buttons.forEach(button => {
    button.addEventListener('click', () => {

        event.preventDefault();

        // Get the question ID from the data attribute
        const questionId = button.dataset.question;

        const confirmResult = confirm('Voulez-vous vraiment supprimer cette image ?');

        if (confirmResult === true) {
        // Make an AJAX request to your Symfony controller
        fetch(`/sondeur/mes-sondages/image-question/${questionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                data: questionId
            })
        })
            .then(response => {
                // Handle the response from your controller
                location.reload();
            })
            .catch(error => {
                // Handle any errors that occur during the request
                console.error(error);
            });
        }
    })

})


let buttonDeleteImage = document.querySelector('.delete-image-form');
buttonDeleteImage.addEventListener('click', () => {
        event.preventDefault();

        // Get the question ID from the data attribute
        const sondageId = buttonDeleteImage.dataset.sondage;

        const confirmResult = confirm('Voulez-vous vraiment supprimer cette image ?');

        if (confirmResult === true) {
            // Make an AJAX request to your Symfony controller
            fetch(`/sondeur/mes-sondages/image/${sondageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    data: sondageId
                })
            })
                .then(response => {
                    // Handle the response from your controller
                    location.reload();
                })
                .catch(error => {
                    // Handle any errors that occur during the request
                    console.error(error);
                });
        }
})


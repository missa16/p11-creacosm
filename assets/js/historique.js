
// recuperation des elements de la liste de sondage

let displayQ = document.getElementById('displayQuestion');

let listeSondages = document.querySelectorAll('.sondageDone');
let reponses = document.querySelector('.reponses');
listeSondages.forEach(li => {
        let name = li.querySelector('a');
        displayQ.style.display="none";
        name.addEventListener("click", (event) => {

                event.preventDefault();
                // Hide all "reponses" blocks except the one associated with the clicked "li"
                listeSondages.forEach(s => {
                        if (s !== li) {
                                displayQ.style.display="block";
                                s.querySelector('.reponses').style.display = 'none';
                        }
                });
                // Toggle the display property of the "reponses" block associated with the clicked "li"
                reponses.style.display = reponses.style.display === 'none' ? 'block' : 'none';
                displayQ.style.display= displayQ.style.display === 'none' ? 'block' : 'none';
        });
});



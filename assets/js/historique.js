
// recuperation des elements de la liste de sondage


let listeSondages = document.querySelectorAll('.sondageDone');
listeSondages.forEach(li => {
        let reponses = li.querySelector('.reponses');
        let name = li.querySelector('a');
        name.addEventListener("click", (event) => {
                event.preventDefault();
                // Hide all "reponses" blocks except the one associated with the clicked "li"
                listeSondages.forEach(s => {
                        if (s !== li) {
                                s.querySelector('.reponses').style.display = 'none';
                        }
                });
                // Toggle the display property of the "reponses" block associated with the clicked "li"
                reponses.style.display = reponses.style.display === 'none' ? 'block' : 'none';
        });
});



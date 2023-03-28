
// recuperation des elements de la liste de sondage

let displayQ = document.getElementById('displayQuestion');
let listeSondages = document.querySelectorAll('.sondageDone');
let reponses = document.querySelector('.reponses');



    listeSondages.forEach(l=>{
        l.addEventListener('click',(event)=> {
            event.preventDefault();
        // Hide all "reponses" blocks except the one associated with the clicked "li"
        listeSondages.forEach(s => {
                reponses.innerHTML=s.dataset.reponses;
        });
    });
        // Toggle the display property of the "reponses" block associated with the clicked "li"


    //let target = event.target;
    // if (target.tagName === 'A'){
    //     event.target.classList.toggle('select');
    //     displayQ.innerHTML="hello";
    // }
})






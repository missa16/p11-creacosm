function addAnswerFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerHTML = "<span class='bi  bi-trash'>Supprimer cette réponse</span>";
    removeFormButton.style.minHeight = "37.5px";
    removeFormButton.style.maxWidth = "15rem";
    removeFormButton.style.marginBottom = "1rem";
    removeFormButton.style.alignSelf = "end";
    removeFormButton.style.background = 'red';
    removeFormButton.style.border = 'red';
    removeFormButton.style.borderRadius = '8px';
    removeFormButton.style.color = 'white';
    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}


function addAnswer(btn) {
    //console.log(btn.currentTarget.dataset.collectionHolderClass)
    let collectionHolder = document.querySelector('.' + btn.currentTarget.dataset.collectionHolderClass);
    let questionIndex = collectionHolder.parentElement.dataset.indice;
    let answerIndex = collectionHolder.dataset.index;
    console.log("indice de la question" + collectionHolder.parentElement.dataset.indice)
    console.log("l'indice de la réponse" + collectionHolder.dataset.index)
    let item = document.createElement('li');
    item.style.display = "flex";
    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            //e.currentTarget.dataset.indice
            collectionHolder.dataset.index
        );
    let div = item.querySelector('div');
    div.innerHTML = `<label for="sondage_Questions_${questionIndex}_Reponses_${answerIndex}_laReponse" class="form-label required">La reponse</label> <input type="text" id="sondage_Questions_${questionIndex}_Reponses_${answerIndex}_laReponse" name="sondage[Questions][${questionIndex}][Reponses][${answerIndex}][laReponse]" required="required" class="form-control">`

    console.log("non" + item.innerHTML);
    collectionHolder.appendChild(item);
    //e.currentTarget.dataset.indice++
    collectionHolder.dataset.index++;
    addAnswerFormDeleteLink(item)
    item.firstElementChild.style.flexGrow = "1";
    item.firstElementChild.style.marginRight = "1rem";
}

function addQuestionFormDeleteLink(item) {

    //console.log('fonction add form delet test d\'une valeur'+item.dataset.indice);

    let div = document.createElement('div');
    div.style.display='flex';

    let removeFormButton = document.createElement('button');
    removeFormButton.innerHTML = "<span class='bi  bi-trash'>Supprimer cette question</span>";
    removeFormButton.classList.add("button-delete");
    removeFormButton.style.flexGrow=1
    removeFormButton.style.marginRight='1rem'
    // item.append(removeFormButton);
    div.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });

    let addAnswerFormButton = document.createElement('button');
    addAnswerFormButton.innerHTML = "<span class='bi  bi-plus-circle'>Ajouter une réponse</span>";
    addAnswerFormButton.classList.add("add_ans_link", "button-add");
    addAnswerFormButton.style.flexGrow=1
    let reponses = item.querySelector('ul');
    addAnswerFormButton.dataset.collectionHolderClass = reponses.className;
    // item.append(addAnswerFormButton);
    div.append(addAnswerFormButton);
    addAnswerFormButton.addEventListener('click', addAnswer);

    item.append(div);
}

function addFormToCollection(e) {

    let collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    let item = document.createElement('li');
    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );
    collectionHolder.appendChild(item);
    item.dataset.indice = collectionHolder.dataset.index.toString();
    collectionHolder.dataset.index++;
    addQuestionFormDeleteLink(item)
    //let reponses = document.querySelector('li ul');
    //reponses.classList.add('reponses-'+collectionHolder.dataset.index);
    //console.log(reponses.dataset.collectionHolderClass);
}

/////// Gestion des questions ////////
// Ajouter une nouvelle question
document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    })
// Supprimer une question
document
    .querySelectorAll('.questions > li')
    .forEach(question => {
        addQuestionFormDeleteLink(question)
    })


/////// Gestion des réponses //////////
// Ajouter une réponse


// document
//     .querySelectorAll('.add_ans_link')
//     .forEach(btn => {
//             btn.addEventListener("click", addAnswer(btn.dataset.collectionHolderClass))
//         }
//     )

document.querySelectorAll('.add_ans_link')
    .forEach(btn => {
        btn.addEventListener("click", addAnswer)
    })


// document
//     .querySelectorAll('.add_ans_link')
//    .forEach(btn => {
//            btn.addEventListener("click", function(){
//                addAnswer(btn);
//            }
//            )}
//    );

// Supprimer une réponse
document
    .querySelectorAll('.questions ul li')
    .forEach(rep => {
        addAnswerFormDeleteLink(rep)
    })




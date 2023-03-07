
function addAnswerFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerHTML="<span class='bi  bi-trash'>Supprimer cette réponse</span>";
    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}

function addAnswer(btn) {
    //console.log(btn.currentTarget.dataset.collectionHolderClass)
    let collectionHolder = document.querySelector('.'+btn.currentTarget.dataset.collectionHolderClass);
    //let collectionHolder = document.querySelector('.' + collectionHolderClass);
    console.log(collectionHolder);

    //console.log(collectionHolder)
    let item = document.createElement('li');
    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            //e.currentTarget.dataset.indice
            collectionHolder.dataset.index
        );
    collectionHolder.appendChild(item);
    //e.currentTarget.dataset.indice++
    collectionHolder.dataset.index++;
    addAnswerFormDeleteLink(item)
}

function addQuestionFormDeleteLink(item) {

    //console.log('fonction add form delet test d\'une valeur'+item.dataset.indice);

    let removeFormButton = document.createElement('button');
    removeFormButton.innerHTML="<span class='bi  bi-trash'>Supprimer cette question</span>";
    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });

    let addAnswerFormButton = document.createElement('button');
    addAnswerFormButton.innerHTML="Ajouter une reponse";
    addAnswerFormButton.classList.add('add_ans_link');
    let reponses = item.querySelector('ul');
    addAnswerFormButton.dataset.collectionHolderClass=reponses.className;
    item.append(addAnswerFormButton);
    addAnswerFormButton.addEventListener('click', addAnswer);
}

function addFormToCollection (e)  {
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
    collectionHolder.dataset.index++;
    addQuestionFormDeleteLink(item)
    //item.dataset.indice=collectionHolder.dataset.index.toString();;
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

 document
     .querySelectorAll('.add_ans_link')
    .forEach(btn => {
            btn.addEventListener("click", function(){
                addAnswer(btn);
            }
            )}
    );

// Supprimer une réponse
document
    .querySelectorAll('.questions ul li')
    .forEach(rep => {
        addAnswerFormDeleteLink(rep)
    })




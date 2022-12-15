(function () {
    let collectionHolderPicture, btnNewPicture, spanPicture;
    collectionHolderPicture = document.querySelector("#create_trick_picture");
    spanPicture = collectionHolderPicture.querySelector("span.pic");
    btnNewPicture = document.createElement("button");
    btnNewPicture.type = "button";
    btnNewPicture.className = "addPicture btn-form-class btn mb-3";
    btnNewPicture.innerText = "Ajouter une image";


    let newBtn = spanPicture.append(btnNewPicture);

    collectionHolderPicture.dataset.index = collectionHolderPicture.querySelectorAll("input").length;

    btnNewPicture.addEventListener("click", function (){
        addPicture(collectionHolderPicture, newBtn);
    });

    function addPicture(collectionHolderPicture, newBtn){
        //Div qui contient le modèle du sous-formulaire
        let prototype = collectionHolderPicture.dataset.prototype;
        let index = collectionHolderPicture.dataset.index;

        //Remplacement des attributs dataset name par l'index avec les regex
        prototype = prototype.replace(/__name__/g, index);

        let content = document.createElement("html");
        console.log(content);
        content.innerHTML= prototype;

        let newForm = content.querySelector("div");

        let btnDelete = document.createElement("button");
        btnDelete.type = "button";
        btnDelete.className = "deletePicture btn-form-class btn mb-2 mt-2";
        btnDelete.innerText = "Supprimer";
        btnDelete.id = "delete-" + index;

        //Ajout du bouton delete dans le form
        newForm.append(btnDelete);

        //Incrémentation de l'index
        collectionHolderPicture.dataset.index++;

        let btnAdd = collectionHolderPicture.querySelector(".addPicture");

        //insertion du bouton d'ajout de formulaire
        spanPicture.insertBefore(newForm, btnAdd);

        btnDelete.addEventListener("click", function () {
            //Suppression de l'element juste au dessus, donc le formulaire
            this.previousElementSibling.parentElement.remove();
            collectionHolderPicture.dataset.index--;
        })
    }
})();
(function () {
    let collectionHolderMovie, btnNewMovie, spanMovie;
    collectionHolderMovie = document.querySelector("#create_trick_movie");
    spanMovie = collectionHolderMovie.querySelector("span.mov");
    btnNewMovie = document.createElement("button");
    btnNewMovie.type = "button";
    btnNewMovie.className = "addMovie btn-form-class btn mb-3";
    btnNewMovie.innerText = "Ajouter une vidéo";

    let newBtnMovie = spanMovie.append(btnNewMovie);

    // Comptage du nombre de collection dans l'index du dataset
    collectionHolderMovie.dataset.index = collectionHolderMovie.querySelectorAll("input").length;


    btnNewMovie.addEventListener("click", function (){
        addMovie(collectionHolderMovie, newBtnMovie);
    });


    function addMovie(collectionHolder, newBtn){
        //Div qui contient le modèle du sous-formulaire
        let prototype = collectionHolder.dataset.prototype;
        let index = collectionHolder.dataset.index;
        //Remplacement des attributs dataset name par l'index avec les regex
        prototype = prototype.replace(/__name__/g, index);

        let content = document.createElement("html");
        content.innerHTML= prototype;

        let newForm = content.querySelector("div");

        let btnDelete = document.createElement("button");
        btnDelete.type = "button";
        btnDelete.className = "delete btn-form-class btn mb-2 mt-2";
        btnDelete.innerText = "Supprimer";
        btnDelete.id = "delete-" + index;

        //Ajout du bouton delete dans le form
        newForm.append(btnDelete);

        //Incrémentation de l'index
        collectionHolder.dataset.index++;

        let btnAdd = collectionHolder.querySelector(".addMovie");

        //insertion du bouton d'ajout de formulaire
        spanMovie.insertBefore(newForm, btnAdd);

        btnDelete.addEventListener("click", function () {
            //Suppression de l'element juste au dessus, donc le formulaire
            this.previousElementSibling.parentElement.remove();
            collectionHolderMovie.dataset.index--;

        })
    }
})();


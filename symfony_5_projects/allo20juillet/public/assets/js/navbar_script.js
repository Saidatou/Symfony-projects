//Je charge le document avant de faire quoi que ce soit
window.onload = () => {
    //1)creation du constante pour aller chercher le nav bu-button de ma nav-bar
    const navButton = document.querySelector("#ma-navbar .nav-button");
    // et dessus je vais faire un écouteur d'évenement sur le click et utiliser la fonction
    //classique qui est le this. avec le quel je vais
    //  gérer apparition et la disparition du clique
    navButton.addEventListener("click", function (event) {
        // 7) On empêche le "click" de se propager au document en recupérent un event 
        // à l'intérieur de ma function et en faisant qui va me permettre
        //de continuer à gérer mon bouton directement tout en ayant toujours le retrait par 
        //un click n'importe où sur le document on fait pareil sur les nav-drops 
        event.stopPropagation();
        //2)je part donc chercher le ul et lui ajouter la class show mais ce qu'il y a c'est 
        // que je dois gérer le click pour afficher et le click pour masquer l'affichage
        // je vais prondre mon-button qui est symboliser par le "this", je vais chercher la prochaine balise
        //(nextelementSibling), je vais dans sa classList et je lui dis toggle("show")
        //ça ajoute la class show si le ul ne l'a pas et le retire dans le cas contraire.
        // par ce biais ça fonctionne pour le menu et non pas pour le sou menu je vais donc 
        //faire pareil pour la nav-drop
        this.nextElementSibling.classList.toggle("show");
    });
    // 3)création de la constante pour aller chercer la nav-drop  ou les nav-drops
    //s'il y en avait plusieurs de ma nav-bar avec queryselectorAll avec lequel je fais
    //une boucle car je vais devoir passer sur chacun des éléments
    const navDrops = document.querySelectorAll("#ma-navbar .nav-drop");
    // pour chaque dropdown trouvé on va lui mettre un écouteur d'évenement
    for (let dropdown of navDrops) {
        dropdown.addEventListener("click", function (event) {
            //on fait pareil qu'au dessus
            event.stopPropagation();
            for (let dropdown of navDrops) {
                //Je clique et ça apparaît et je clique ça disparait
                if (dropdown != this) dropdown.classList.remove("show");
            }
            //4)et cette fois ci c'est la function this qui prend la class "show"
            this.classList.toggle("show");
        });
    }

    // 5)On gère la fermeture des menus, j vais mettre un event listener sur le document 
    document.addEventListener("click", () => {
        // 6)quelque soit l'endroit où je clique sur le document, si j'ai la class show 
        //et je clique c'a s'enlève après qd je reclique aucune action ne se fait car 
        //c'est le document a pris le pas sur le button
        navButton.nextElementSibling.classList.remove("show");
        for (let dropdown of navDrops) {
            //On refermera tous le menus en funtion de ce qu'on aura demandé
            dropdown.classList.remove("show");
        }
    });
}
window.onload = (e) => {

    const btnRecherche = document.querySelector('.fa-magnifying-glass');
    const recherche = document.querySelector('#recherche');

    // Bouton de recherche sur la page accueil - ACTION
    btnRecherche.addEventListener('mousedown', (e) => {

        const urlParams = new URLSearchParams();
        if(recherche.value != '') {
            urlParams.set('recherche', recherche.value);
        }
        window.location.assign('https://e2196106.webdev.cmaisonneuve.qc.ca/projet_web/public/catalogue?'+urlParams.toString());
    });
}
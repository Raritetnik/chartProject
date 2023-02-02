window.onload = (e) => {

    const btnRecherche = document.querySelector('.fa-magnifying-glass');
    const recherche = document.querySelector('#recherche');


    btnRecherche.addEventListener('mousedown', (e) => {

        const urlParams = new URLSearchParams();
        if(recherche.value != '') {
            urlParams.set('recherche', recherche.value);
        }
        window.location.assign('http://localhost:8080/projet_web/public/catalogue?'+urlParams.toString());
    });

    const options = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    };

}
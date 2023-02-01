window.onload = (e) => {
    const filtres = document.querySelector('.filtres');
    const trier = document.querySelector('#btn-trier');

    const filtrePrix = document.querySelector('#prix');
    const filtreAnnee = document.querySelector('#annee');
    const recherche = document.querySelector('#filtre-recherche');


    filtres.addEventListener('change', (e) => {
        let resultats = filtres.querySelectorAll('input:checked');
        let vars = '';

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        if(urlParams == 0) {
            urlParams.set('recherche', recherche.value);
        } else {
            resultats.forEach(a => {
                urlParams.set(a.name, a.id);
            });
        }
        window.location.assign('http://localhost:8080/projet_web/public/catalogue?'+urlParams.toString());
    });

    const options = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    };

    trier.addEventListener('mousedown', (e) => {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        if(urlParams == 0 || !urlParams.has('trie')) {
            urlParams.set('trie', 'ASC');
        } else if(urlParams.get('trie') == 'ASC') {
            urlParams.set('trie', 'DESC');
        } else if(urlParams.get('trie') == 'DESC') {
            urlParams.set('trie', 'ASC');
        }
        window.location.assign('http://localhost:8080/projet_web/public/catalogue?'+urlParams.toString());

        fetch('http://localhost:8080/projet_web/public/LoadDB/getTimbres?'+urlParams.toString())
        .then((data) => data.json())
        .then(data => {
            console.log(data);
        });
    });


    /*function changerContenu(datas) {
        const grille = document.querySelector('.grille');
        let chaineHTML = "";
        datas.forEach( (data) => {
            chaineHTML += `
        <div class="carte"> <img src="${data.url}" alt="">
            <header>
                <h4 class="title">${data.titre}</h4>
                <small><i>Date limite: ${data.dateFin}</i></small>
                <div>
                <a href="timbre/show/${data.idTimbre}"><i class="fa-solid fa-hand-point-up"></i></a>
                <h1>CA$${ Number(data.prixPlancher).toLocaleString('en', options)}</h1>
                </div>
            </header>
        </div>
        `;
        });
        grille.innerHTML = chaineHTML;
    }*/
}



/* POST element vers une fonction de Controller

fetch('http://localhost:8080/projet_web/public/LoadDB/Filtre', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(vars)
        })
        .then((data) => data.json())
        .then(data => {
            changerContenu(data);
        });*/
window.onload = (e) => {
    const menu = document.querySelector('.menu');
    const contenu = document.querySelector('.contenu');

    // Les templates
    const tmpInfoCompte = document.querySelector('#infoCompte');
    const tmpFavoris = document.querySelector('#favoris');
    const tmpListeMises = document.querySelector('#listeMises');
    const tmplisteTimbres = document.querySelector('#listeTimbres');

    menu.addEventListener('mousedown', (e) => {
        let option = e.target.classList.value;
        switch(option) {
            case 'info':
                contenu.innerHTML = tmpInfoCompte.innerHTML;
                break;
            case 'favoris':
                contenu.innerHTML = tmpFavoris.innerHTML;
                break;
            case 'mises':
                contenu.innerHTML = tmpListeMises.innerHTML;
                break;
            case 'timbres':
                console.log('Affiche timbres');
                contenu.innerHTML = tmplisteTimbres.innerHTML;
                break;
            default:
        }
    });


}
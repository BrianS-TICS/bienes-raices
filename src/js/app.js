document.addEventListener('DOMContentLoaded', function() {
    eventListeners();
    darkMode();
});

function darkMode() {

    let electionColor = '';
    
    // Si existe una eleccion en el local storage eleccion color tomara su valor si no seguirá vacio
    localStorage.getItem('electionColor') != '' ? electionColor = localStorage.getItem('electionColor') : '';

    // Se le asigna booleano segun la preferencia del sistema
    const prefiereDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

    // Asignacion de color segun tema del ordenador
    if(electionColor === 'black') {
        document.body.classList.add('dark-mode');
        
    } else if(electionColor === 'white') {
        document.body.classList.remove('dark-mode');
    }

    // Si la preferencia del sistema cambia se elimina o agrega el modo oscuro
    prefiereDarkMode.addEventListener('change', function() {
        // Se evalua de nueva cuenta la mismo valor booleano
        if(prefiereDarkMode.matches) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    });


    // Evento para boton de dark mode
    const botonDarkMode = document.querySelector('.dark-mode-boton');
    botonDarkMode.addEventListener('click', function() {

        if(document.body.classList.contains('dark-mode')){
            localStorage.setItem('electionColor','white');
        }
        else{
            localStorage.setItem('electionColor','black');
        }
        document.body.classList.toggle('dark-mode');
    });

}

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenu.addEventListener('click', navegacionResponsive);
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navegacion');

    navegacion.classList.toggle('mostrar');
}
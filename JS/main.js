document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('buscador');
    
    if(buscador){
        buscador.addEventListener('input', (e) => {
            const texto = e.target.value.toLowerCase();
            const tarjetas = document.querySelectorAll('.card');

            tarjetas.forEach(card => {
                const titulo = card.querySelector('.titulo').textContent.toLowerCase();
                const autor = card.querySelector('.autor').textContent.toLowerCase();

                if (titulo.includes(texto) || autor.includes(texto)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
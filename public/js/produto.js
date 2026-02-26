document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.querySelector('#emoji-trigger');
    const input = document.querySelector('#emoji');
    const container = document.querySelector('#picker-container');

    // Cria o seletor
    const picker = picmo.createPicker({
        rootElement: container,
        theme: 'dark',
        showSearch: true,
        autoFocusSearch: true
    });

    // Esconde o container inicialmente
    container.style.display = 'none';

    // Toggle ao clicar no botão
    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        container.style.display = container.style.display === 'none' ? 'block' : 'none';
    });

    // Quando selecionar um emoji
    picker.addEventListener('emoji:select', (selection) => {
        input.value = selection.emoji;
        container.style.display = 'none'; // Fecha ao selecionar
    });

    // Fecha se clicar fora
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target) && e.target !== trigger) {
            container.style.display = 'none';
        }
    });
});


    const el = document.getElementById('sortable-produtos');
    Sortable.create(el, {
        animation: 150,
        ghostClass: 'table-active',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            document.querySelectorAll('.show-ordem').forEach((span, index) => {
                span.innerText = index + 1;
            });
        }
    });

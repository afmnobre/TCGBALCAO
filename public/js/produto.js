document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('#emojiPickerBtn');
  const input = document.querySelector('#emoji');
  const container = document.querySelector('#emojiContainer');

  if (button && input && container) {
    // Cria o componente emoji-picker
    const picker = document.createElement('emoji-picker');
    container.appendChild(picker);

    // Mostra/esconde o picker ao clicar no botão
    button.addEventListener('click', () => {
      container.style.display = container.style.display === 'none' ? 'block' : 'none';
    });

    // Evento disparado ao escolher um emoji
    picker.addEventListener('emoji-click', event => {
      input.value = event.detail.unicode;
      container.style.display = 'none'; // fecha após escolher
    });
  }
});


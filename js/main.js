const inputDivs = document.querySelectorAll('.input, .textarea, .select');

inputDivs.forEach(function(div) {
  const input = div.querySelector('input, textarea, select');

  
  const inputHasValue = input.value.trim() !== '';
  div.style.opacity = inputHasValue ? 1 : 0.8;


  input.addEventListener('input', function() {
    const inputHasValue = input.value.trim() !== '';
    div.style.opacity = inputHasValue ? 1 : 0.8;
  });
});


function show_hide_password(target, inputElement) {
    if (inputElement.getAttribute('type') == 'password') {
        target.classList.add('view');
        inputElement.setAttribute('type', 'text');
    } else {
        target.classList.remove('view');
        inputElement.setAttribute('type', 'password');
    }
    return false;
}



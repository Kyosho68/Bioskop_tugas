document.addEventListener('DOMContentLoaded', function() {
  const emailInput = document.querySelector('#email');
  const usernameInput = document.querySelector('#username');

  function checkField(field, value, resultElId) {
    if (!field) return;
    const resultEl = document.getElementById(resultElId);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'register_process.php', true);
    xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState==4 && xhr.status==200) {
        try {
          const res = JSON.parse(xhr.responseText);
          if (res.exists) {
            resultEl.textContent = res.message;
            resultEl.style.color = 'red';
          } else {
            resultEl.textContent = '';
          }
        } catch(e) { console.error(e); }
      }
    };
    xhr.send('action=check_'+resultElId + '&value=' + encodeURIComponent(value));
  }

  if (emailInput) {
    emailInput.addEventListener('blur', function() {
      checkField(emailInput, emailInput.value, 'email-check');
    });
  }
  if (usernameInput) {
    usernameInput.addEventListener('blur', function() {
      checkField(usernameInput, usernameInput.value, 'username-check');
    });
  }
});
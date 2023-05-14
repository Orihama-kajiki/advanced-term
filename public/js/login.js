const menuBtn = document.querySelector('.menu-btn');

document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

  fetch('/login', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ email, password }),
  })
    .then(response => response.json())
    .then(data => {
        localStorage.setItem('authToken', data.token);

        window.location.href = '/';
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

window.toggleModal = function () {
  const modalBg = document.querySelector('.modal-bg');
  const modalContent = document.querySelector('.modal-content');

  modalBg.classList.toggle('hidden');
  modalContent.classList.toggle('hidden');
  menuBtn.classList.toggle('menu-open');
}

function setupCommonEventListeners() {
  menuBtn.addEventListener('click', toggleModal);
}

document.addEventListener('DOMContentLoaded', function () {
  setupCommonEventListeners();
});

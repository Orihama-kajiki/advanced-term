const menuBtn = document.querySelector('.menu-btn');
const modalBg1 = document.querySelector('.modal-bg1');
const modalBg2 = document.querySelector('.modal-bg-2');
const updateBtns = document.querySelectorAll('.update-btn');
const cancelButton = document.querySelector('.cancel-btn');
const formElement = document.querySelector('#update-reservation-form');
const numOfUsersInput = document.querySelector('#num_of_users');
const timeSelect = document.querySelector('#time');

window.toggleMenuModal = function () {
  const modalContent1 = document.querySelector('.modal-content1');

  modalBg1.classList.toggle('hidden');
  modalContent1.classList.toggle('hidden');
  menuBtn.classList.toggle('menu-open');
}

window.updateModal = async function (reservationId) {
  const formElement = document.querySelector('#update-reservation-form');
  formElement.setAttribute('data-url', `http://127.0.0.1:8000/reservations/${reservationId}`);
  const apiUrl = `http://127.0.0.1:8000/api/reservations/${reservationId}`;
  const modalBg2 = document.querySelector('.modal-bg-2');
  const modalContent2 = document.querySelector('.modal-content-2');
  const numOfUsersInput = document.querySelector('#num_of_users');
  const timeSelect = document.querySelector('#time');

  try {
    const response = await fetch(apiUrl, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      credentials: 'include',
    });

    if (response.ok) {
      const reservationData = await response.json();

      numOfUsersInput.value = reservationData.num_of_users;
      timeSelect.value = reservationData.time;

      const reservationDate = reservationData.date;
      const datepicker = document.querySelector('#datepicker');
      datepicker.value = reservationDate;
    } else {
      console.error('予約が見つかりませんでした');
    }
  } catch (error) {
    console.error('APIへのリクエスト中にエラーが発生しました:', error);
  }

  modalBg2.classList.toggle('hidden');
  modalContent2.classList.toggle('hidden');
  const reservationIdInput = document.querySelector('#reservation-id');
  reservationIdInput.value = reservationId;
}

async function updateReservation(reservationId) {
  const apiUrl = `http://127.0.0.1:8000/api/reservations/${reservationId}`;
  const modalContent2 = document.querySelector('.modal-content-2');
  const date = $("#datepicker").datepicker("getDate");
  const time = timeSelect.value.split(':');
  date.setHours(parseInt(time[0]));
  date.setMinutes(parseInt(time[1]));
  const data = {
    num_of_users: numOfUsersInput.value,
    datetime: date.toISOString(),
  };
  console.log('送信データ:', data);

  try {
    const response = await fetch(apiUrl, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        datetime: date.toISOString(),
        num_of_users: numOfUsersInput.value,
      }),
    });

    if (!response.ok) {
      throw new Error('APIへのリクエスト中にエラーが発生しました: ' + response.statusText);
    }

    const updatedReservation = await response.json();
    console.log('予約が更新されました:', updatedReservation);



    updateReservationDisplay(reservationId, updatedReservation);
  } catch (error) {
    console.error('APIへのリクエスト中にエラーが発生しました:', error);
  }
}

function addUpdateReservationListener() {
  const updateReservationButton = document.querySelector('#update-reservation-button');
  updateReservationButton.addEventListener('click', async () => {
    const reservationId = document.querySelector('#reservation-id').value;

    if (confirm('更新してもよろしいですか？')) {
      await updateReservation(reservationId);
    }
  });
}

function updateReservationDisplay(reservationId, updatedReservation) {
  const reservationDateElement = document.querySelector(`#reservation-${reservationId}-date`);
  const reservationTimeElement = document.querySelector(`#reservation-${reservationId}-time`);
  const reservationNumOfUsersElement = document.querySelector(`#reservation-${reservationId}-num-of-users`);

  const updatedDate = new Date(updatedReservation.start_at);

  reservationDateElement.textContent = updatedDate.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
  reservationTimeElement.textContent = updatedDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  reservationNumOfUsersElement.textContent = updatedReservation.num_of_users;
}

function updateFavoriteButtons() {
  const favoriteButtons = document.querySelectorAll('.favorite-btn');
  favoriteButtons.forEach(button => {
    const shopId = button.dataset.shopId;
    const isFavorited = window.favorites.some(shop => shop.id === parseInt(shopId, 10));
    const heartIcon = button.querySelector('.fa-heart');
    if (isFavorited) {
      heartIcon.classList.add('text-red-500');
    } else {
      heartIcon.classList.remove('text-red-500');
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateFavoriteButtons();
  addUpdateReservationListener();

  const changeReservationButtons = document.querySelectorAll('[id^="change-reservation-"]');
  changeReservationButtons.forEach(button => {
    button.addEventListener('click', () => {
      const reservationId = button.getAttribute('data-reservation-id');
      updateModal(reservationId);
    });
  });
});

function setupMenuEventListeners() {
  menuBtn.addEventListener('click', window.toggleMenuModal);
  modalBg1.addEventListener('click', () => window.toggleMenuModal());
}
setupMenuEventListeners();

function setupCancelEventListener() {
  cancelButton.addEventListener('click', () => window.updateModal());
}
setupCancelEventListener();
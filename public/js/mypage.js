const menuBtn = document.querySelector('.menu-btn');
const modalBg1 = document.querySelector('.modal-bg1');
const modalContent1 = document.querySelector('.modal-content1');
const modalBg2 = document.querySelector('.modal-bg-2');
const modalContent2 = document.querySelector('.modal-content-2');
const updateBtns = document.querySelectorAll('.update-btn');
const cancelButton = document.querySelector('.cancel-btn');
const formElement = document.querySelector('#update-reservation-form');
const numOfUsersInput = document.querySelector('#num_of_users');
const timeSelect = document.querySelector('#time');
const qrCodeModal = document.getElementById('qr-code-modal');
const qrCodeImage = document.querySelector('#qr-code-image');
const favoritesDiv = document.getElementById('favorites');

window.toggleMenuModal = function () {
  modalBg1.classList.toggle('hidden');
  modalContent1.classList.toggle('hidden');
  menuBtn.classList.toggle('menu-open');
}

let openModalButton = document.querySelector('.open-modal-3');
if (openModalButton) {
    openModalButton.addEventListener('click', function() {
      qrCodeModal.classList.remove('hidden');
    });
}

document.getElementById('close-modal').addEventListener('click', function() {
    qrCodeModal.classList.add('hidden');
});

function changeTab(evt, tabName) {
  let i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

function handleFavoriteButtonClick(event) {
  const clickedElement = event.target;
  const favoriteButton = clickedElement.closest('.favorite-btn');

  if (!favoriteButton) {
    return;
  }

  const shopId = favoriteButton.dataset.shopId;

  if (isUserLoggedIn) {
    if (isProcessingFavorite) {
      console.log('Already processing favorite, returning early');
      return;
    }

    isProcessingFavorite = true;
    toggleFavorite(shopId)
      .finally(() => {
        isProcessingFavorite = false;
      });
  } else {
    window.location.href = '/login';
  }
}

document.querySelector('#update-reservation-form').addEventListener('submit', function() {
  const dateValue = document.querySelector('#datepicker').value;
  const timeValue = document.querySelector('#time').value;
  console.log(`Frontend date value: ${dateValue}`);
  console.log(`Frontend time value: ${timeValue}`);
});

cancelButton.addEventListener('click', function () {
  modalBg2.classList.add('hidden');
  modalContent2.classList.add('hidden');
});

window.updateModal = async function (reservationId) {
  formElement.setAttribute('action', `/reservations/${reservationId}`);

  try {
    const response = await fetch(`/api/reservations/${reservationId}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

      modalBg2.classList.remove('hidden');
      modalContent2.classList.remove('hidden');
    } else {
      console.error('予約が見つかりませんでした');
    }
  } catch (error) {
    console.error('APIへのリクエスト中にエラーが発生しました:', error);
  }
}

function updateFavorites() {
  while (favoritesDiv.firstChild) {
    favoritesDiv.removeChild(favoritesDiv.firstChild);
  }

  for (const shop of window.favorites) {
    const shopDiv = document.createElement('div');
    shopDiv.className = "bg-white rounded-lg overflow-hidden shadow-3xl";
    shopDiv.innerHTML = `
      <img src="${shop.image_url}" class="w-full h-48 object-cover object-center">
      <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900">${shop.name}</h3>
        <div class="flex items-center mt-2 text-gray-700">
          <span class="text-sm font-bold">#${shop.area?.name || 'N/A'}</span>
          <span class="text-sm font-bold">#${shop.genre?.name || 'N/A'}</span>
        </div>
        <div class="flex items-center mt-2 text-gray-700">
          <a href="/detail/${shop.id}" class="bg-blue-600 text-white px-5 py-1 rounded-lg tracking-widest text-base">詳しくみる</a>
          <button class="favorite-btn text-3xl text-gray-200 ml-auto p-2" data-shop-id="${shop.id}" onclick="toggleFavorite(${shop.id})">
            <i class="fa fa-heart ${shop.isFavorited ? 'text-red-500' : ''}"></i>
          </button>
        </div>
      </div>
    `;
    favoritesDiv.appendChild(shopDiv);
  }
}

updateFavorites();


window.addEventListener('load', async () => {
  const response = await fetch('/api/favorites');
  if (response.ok) {
    window.favorites = await response.json();
    updateFavorites();
    updateFavoriteButtons();
  } else {
    console.error('Failed to fetch favorites');
  }
});

async function toggleFavorite(shopId) {
  const favoriteButton = document.querySelector(`.favorite-btn[data-shop-id="${shopId}"]`);
  favoriteButton.disabled = true;

  const isFavorited = window.favorites.some(favorite => favorite.id === parseInt(shopId, 10));
  
  try {
    const response = await fetch(`/api/favorites/${shopId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ shop_id: shopId })
    });

    if (response.ok) {
      if (isFavorited) {
        window.favorites = window.favorites.filter(favorite => favorite.id !== parseInt(shopId, 10));
      } else {
        const shop = await response.json();
        window.favorites.push(shop);
      }

      updateFavorites();
      updateFavoriteButtons();
      
      const heartIcon = favoriteButton.querySelector('.fa-heart');
      if (isFavorited) {
        heartIcon.classList.remove('text-red-500');
      } else {
        heartIcon.classList.add('text-red-500');
      }
    } else {
      throw new Error('Error toggling the favorite status');
    }
  } finally {
    favoriteButton.disabled = false;
  }
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

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".tablink").click();
  updateFavoriteButtons();

  const changeReservationButtons = document.querySelectorAll('[id^="change-reservation-"]');
  changeReservationButtons.forEach(button => {
    button.addEventListener('click', () => {
      const reservationId = button.getAttribute('data-reservation-id');
      window.updateModal(reservationId);
    });
  });

  const openModalButton = document.querySelector('.open-modal-3');
  if (openModalButton) {
    openModalButton.addEventListener('click', function () {
      qrCodeModal.classList.remove('hidden');
    });
  }

  const generateQrCodeButtons = document.querySelectorAll('.open-modal-3');
  generateQrCodeButtons.forEach(button => {
    button.addEventListener('click', function () {
      const reservationId = this.getAttribute('data-reservation-id');
      const url = `/shop-owner/reservation-detail/${reservationId}/qr-code`;

      fetch(url)
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('QRコードの生成に失敗しました。');
          }
        })
        .then(data => {
          if (data.qr_code_url) {
            qrCodeImage.src = data.qr_code_url;
            qrCodeImage.style.display = 'block';
          } else {
            console.error('QRコードのURLが見つかりません。');
          }
        })
        .catch(error => {
          console.error(error);
        });

      qrCodeModal.classList.remove('hidden');
    });
  });
});

function setupMenuEventListeners() {
  menuBtn.addEventListener('click', window.toggleMenuModal);
  modalBg1.addEventListener('click', () => window.toggleMenuModal());
}
setupMenuEventListeners();


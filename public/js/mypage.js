const menuBtn = document.querySelector('.menu-btn');
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
});

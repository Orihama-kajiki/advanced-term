const menuBtn = document.querySelector('.menu-btn');
const areaSelect = document.getElementById('areaSelect');
const genreSelect = document.getElementById('genreSelect');
const searchInput = document.getElementById('searchInput');
const searchIcon = document.querySelector('#search-icon');
const searchResults = document.getElementById('search-results');
let isProcessingFavorite = false;

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

function filterShops(shops, selectedArea, selectedGenre, searchShopName) {
  return shops.filter((shop) => {
    const isAreaMatch = !selectedArea || shop.area_id === parseInt(selectedArea);
    const isGenreMatch = !selectedGenre || shop.genre_id === parseInt(selectedGenre);
    const isShopNameMatch = !searchShopName || shop.name.toLowerCase().includes(searchShopName.toLowerCase());

    return isAreaMatch && isGenreMatch && isShopNameMatch;
  });
}

function createShopCardHTML(shop, index) {
  const areaName = shop.area ? shop.area.name : '';
  const genreName = shop.genre ? shop.genre.name : '';

  return `
    <div class="max-w-[380px] w-full mb-8 mr-4 rounded-lg shadow-3xl">
      <div class="bg-white rounded-lg overflow-hidden">
        <img src="${shop.image_url}" alt="店舗画像" class="w-full h-48 object-cover object-center">
        <div class="p-4">
          <h3 class="text-lg font-bold text-gray-900">${shop.name}</h3>
          <div class="flex items-center mt-2 text-gray-700">
            <span class="text-sm font-bold">#${areaName}</span>
            <span class="text-sm font-bold">#${genreName}</span>
          </div>
          <div class="flex items-center mt-2 text-gray-700">
            <a href="/detail/${shop.id}" class="bg-blue-600 text-white px-5 py-1 rounded-lg tracking-widest text-base">詳しくみる</a>
            <button id="favorite-btn-${index}" class="favorite-btn text-3xl text-gray-200 ml-auto p-2" data-shop-id="${shop.id}">
              <i class="fa-solid fa-heart"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderSearchResults(filteredShops) {
  const searchResultsContainer = document.getElementById('search-results');
  const resultsHTML = filteredShops.map((shop, index) => createShopCardHTML(shop, index)).join('');
  searchResultsContainer.innerHTML = `<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">${resultsHTML}</div>`;
}

function updateSearchResults() {
  const selectedArea = areaSelect.value;
  const selectedGenre = genreSelect.value;
  const searchShopName = searchInput.value;

  const filteredShops = filterShops(shops, selectedArea, selectedGenre, searchShopName);
  renderSearchResults(filteredShops);
  updateFavoriteButtons();
}

document.addEventListener('DOMContentLoaded', function () {
  const currentUrl = window.location.href;
  if (currentUrl.includes('/index') || currentUrl.endsWith('/')) {
    renderSearchResults(window.shops);
    updateSearchResults();
  }
});

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
    favoriteShop(shopId)
      .finally(() => {
        isProcessingFavorite = false;
      });
  } else {
    window.location.href = '/login';
  }
}

async function favoriteShop(shopId) {
  const favoriteButton = document.querySelector(`.favorite-btn[data-shop-id="${shopId}"]`);
  favoriteButton.disabled = true;

  try {
    const response = await fetch('/favorite', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ shop_id: shopId })
    });

    if (response.ok) {
      const heartIcon = favoriteButton.querySelector('.fa-heart');
      heartIcon.classList.toggle('text-red-500');
    } else {
      throw new Error('Error liking the shop');
    }
  } finally {
    favoriteButton.disabled = false;
  }
}

function updateFavoriteButtons() {
  const favoriteButtons = document.querySelectorAll('.favorite-btn');
  favoriteButtons.forEach(button => {
    const shopId = button.dataset.shopId;
    const isFavorited = window.favorites.some(favorite => favorite.id === parseInt(shopId, 10));
    const heartIcon = button.querySelector('.fa-heart');
    if (isFavorited) {
      heartIcon.classList.add('text-red-500');
    } else {
      heartIcon.classList.remove('text-red-500');
    }
  });
}

function addFavoriteButtonsEventListener() {
  document.addEventListener('click', handleFavoriteButtonClick, true);
}

function setupIndexEventListeners() {
  menuBtn.addEventListener('click', toggleModal);
  areaSelect.addEventListener('change', updateSearchResults);
  genreSelect.addEventListener('change', updateSearchResults);
  searchInput.addEventListener('input', updateSearchResults);
  searchIcon.addEventListener('click', updateSearchResults);

  addFavoriteButtonsEventListener();
}

document.addEventListener('DOMContentLoaded', function () {
  const currentUrl = window.location.href;

  const isIndexPage = currentUrl.includes('/index') || currentUrl.endsWith('/');

  if (isIndexPage) {
    renderSearchResults(window.shops);
    updateSearchResults();
    setupIndexEventListeners();
  }

  setupCommonEventListeners();
  updateFavoriteButtons();
});

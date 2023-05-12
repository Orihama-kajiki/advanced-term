const tabs = document.getElementsByClassName('tab-menu__item');
for (let i = 0; i < tabs.length; i++) {
  tabs[i].addEventListener('click', tabSwitch);
}
function tabSwitch() {
  document.getElementsByClassName('active')[0].classList.remove('active');
  this.classList.add('active');
  document.getElementsByClassName('show')[0].classList.remove('show');
  const arrayTabs = Array.prototype.slice.call(tabs);
  const index = arrayTabs.indexOf(this);
  document.getElementsByClassName('tab-content__item')[index].classList.add('show');
};

document.addEventListener("DOMContentLoaded", function () {
  const tabMenu = document.querySelector(".tab-menu");
  const tabContent = document.querySelector(".tab-content");

  shopsData.forEach((shop, index) => {
    const tabMenuItem = document.createElement("li");
    tabMenuItem.textContent = shop.name;
    tabMenuItem.classList.add("tab-menu__item", "font-bold");
    if (index === 0) {
      tabMenuItem.classList.add("active");
    }
    tabMenu.appendChild(tabMenuItem);

    const tabContentItem = document.createElement("div");
    tabContentItem.classList.add("tab-content__item");
    if (index === 0) {
      tabContentItem.classList.add("show");
    }

    const reservations = reservationsData[shop.id] || [];
    if (reservations.length === 0) {
      const noReservationMessage = `
        <p class="text-2xl font-semibold">現在予約はありません。</p>
      `;
      tabContentItem.insertAdjacentHTML('beforeend', noReservationMessage);
    } else {
      reservations.forEach((reservation) => {
        const reservationDetailUrl = reservationDetailUrlTemplate.replace('RESERVATION_ID', reservation.id);
        const reservationDetail = `
        <div class="reservation-detail flex justify-between items-center mb-4 text-2xl font-semibold">
          <div>
            <span class="mr-4">ユーザー名: ${reservation.user.name}</span>
            <span class="mr-4">人数: ${reservation.num_of_users}</span>
            <span >日時: ${reservation.start_at}</span>
          </div>
          <div>
            <a href="${reservationDetailUrl}" class="bg-blue-500 text-white rounded px-3 py-1 mr-2 inline-block justify-center">
              詳細
            </a>
            <button class="bg-red-500 text-white rounded px-3 py-1" onclick="deleteReservation(${reservation.id})">削除</button>
          </div>
        </div>
        `;
        tabContentItem.insertAdjacentHTML('beforeend', reservationDetail);
      });
    }

    tabContent.appendChild(tabContentItem);

    tabMenuItem.addEventListener("click", function () {
      const activeTabMenu = document.querySelector(".tab-menu__item.active");
      activeTabMenu.classList.remove("active");
      tabMenuItem.classList.add("active");

      const activeTabContent = document.querySelector(".tab-content__item.show");
      activeTabContent.classList.remove("show");
      tabContentItem.classList.add("show");
    });
  });
});

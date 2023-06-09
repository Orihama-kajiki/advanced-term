let courseMenuIndex = 1;
let addCourseMenuButton = document.getElementById('add_course_menu');
let deletedCourseMenuIds = [];

if (addCourseMenuButton !== null) {
  addCourseMenuButton.addEventListener('click', function () {
    courseMenuIndex++;
    const courseMenu = document.createElement('div');
    courseMenu.classList.add('course_menu');
    courseMenu.innerHTML = `
      <div class="flex mb-2">
        <label for="course_name_${courseMenuIndex}" class="block text-base">名前:</label>
        <input type="text" name="course_name[]" id="course_name_${courseMenuIndex}" class="w-1/4"/>
      </div>
      <div class="flex mb-1">
        <label for="course_price_${courseMenuIndex}" class="block text-base">価格:</label>
        <input type="text" name="course_price[]" id="course_price_${courseMenuIndex}" class="w-1/4"/>
      </div>
      <div>
        <label for="course_description_${courseMenuIndex}" class="block text-base">説明</label>
        <textarea name="course_description[]" id="course_description_${courseMenuIndex}" class="w-full text-base resize-none" rows="5"></textarea>
      </div>
      <button type="button" class="delete_course_menu border border-black mb-4 px-2">コースを削除</button>
    `;
    addCourseMenuButton.parentNode.insertBefore(courseMenu, addCourseMenuButton);

    courseMenu.querySelector('.delete_course_menu').addEventListener('click', function () {
      courseMenu.remove();
    });
  });
}

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

document.querySelectorAll('.delete_course_menu').forEach((button) => {
  button.addEventListener('click', function () {
    const courseMenuElement = button.parentElement;
    const courseMenuId = courseMenuElement.getAttribute('data-id');

    if (courseMenuId) {
      deletedCourseMenuIds.push(courseMenuId);
    }

    courseMenuElement.remove();
  });
});

const form = document.querySelector('form');
if (form !== null) {
  form.addEventListener('submit', function (e) {
    deletedCourseMenuIds.forEach((id) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'deleted_course_menu_ids[]';
      input.value = id;
      this.appendChild(input);
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const tabMenu = document.querySelector(".tab-menu");
  const tabContent = document.querySelector(".tab-content");

  if (
    typeof shopsData !== 'undefined' && 
    typeof reservationsData !== 'undefined' && 
    typeof reservationDetailUrlTemplate !== 'undefined' && 
    tabMenu !== null &&
    tabContent !== null
  ) {
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
            <div id="reservation-${reservation.id}" class="reservation-detail flex justify-between items-center mb-4 text-2xl font-semibold">
              <div>
                <span class="mr-4">ユーザー名: ${reservation.user.name}</span>
                <span class="mr-4">人数: ${reservation.num_of_users}</span>
                <span class="mr-4">日時: ${reservation.start_at}</span>
                <span>コースメニュー: ${reservation.course_menu ? reservation.course_menu.name : 'コースメニューは選択されていません'}</span>
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
  }
});

function deleteReservation(id) {
  const url = `/shop-owner/reservation-delete/${id}`; 

  const options = {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
  };

  fetch(url, options)
    .then(response => response.json())
    .then(json => {
        console.log('Success:', json);
        const reservationItem = document.querySelector(`#reservation-${id}`);
        const tabContentItem = reservationItem.closest('.tab-content__item');


        reservationItem.remove();

        const remainingReservations = tabContentItem.querySelectorAll('.reservation-detail');
        if (remainingReservations.length === 0) {
            const noReservationMessage = `
                <p class="text-2xl font-semibold">現在予約はありません。</p>
            `;
            tabContentItem.insertAdjacentHTML('beforeend', noReservationMessage);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

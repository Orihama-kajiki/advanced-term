const menuBtn = document.querySelector('.menu-btn');

var stars = document.getElementsByClassName("star");
var clicked = false;
document.addEventListener("DOMContentLoaded", () => {
  for (let i = 0; i < stars.length; i++) {
    stars[i].addEventListener(
      "mouseover",
      () => {
        if (!clicked) {
          for (let j = 0; j <= i; j++) {
            stars[j].style.color = "#ffd700";
          }
        }
      },
      false
    );

    stars[i].addEventListener(
      "mouseout",
      () => {
        if (!clicked) {
          for (let j = 0; j < stars.length; j++) {
            stars[j].style.color = "#a09a9a";
          }
        }
      },
      false
    );

    stars[i].addEventListener(
      "click",
      () => {
        clicked = true;
        for (let j = 0; j <= i; j++) {
          stars[j].style.color = "#ffd700";
        }
        for (let j = i + 1; j < stars.length; j++) {
          stars[j].style.color = "#a09a9a";
        }
        document.getElementById("rating").value = stars[i].getAttribute("data-rating");
      },
      false
    );
  }
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
    setupCommonEventListeners();

const visibleElements = $(".some-class:visible");
const menuBtn = document.querySelector('.menu-btn');
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

$(document).ready(function () {
  $("#calendar").datepicker({
    dateFormat: "yy-mm-dd",
    minDate: 0,
    beforeShow: function (input, inst) {
      setTimeout(function () {
        inst.dpDiv.addClass("custom-datepicker");
      }, 0);
    },
  });
  
  $(".carender-icon").on("click", function () {
    $("#calendar").datepicker("show");
  });

  $("#calendar").on("change", function() {
    const selectedDate = $(this).val();
    $("#selected-date").text(selectedDate);
  });

  $("#time").on("change", function() {
    const selectedTime = $(this).val();
    $("#selected-time").text(selectedTime);
  });

  $("#num_of_users").on("change", function() {
    const selectedNumber = $(this).val() + "人";
    $("#selected-number").text(selectedNumber);
  });

  $("#course_menu_id").on("change", function() {
    const selectedCourse = $(this).find("option:checked").text();
    $("#selected-course").text(selectedCourse);
  });

  setupCommonEventListeners();
});

window.toggleModal = function () {
  const modalBg = document.querySelector('.modal-bg');
  const modalContent = document.querySelector('.modal-content');

  modalBg.classList.toggle('hidden');
  modalContent.classList.toggle('hidden');
  menuBtn.classList.toggle('menu-open');
}

function setReservationValues(numOfUsers, startAt, courseMenuId) {
  document.getElementById("selected-number").innerText = numOfUsers;
  const numOfUsersInput = document.getElementById("num_of_users");
  const startAtInput = document.getElementById("start_at");
  const courseMenuIdInput = document.querySelector("select[name='course_menu_id']");

  numOfUsersInput.value = numOfUsers;
  startAtInput.value = startAt;
  courseMenuIdInput.value = courseMenuId;
}

document.getElementById("reservation-form-btn").addEventListener("click", async function (event) {
  const userId = document.querySelector("input[name='user_id']").value;
  const isAuthenticated = !!userId;
  const isEmailVerified = "{{ Auth::check() && Auth::user()->hasVerifiedEmail() }}";

  if (!isAuthenticated) {
    alert('ログインが必要です。');
    event.preventDefault();
    window.location.href = "/login"; 
    return;
  }
  
  const numOfUsers = document.getElementById("num_of_users").value;
  const selectedDate = document.getElementById("calendar").value;
  const selectedTime = document.getElementById("time").value;
  const startAt = selectedDate + " " + selectedTime;
  const courseMenuId = document.querySelector("select[name='course_menu_id']").value;
  const shopId = document.querySelector("input[name='shop_id']").value;

  setReservationValues(numOfUsers, startAt, courseMenuId);
  event.preventDefault();

  if (courseMenuId) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const response = await fetch('/api/create-checkout-session', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
      shop_id: shopId,
      user_id: userId,
      num_of_users: numOfUsers,
      start_at: startAt,
      course_menu_id: courseMenuId,
    })
  });

  const data = await response.json();

  if (data.error) {
    alert(data.error);
    return;
  }
  window.location.href = data.url;
  }   else {
    document.getElementById('reservation-form').submit();
  }
});

function setupCommonEventListeners() {
  menuBtn.addEventListener('click', toggleModal);
}

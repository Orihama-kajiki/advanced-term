const menuBtn = document.querySelector('.menu-btn');
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

    $("#number").on("change", function() {
        const selectedNumber = $(this).val() + "人";
        $("#selected-number").text(selectedNumber);
    });
    document.getElementById("reservation-form-btn").addEventListener("submit", function (event) {
        event.preventDefault();

        const numOfUsers = document.getElementById("number").value;
        const selectedDate = document.getElementById("calendar").value;
        const selectedTime = document.getElementById("time").value;
        const startAt = selectedDate + " " + selectedTime;

        setReservationValues(numOfUsers, startAt);
        document.getElementById("reservation-form-btn").submit();
    });
    setupCommonEventListeners();
});
function setReservationValues(num_of_users, start_at) {
    const numOfUsersInput = document.getElementById("num_of_users");
    const startAtInput = document.getElementById("start_at");

    numOfUsersInput.value = num_of_users;
    startAtInput.value = start_at;
}
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

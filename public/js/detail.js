/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/detail.js ***!
  \********************************/
$(document).ready(function () {
  $("#calendar").datepicker({
    dateFormat: "yy-mm-dd",
    minDate: 0,
    beforeShow: function beforeShow(input, inst) {
      setTimeout(function () {
        inst.dpDiv.addClass("custom-datepicker");
      }, 0);
    }
  });
  $(".carender-icon").on("click", function () {
    $("#calendar").datepicker("show");
  });
  $("#calendar").on("change", function () {
    var selectedDate = $(this).val();
    $("#selected-date").text(selectedDate);
  });
  $("#time").on("change", function () {
    var selectedTime = $(this).val();
    $("#selected-time").text(selectedTime);
  });
  $("#number").on("change", function () {
    var selectedNumber = $(this).val() + "人";
    $("#selected-number").text(selectedNumber);
  });
  document.getElementById("reservation-form-btn").addEventListener("submit", function (event) {
    event.preventDefault();
    var numOfUsers = document.getElementById("number").value;
    var selectedDate = document.getElementById("calendar").value;
    var selectedTime = document.getElementById("time").value;
    var startAt = selectedDate + " " + selectedTime;
    setReservationValues(numOfUsers, startAt);
    document.getElementById("reservation-form-btn").submit();
  });
});
function setReservationValues(num_of_users, start_at) {
  var numOfUsersInput = document.getElementById("num_of_users");
  var startAtInput = document.getElementById("start_at");
  numOfUsersInput.value = num_of_users;
  startAtInput.value = start_at;
}
/******/ })()
;
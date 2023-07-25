// login.js

document.getElementById("loginForm").addEventListener("submit", function () {
  var userTypeSelect = document.getElementById("userTypeSelect");
  var userType = userTypeSelect.value;
  document.getElementById("userType").value = userType;
});

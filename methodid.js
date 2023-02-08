function showPopup() {
  document.getElementById("popup").style.display = "block";
}

function closePopup() {
  document.getElementById("popup").style.display = "none";
}

document.getElementById("cancel").addEventListener("click", showPopup);
document.getElementById("yes-btn").addEventListener("click", function () {
  document.getElementById("file").value = "";
  closePopup();
});
document.getElementById("no-btn").addEventListener("click", closePopup);
document
  .getElementsByClassName("close")[0]
  .addEventListener("click", closePopup);

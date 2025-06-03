console.log("Class Schedule page loaded.");

const floatingButton = document.querySelector(".floating-button");
const scheduleForm = document.getElementById("schedule-form");
const cancelBtn = document.getElementById("cancelBtn");
const classForm = document.getElementById("classForm");
const scheduleList = document.getElementById("schedule-list");

// Tampilkan form saat tombol + diklik
floatingButton.addEventListener("click", () => {
  scheduleForm.classList.remove("hidden");
});

// Sembunyikan form saat tombol batal diklik
cancelBtn.addEventListener("click", () => {
  scheduleForm.classList.add("hidden");
});

// Simpan jadwal baru
classForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(classForm);
  const hari = formData.get("hari");
  const waktu = formData.get("waktu");
  const matkul = formData.get("matkul");
  const tempat = formData.get("tempat");

  const newItem = document.createElement("div");
  newItem.classList.add("schedule-item");
  newItem.innerHTML = `
    <strong>${hari}</strong><br/>
    ${waktu}<br/>
    ${matkul}<br/>
    ${tempat}
  `;

  scheduleList.classList.remove("empty");
  scheduleList.innerHTML = "";
  scheduleList.appendChild(newItem);

  classForm.reset();
  scheduleForm.classList.add("hidden");
});
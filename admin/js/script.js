document.addEventListener("DOMContentLoaded", () => {
  // --- Sidebar toggle ---
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("sidebarToggle");
  const contentArea = document.querySelector(".flex-grow-1");

  if (sidebar && toggleBtn && contentArea) {
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      contentArea.classList.toggle("expanded");
    });
  }

  // --- Profile Edit Logic ---
  const editBtn = document.getElementById("editBtn");
  const saveBtn = document.getElementById("saveBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const formInputs = document.querySelectorAll("#profileForm input");

  if (editBtn && saveBtn && cancelBtn && formInputs.length > 0) {
    editBtn.addEventListener("click", () => {
      formInputs.forEach(input => input.removeAttribute("readonly"));
      editBtn.classList.add("d-none");
      saveBtn.classList.remove("d-none");
      cancelBtn.classList.remove("d-none");
    });

    cancelBtn.addEventListener("click", () => {
      formInputs.forEach(input => {
        input.setAttribute("readonly", true);
        if (input.id === "name") input.value = "Prashant Madarkhandi";
        if (input.id === "email") input.value = "prashantsir2008@gmail.com";
      });
      editBtn.classList.remove("d-none");
      saveBtn.classList.add("d-none");
      cancelBtn.classList.add("d-none");
    });

    const form = document.getElementById("profileForm");
    if (form) {
      form.addEventListener("submit", e => {
        e.preventDefault();
        formInputs.forEach(input => input.setAttribute("readonly", true));
        editBtn.classList.remove("d-none");
        saveBtn.classList.add("d-none");
        cancelBtn.classList.add("d-none");
        alert("Profile updated successfully!");
      });
    }
  }

  // --- Upload Profile Picture Logic ---
  const uploadBtn = document.getElementById("uploadBtn");
  const uploadInput = document.getElementById("uploadInput");
  const profileImage = document.getElementById("profileImage");
  const removeBtn = document.getElementById("removeBtn");

  const defaultImage = "https://www.gravatar.com/avatar?d=mp";

  if (uploadBtn && uploadInput && profileImage && removeBtn) {
    uploadBtn.addEventListener("click", () => {
      uploadInput.click();
    });

    uploadInput.addEventListener("change", () => {
      const file = uploadInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          profileImage.src = reader.result;
        };
        reader.readAsDataURL(file);
      }
    });

    removeBtn.addEventListener("click", () => {
      profileImage.src = defaultImage;
      uploadInput.value = "";
    });
  }
});

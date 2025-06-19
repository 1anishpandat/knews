document.addEventListener("DOMContentLoaded", () => {
    // Old d-none toggle (optional if you want a full hide on double click)
    const toggleBtnOld = document.querySelector(".btn-outline-secondary");
    const sidebarOld = document.querySelector(".sidebar");
  
    if (toggleBtnOld && sidebarOld) {
      toggleBtnOld.addEventListener("dblclick", () => {
        sidebarOld.classList.toggle("d-none");
      });
    }
  
    // Slide (collapse) toggle
    const toggleBtnNew = document.getElementById("sidebarToggle");
    const sidebarNew = document.getElementById("sidebar");
    const contentArea = document.querySelector(".flex-grow-1");
  
    if (toggleBtnNew && sidebarNew) {
      toggleBtnNew.addEventListener("click", () => {
        sidebarNew.classList.toggle("collapsed");
        contentArea.classList.toggle("expanded");
      });
    }
  });

  document.addEventListener("DOMContentLoaded", () => {
    const editBtn = document.getElementById("editBtn");
    const saveBtn = document.getElementById("saveBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const formInputs = document.querySelectorAll("#profileForm input");
  
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
  
    document.getElementById("profileForm").addEventListener("submit", e => {
      e.preventDefault();
      // Save logic here (e.g., send data via AJAX to PHP)
      formInputs.forEach(input => input.setAttribute("readonly", true));
      editBtn.classList.remove("d-none");
      saveBtn.classList.add("d-none");
      cancelBtn.classList.add("d-none");
      alert("Profile updated successfully!");
    });
  });

  document.addEventListener("DOMContentLoaded", () => {
    const uploadBtn = document.getElementById("uploadBtn");
    const uploadInput = document.getElementById("uploadInput");
    const profileImage = document.getElementById("profileImage");
  
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
  });
  
  document.addEventListener("DOMContentLoaded", () => {
    const uploadBtn = document.getElementById("uploadBtn");
    const uploadInput = document.getElementById("uploadInput");
    const profileImage = document.getElementById("profileImage");
    const removeBtn = document.getElementById("removeBtn");
  
    const defaultImage = "https://www.gravatar.com/avatar?d=mp";
  
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
      uploadInput.value = ""; // reset file input
    });
  });
  



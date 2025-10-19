// --- Redirect if not logged in
if (localStorage.getItem("isLoggedIn") !== "true") {
  window.location.href = "index.html";
}

// --- Initialize data
let items = JSON.parse(localStorage.getItem("lostFoundItems")) || [];

// --- Display items
function displayItems(filteredItems = items) {
  const container = document.getElementById("itemsContainer");
  if (!container) return;

  container.innerHTML = "";

  if (filteredItems.length === 0) {
    container.innerHTML = "<p>No reports yet. Be the first to add one!</p>";
    return;
  }

  filteredItems.forEach((item, index) => {
    const card = document.createElement("div");
    card.className = "item-card";
    card.innerHTML = `
      <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.title}">
      <div class="item-info">
        <h3>${item.title}</h3>
        <p><strong>Type:</strong> ${item.type}</p>
        <p><strong>Description:</strong>${item.description}</p>
        <p><strong>Location:</strong> ${item.location}</p>
        <p><strong>Contact on:</strong> 
          ${item.email ? `<a href="mailto:${item.email}">${item.email}</a>` : "No email provided"}
        </p>
        <p><small>Reported on: ${item.date}</small></p>

        <div class="btn-group">
          <button class="edit-btn">Edit</button>
          <button class="delete-btn">Delete</button>
        </div>
      </div>
    `;

    // Delete functionality
    card.querySelector(".delete-btn").addEventListener("click", () => deleteItem(index));

    // Edit functionality
    card.querySelector(".edit-btn").addEventListener("click", () => editItem(index));

    container.appendChild(card);
  });
}

// --- Delete item
function deleteItem(index) {
  if (confirm("Are you sure you want to delete this report?")) {
    items.splice(index, 1);
    localStorage.setItem("lostFoundItems", JSON.stringify(items));
    displayItems();
  }
}

// --- Edit item
function editItem(index) {
  localStorage.setItem("editIndex", index);
  window.location.href = "report.html";
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("reportForm");

  if (form) {
    const editIndex = localStorage.getItem("editIndex");

    // --- If editing existing post
    if (editIndex !== null) {
      const item = items[editIndex];
      document.getElementById("title").value = item.title;
      document.getElementById("type").value = item.type;
      document.getElementById("description").value = item.description;
      document.getElementById("location").value = item.location;
      document.getElementById("email").value = item.email || ""; // ✅ Pre-fill email if editing

      const submitBtn = form.querySelector("button[type='submit']");
      submitBtn.textContent = "Update Report";
    }

    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const title = document.getElementById("title").value.trim();
      const type = document.getElementById("type").value;
      const description = document.getElementById("description").value.trim();
      const location = document.getElementById("location").value.trim();
      const email = document.getElementById("email").value.trim(); // ✅ Collect email
      const fileInput = document.querySelector('input[type="file"]');
      const imageFile = fileInput.files[0];

      // --- Check for duplicate before saving
      const isDuplicate = items.some((item, i) => {
        return (
          i != editIndex &&
          item.title.toLowerCase() === title.toLowerCase() &&
          item.type === type &&
          item.location.toLowerCase() === location.toLowerCase()
        );
      });

      if (isDuplicate) {
        alert("⚠️ A similar report already exists. Please modify your entry.");
        return;
      }

      // --- Save item (with image support)
      const saveData = (image) => {
        const newItem = {
          title,
          type,
          description,
          location,
          email, // ✅ Save email here
          image: image || (editIndex !== null ? items[editIndex].image : ""),
          date: new Date().toLocaleString(),
        };

        if (editIndex !== null) {
          items[editIndex] = newItem;
          localStorage.removeItem("editIndex");
        } else {
          items.push(newItem);
        }

        localStorage.setItem("lostFoundItems", JSON.stringify(items));
        window.location.href = "home.html";
      };

      if (imageFile) {
        const reader = new FileReader();
        reader.onload = (event) => saveData(event.target.result);
        reader.readAsDataURL(imageFile);
      } else {
        saveData();
      }
    });
  } else {
    // --- For homepage
    displayItems();

    // --- SEARCH FUNCTIONALITY
    const searchInput = document.getElementById("searchInput");
    const searchBtn = document.getElementById("searchBtn");

    if (searchInput && searchBtn) {
      // Search button click
      searchBtn.addEventListener("click", () => {
        const searchTerm = searchInput.value.toLowerCase();
        const filtered = items.filter(
          (item) =>
            item.title.toLowerCase().includes(searchTerm) ||
            item.type.toLowerCase().includes(searchTerm) ||
            item.description.toLowerCase().includes(searchTerm) ||
            item.location.toLowerCase().includes(searchTerm)
        );
        displayItems(filtered);
      });

      // Live search typing
      searchInput.addEventListener("keyup", () => {
        const searchTerm = searchInput.value.toLowerCase();
        const filtered = items.filter(
          (item) =>
            item.title.toLowerCase().includes(searchTerm) ||
            item.type.toLowerCase().includes(searchTerm) ||
            item.description.toLowerCase().includes(searchTerm) ||
            item.location.toLowerCase().includes(searchTerm)
        );
        displayItems(filtered);
      });
    }

    // --- USERNAME & LOGOUT
    const usernameDisplay = document.getElementById("username");
    const logoutBtn = document.getElementById("logoutBtn");
    const storedName = localStorage.getItem("username");

    if (usernameDisplay && storedName) {
      usernameDisplay.textContent = storedName;
    }

    if (logoutBtn) {
      logoutBtn.addEventListener("click", logout);
    }
  }
});

// --- Logout
function logout() {
  localStorage.removeItem("isLoggedIn");
  localStorage.removeItem("username");
  window.location.href = "index.html";
}

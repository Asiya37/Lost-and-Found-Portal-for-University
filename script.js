// Redirect if not logged in
if (localStorage.getItem("isLoggedIn") !== "true") {
  window.location.href = "login.html";
}


// --- Initialize data
let items = JSON.parse(localStorage.getItem("lostFoundItems")) || [];

// --- Function to display items
function displayItems() {
  const container = document.getElementById("itemsContainer");
  if (!container) return;

  container.innerHTML = "";

  if (items.length === 0) {
    container.innerHTML = "<p>No reports yet. Be the first to add one!</p>";
    return;
  }

  items.forEach(item => {
    const card = document.createElement("div");
    card.className = "item-card";
    card.innerHTML = `
      <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.title}">
      <div class="item-info">
        <h3>${item.title}</h3>
        <p><strong>Type:</strong> ${item.type}</p>
        <p>${item.description}</p>
        <p><strong>Location:</strong> ${item.location}</p>
        <p><small>Reported on: ${item.date}</small></p>
      </div>
    `;
    container.appendChild(card);
  });
}

// --- Delete item
function deleteItem(index) {
    if (confirm("Are you sure you want to delete this report?")) {
      items.splice(index, 1);
      localStorage.setItem("lostFoundItems", JSON.stringify(items));
      displayItems(); // Refresh list
    }
  }
  
  // --- Search feature
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const query = e.target.value.toLowerCase();
      const filtered = items.filter(item =>
        item.title.toLowerCase().includes(query) ||
        item.type.toLowerCase().includes(query) ||
        item.location.toLowerCase().includes(query)
      );
      displayItems(filtered);
    });
  }

// --- Handle form submission
const form = document.getElementById("reportForm");
if (form) {
  form.addEventListener("submit", e => {
    e.preventDefault();

    const newItem = {
      title: document.getElementById("title").value.trim(),
      type: document.getElementById("type").value,
      description: document.getElementById("description").value.trim(),
      location: document.getElementById("location").value.trim(),
      image: document.getElementById("image").value.trim(),
      date: new Date().toLocaleString()
    };

    // Save new item
    items.push(newItem);
    localStorage.setItem("lostFoundItems", JSON.stringify(items));

    // Redirect
    alert("Report submitted successfully!");
    window.location.href = "home.html";
  });
}

// --- Show items on homepage load
document.addEventListener("DOMContentLoaded", displayItems);

function logout() {
  localStorage.removeItem("isLoggedIn");
  window.location.href = "login.html";
}


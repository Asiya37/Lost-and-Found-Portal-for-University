// --- Check admin access
if (localStorage.getItem("isAdmin") !== "true") {
    window.location.href = "index.html";
  }
  
  let items = JSON.parse(localStorage.getItem("lostFoundItems")) || [];
  const container = document.getElementById("adminItemsContainer");
  
  function displayItems() {
    container.innerHTML = "";
  
    if (items.length === 0) {
      container.innerHTML = "<p>No reports available.</p>";
      return;
    }
  
    items.forEach((item, index) => {
      const div = document.createElement("div");
      div.className = "item-card";
  
      div.innerHTML = `
        <img src="${item.image || 'https://via.placeholder.com/150'}" alt="${item.title}">
        <div class="item-info">
          <h3>${item.title}</h3>
          <p><strong>Type:</strong> ${item.type}</p>
          <p><strong>Description:</strong> ${item.description}</p>
          <p><strong>Location:</strong> ${item.location}</p>
          <p><strong>Email:</strong> ${item.email || 'N/A'}</p>
          <p><small>Date: ${item.date}</small></p>
          <div class="btn-group">
            <button class="edit" onclick="editItem(${index})">Edit</button>
            <button class="delete" onclick="deleteItem(${index})">Delete</button>
          </div>
        </div>
      `;
      container.appendChild(div);
    });
  }
  
  function deleteItem(index) {
    if (confirm("Are you sure you want to delete this report?")) {
      items.splice(index, 1);
      localStorage.setItem("lostFoundItems", JSON.stringify(items));
      displayItems();
    }
  }
  
  function editItem(index) {
    localStorage.setItem("editIndex", index);
    window.location.href = "report.html";
  }
  
  document.getElementById("logoutBtn").addEventListener("click", () => {
    localStorage.removeItem("isAdmin");
    window.location.href = "index.html";
  });
  
  displayItems();
  
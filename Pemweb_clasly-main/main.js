console.log("Classly homepage loaded.");

// Ambil nama user dari localStorage
const userName = localStorage.getItem("classlyUserName");

// Cek apakah ada nama user dan update welcome text
if (userName) {
  const welcomeText = document.getElementById("welcome-text");
  const currentHour = new Date().getHours();
  let greeting;
  
  if (currentHour < 12) {
    greeting = "Good morning";
  } else if (currentHour < 17) {
    greeting = "Good afternoon";
  } else {
    greeting = "Good evening";
  }
  
  welcomeText.textContent = `${greeting}, ${userName}`;
}

// Function to load recent items from localStorage
function loadRecentItems() {
  const recentItems = JSON.parse(localStorage.getItem("classlyRecentItems") || "[]");
  const recentContainer = document.getElementById("recent-items");
  
  if (recentItems.length === 0) {
    return; // Keep empty state
  }
  
  recentContainer.innerHTML = "";
  
  recentItems.slice(0, 6).forEach(item => {
    const card = document.createElement("div");
    card.className = "recent-card";
    card.innerHTML = `
      <h4>
        <span class="icon ${item.type}"></span>
        ${item.title}
      </h4>
      <p>${item.description || "No description"}</p>
      <div class="card-meta">${item.date} • ${item.type}</div>
    `;
    card.onclick = () => window.location.href = item.link;
    recentContainer.appendChild(card);
  });
}

// Function to load upcoming events
function loadUpcomingEvents() {
  const schedules = JSON.parse(localStorage.getItem("classSchedules") || "[]");
  const reminders = JSON.parse(localStorage.getItem("classlyReminders") || "[]");
  const eventsContainer = document.getElementById("upcoming-events");
  
  const allEvents = [
    ...schedules.map(s => ({...s, type: 'schedule'})),
    ...reminders.map(r => ({...r, type: 'reminder'}))
  ];
  
  if (allEvents.length === 0) {
    return; // Keep empty state
  }
  
  eventsContainer.innerHTML = "";
  
  allEvents.slice(0, 4).forEach(event => {
    const card = document.createElement("div");
    card.className = "recent-card";
    card.innerHTML = `
      <h4>
        <span class="icon ${event.type}"></span>
        ${event.courseName || event.title || event.subject}
      </h4>
      <p>${event.location || event.description || ""}</p>
      <div class="card-meta">${event.time || event.date} • ${event.day || ""}</div>
    `;
    eventsContainer.appendChild(card);
  });
}

// Function to add recent item
function addRecentItem(type, title, description, link) {
  const recentItems = JSON.parse(localStorage.getItem("classlyRecentItems") || "[]");
  const newItem = {
    type,
    title,
    description,
    link,
    date: new Date().toLocaleDateString(),
    timestamp: Date.now()
  };
  
  // Remove duplicate if exists
  const filtered = recentItems.filter(item => item.link !== link);
  
  // Add to beginning and limit to 10 items
  filtered.unshift(newItem);
  const limited = filtered.slice(0, 10);
  
  localStorage.setItem("classlyRecentItems", JSON.stringify(limited));
  loadRecentItems();
}

// Function untuk floating button (jika diperlukan)
function openQuickAdd() {
  // Bisa diarahkan ke modal atau halaman quick add
  alert("Quick Add feature - coming soon!");
}

// Load data on page load
document.addEventListener("DOMContentLoaded", function() {
  loadRecentItems();
  loadUpcomingEvents();
  
  // Update data setiap 30 detik untuk sinkronisasi
  setInterval(() => {
    loadRecentItems();
    loadUpcomingEvents();
  }, 30000);
});

// Export function for other pages to use
window.addRecentItem = addRecentItem;

// Function to handle search (optional)
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.querySelector(".search-container input");
  
  if (searchInput) {
    searchInput.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        const query = this.value.trim();
        if (query) {
          console.log("Searching for:", query);
          // Implement search functionality here
          // For now, just alert
          alert(`Search functionality coming soon! Query: ${query}`);
        }
      }
    });
  }
});

// Function to update user name (dapat dipanggil dari halaman account)
function updateUserName(newName) {
  localStorage.setItem("classlyUserName", newName);
  location.reload(); // Refresh untuk update welcome message
}

// Function to clear all data (untuk testing/reset)
function clearAllData() {
  if (confirm("Are you sure you want to clear all data? This cannot be undone.")) {
    localStorage.removeItem("classlyRecentItems");
    localStorage.removeItem("classSchedules");
    localStorage.removeItem("classlyReminders");
    localStorage.removeItem("classlyUserName");
    location.reload();
  }
}

// Export functions untuk debugging di console
window.updateUserName = updateUserName;
window.clearAllData = clearAllData;

// Add some interactive features
document.addEventListener("DOMContentLoaded", function() {
  // Add hover effects to sidebar links
  const sidebarLinks = document.querySelectorAll(".sidebar a");
  sidebarLinks.forEach(link => {
    link.addEventListener("mouseenter", function() {
      this.style.transform = "translateX(2px)";
    });
    
    link.addEventListener("mouseleave", function() {
      this.style.transform = "translateX(0)";
    });
  });
  
  // Add click analytics (optional)
  document.addEventListener("click", function(e) {
    if (e.target.matches("a")) {
      const linkText = e.target.textContent.trim();
      const linkHref = e.target.href;
      console.log(`Link clicked: ${linkText} -> ${linkHref}`);
      
      // Track navigation untuk recent items
      if (linkHref && !linkHref.includes("#")) {
        // Jangan track link internal (#)
        const pathName = linkHref.split("/").pop() || linkHref;
        addRecentItem(
          "navigation",
          linkText,
          `Visited ${pathName}`,
          linkHref
        );
      }
    }
  });
});
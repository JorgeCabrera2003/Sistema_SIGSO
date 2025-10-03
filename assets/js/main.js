// Theme toggle functionality
function initializeTheme() {
  const themeToggle = document.getElementById("theme-toggle");
  const htmlElement = document.documentElement;

  if (!themeToggle) {
    console.warn("Elemento theme-toggle no encontrado");
    return;
  }

  // Check for saved theme preference or use system preference
  const savedTheme = localStorage.getItem("theme");
  if (
    savedTheme === "dark" ||
    (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    htmlElement.classList.add("dark");
  }

  themeToggle.addEventListener("click", () => {
    htmlElement.classList.toggle("dark");
    const currentTheme = htmlElement.classList.contains("dark")
      ? "dark"
      : "light";
    localStorage.setItem("theme", currentTheme);

    // Update table styles
    const tables = document.querySelectorAll(".table");
    tables.forEach((table) => {
      table.classList.toggle(
        "table-dark",
        htmlElement.classList.contains("dark")
      );
    });
  });

  // Initial table style setup based on current theme
  const tables = document.querySelectorAll(".table");
  tables.forEach((table) => {
    table.classList.toggle("table-dark", htmlElement.classList.contains("dark"));
  });
}

// Sidebar functionality
function initializeSidebar() {
  const collapseBtn = document.getElementById("collapse-btn");
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");
  const sidebarToggle = document.getElementById("sidebar-toggle");

  if (collapseBtn && sidebar && mainContent) {
    collapseBtn.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      mainContent.classList.toggle("expanded");
    });
  }

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      sidebar.classList.add("mobile-open");
    });
  }

  // Close sidebar when clicking outside on mobile
  document.addEventListener("click", (event) => {
    const isMobile = window.innerWidth < 992;
    const isClickInsideSidebar = sidebar && sidebar.contains(event.target);
    const isClickOnToggle = sidebarToggle && sidebarToggle.contains(event.target);

    if (
      isMobile &&
      sidebar &&
      !isClickInsideSidebar &&
      !isClickOnToggle &&
      sidebar.classList.contains("mobile-open")
    ) {
      sidebar.classList.remove("mobile-open");
    }
  });
}

// Dropdown functionality - FIXED
function initializeDropdowns() {
  const dropdownToggles = document.querySelectorAll(
    ".notification-btn, .user-dropdown-toggle, [data-dropdown-toggle]"
  );
  const dropdownMenus = document.querySelectorAll(".dropdown-menu");
  const closeDropdownBtns = document.querySelectorAll(".close-dropdown");

  console.log("Dropdown toggles encontrados:", dropdownToggles.length);
  console.log("Dropdown menus encontrados:", dropdownMenus.length);

  // Toggle dropdown on click
  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();
      
      let dropdown = null;
      
      // Buscar el dropdown asociado de diferentes maneras
      if (this.dataset.dropdownToggle) {
        dropdown = document.getElementById(this.dataset.dropdownToggle);
      } else if (this.nextElementSibling && this.nextElementSibling.classList.contains("dropdown-menu")) {
        dropdown = this.nextElementSibling;
      } else {
        dropdown = this.closest('.action-item')?.querySelector('.dropdown-menu');
      }

      if (!dropdown) {
        console.warn("Dropdown menu no encontrado para:", this);
        return;
      }

      // Close all other dropdowns
      dropdownMenus.forEach((menu) => {
        if (menu !== dropdown && menu.classList.contains("show")) {
          menu.classList.remove("show");
        }
      });

      dropdown.classList.toggle("show");
      console.log("Dropdown toggled:", dropdown.classList.contains("show"));
    });
  });

  // Close dropdown when clicking the close button
  closeDropdownBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const dropdown = this.closest(".dropdown-menu");
      if (dropdown) {
        dropdown.classList.remove("show");
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener("click", (event) => {
    const isOutsideDropdown = !event.target.closest(".action-item") && 
                             !event.target.closest(".dropdown-menu");
    
    if (isOutsideDropdown) {
      dropdownMenus.forEach((menu) => {
        if (menu.classList.contains("show")) {
          menu.classList.remove("show");
        }
      });
    }
  });

  // Prevent dropdown menu clicks from closing the dropdown
  dropdownMenus.forEach((menu) => {
    menu.addEventListener("click", (event) => {
      event.stopPropagation();
    });
  });
}

// Table functionality
function initializeTable() {
  const rows = [
    {
      date: "2023-07-01",
      description: "Grocery Shopping",
      category: "Food",
      amount: -85.2,
      status: "Completed",
    },
    {
      date: "2023-07-02",
      description: "Salary Deposit",
      category: "Income",
      amount: 3000.0,
      status: "Completed",
    },
    {
      date: "2023-07-03",
      description: "Electric Bill",
      category: "Utilities",
      amount: -120.5,
      status: "Pending",
    },
    {
      date: "2023-07-04",
      description: "Online Purchase",
      category: "Shopping",
      amount: -65.99,
      status: "Completed",
    },
    {
      date: "2023-07-05",
      description: "Restaurant Dinner",
      category: "Food",
      amount: -45.8,
      status: "Completed",
    },
    {
      date: "2023-07-06",
      description: "Gas Station",
      category: "Transportation",
      amount: -40.0,
      status: "Completed",
    },
    {
      date: "2023-07-07",
      description: "Movie Tickets",
      category: "Entertainment",
      amount: -25.0,
      status: "Completed",
    },
    {
      date: "2023-07-08",
      description: "Gym Membership",
      category: "Health",
      amount: -50.0,
      status: "Pending",
    },
    {
      date: "2023-07-09",
      description: "Freelance Payment",
      category: "Income",
      amount: 500.0,
      status: "Completed",
    },
    {
      date: "2023-07-10",
      description: "Phone Bill",
      category: "Utilities",
      amount: -80.0,
      status: "Completed",
    },
    {
      date: "2023-07-11",
      description: "Book Purchase",
      category: "Education",
      amount: -30.5,
      status: "Completed",
    },
    {
      date: "2023-07-12",
      description: "Coffee Shop",
      category: "Food",
      amount: -4.5,
      status: "Completed",
    },
    {
      date: "2023-07-13",
      description: "Public Transport",
      category: "Transportation",
      amount: -25.0,
      status: "Completed",
    },
    {
      date: "2023-07-14",
      description: "Online Course",
      category: "Education",
      amount: -199.99,
      status: "Pending",
    },
    {
      date: "2023-07-15",
      description: "Clothing Store",
      category: "Shopping",
      amount: -89.95,
      status: "Completed",
    },
  ];

  let currentPage = 1;
  let rowsPerPage = 10;
  let filteredData = [...rows];

  const tableBody = document.querySelector("#transactionTable tbody");
  const pagination = document.getElementById("tablePagination");
  const rowsPerPageSelect = document.getElementById("rowsPerPage");
  const searchInput = document.getElementById("searchInput");

  if (!tableBody || !pagination) {
    console.warn("Elementos de tabla no encontrados");
    return;
  }

  function renderTable() {
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = Math.min(startIndex + rowsPerPage, filteredData.length);
    const pageData = filteredData.slice(startIndex, endIndex);

    tableBody.innerHTML = "";
    pageData.forEach((transaction) => {
      const row = document.createElement("tr");
      row.innerHTML = `
          <td>${transaction.date}</td>
          <td>${transaction.description}</td>
          <td>${transaction.category}</td>
          <td class="${transaction.amount >= 0 ? "text-success" : "text-danger"}">
            ${transaction.amount >= 0 ? '+' : ''}${transaction.amount.toFixed(2)}
          </td>
          <td><span class="badge ${
            transaction.status === "Completed" ? "bg-success" : "bg-warning"
          }">${transaction.status}</span></td>
        `;
      tableBody.appendChild(row);
    });

    renderPagination();
  }

  function renderPagination() {
    const pageCount = Math.ceil(filteredData.length / rowsPerPage);
    pagination.innerHTML = "";

    // Previous button
    const prevLi = document.createElement("li");
    prevLi.classList.add("page-item", currentPage === 1 ? "disabled" : null);
    prevLi.innerHTML = `<a class="page-link" href="#" tabindex="-1">Previous</a>`;
    prevLi.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage > 1) {
        currentPage--;
        renderTable();
      }
    });
    pagination.appendChild(prevLi);

    // Page numbers
    for (let i = 1; i <= pageCount; i++) {
      const li = document.createElement("li");
      li.classList.add("page-item", i === currentPage ? "active" : null);
      li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
      li.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage = i;
        renderTable();
      });
      pagination.appendChild(li);
    }

    // Next button
    const nextLi = document.createElement("li");
    nextLi.classList.add(
      "page-item",
      currentPage === pageCount ? "disabled" : null
    );
    nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
    nextLi.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage < pageCount) {
        currentPage++;
        renderTable();
      }
    });
    pagination.appendChild(nextLi);
  }

  if (rowsPerPageSelect) {
    rowsPerPageSelect.addEventListener("change", (e) => {
      rowsPerPage = Number.parseInt(e.target.value);
      currentPage = 1;
      renderTable();
    });
  }

  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const searchTerm = e.target.value.toLowerCase();
      filteredData = rows.filter(
        (transaction) =>
          transaction.description.toLowerCase().includes(searchTerm) ||
          transaction.category.toLowerCase().includes(searchTerm) ||
          transaction.status.toLowerCase().includes(searchTerm)
      );
      currentPage = 1;
      renderTable();
    });
  }

  // Initial render
  renderTable();
}

// Handle window resize
function initializeResizeHandler() {
  window.addEventListener("resize", () => {
    const sidebar = document.getElementById("sidebar");
    if (window.innerWidth >= 992 && sidebar) {
      sidebar.classList.remove("mobile-open");
    }
  });
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function() {
  console.log("Inicializando aplicación...");
  
  initializeTheme();
  initializeSidebar();
  initializeDropdowns();
  initializeTable();
  initializeResizeHandler();
  
  console.log("Aplicación inicializada correctamente");
});

// Event listener for debugging
var someElement = document.getElementById('someId');
if (someElement) {
    someElement.addEventListener('click', function() {
        console.log('Elemento clickeado');
    });
} else {
    console.warn('El elemento #someId no existe en el DOM.');
}
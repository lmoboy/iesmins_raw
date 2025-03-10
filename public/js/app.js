// Global app initialization
class App {
  constructor() {
    this.components = new Map();
    this.initializeComponents();
  }

  initializeComponents() {
    console.log("initializing components");

    // Initialize search components
    document.querySelectorAll(".search-container").forEach((element) => {
      const searchBar = new SearchBar(element);
      this.components.set(element.id, searchBar);
      searchBar.render();

    });

    // Initialize dropdown components
    document.querySelectorAll(".dropdown-container").forEach((element) => {
      const options = element.dataset.options
        ? JSON.parse(element.dataset.options)
        : ["Option 1", "Option 2", "Option 3", "Option 4"];
      const isMulti = element.dataset.multi === 'true';
      const dropdown = new Dropdown(element, options, isMulti);

      this.components.set(element.id, dropdown);

      dropdown.render();
    });
  }

  getComponent(id) {
    return this.components.get(id);
  }
}

// Initialize the application
const app = new App();

document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
});

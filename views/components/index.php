<div class="container">
    <h1>Component Demo</h1>
    
    <div class="demo-section">
        <h2>Search Component</h2>
        <div id="searchDemo" class="search-container"></div>
    </div>

    <div class="demo-section">
        <h2>Dropdown Component</h2>
        <div id="dropdownDemo" class="dropdown-container" data-options='["Option 1", "Option 2", "Option 3", "Option 4"]'></div>
    </div>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.demo-section {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.search-container input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.dropdown-container {
    height: fit-content;
    position: relative;
}

.dropdown-container .btn {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: white;
    text-align: left;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.dropdown-content.show {
    display: block;
}

.dropdown-item {
    padding: 10px;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f5f5f5;
}

.dropdown-item.selected {
    background-color: #e0e0e0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const app = new App();
});
</script>
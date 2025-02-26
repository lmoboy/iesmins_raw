class DOMHelper {
    static $(selector) {
        const elements = document.querySelectorAll(selector);
        return elements.length === 1 ? elements[0] : elements;
    }

    static create(tag, attributes = {}, content = '') {
        const element = document.createElement(tag);
        Object.entries(attributes).forEach(([key, value]) => {
            element.setAttribute(key, value);
        });
        element.innerHTML = content;
        return element;
    }

    static append(parent, child) {
        parent.appendChild(child);
        return child;
    }
}

class Ajax {
    static async get(url, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;
        const response = await fetch(fullUrl);
        return response.json();
    }

    static async post(url, data = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }

    static async put(url, data = {}) {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }

    static async delete(url) {
        const response = await fetch(url, {
            method: 'DELETE'
        });
        return response.json();
    }
}

class EventEmitter {
    constructor() {
        this.events = {};
    }

    on(event, callback) {
        if (!this.events[event]) {
            this.events[event] = [];
        }
        this.events[event].push(callback);
        return this;
    }

    off(event, callback) {
        if (this.events[event]) {
            this.events[event] = this.events[event].filter(cb => cb !== callback);
        }
        return this;
    }

    emit(event, data) {
        if (this.events[event]) {
            this.events[event].forEach(callback => callback(data));
        }
        return this;
    }
}

class Component extends EventEmitter {
    constructor(element) {
        super();
        this.element = element;
        this.state = {};
        this.stateHistory = [];
        this.maxHistoryLength = 10;
    }

    setState(newState, options = {}) {
        const prevState = { ...this.state };
        const mergedState = this._mergeStates(this.state, newState);

        if (options.validate && !this._validateState(mergedState)) {
            this.emit('stateValidationError', { prevState, attemptedState: mergedState });
            return false;
        }

        this._addToHistory(prevState);
        this.state = mergedState;
        this.emit('stateChange', { prevState, newState: this.state });
        this.render();
        return true;
    }

    _mergeStates(currentState, newState) {
        const merged = { ...currentState };
        Object.entries(newState).forEach(([key, value]) => {
            if (value && typeof value === 'object' && !Array.isArray(value)) {
                merged[key] = this._mergeStates(currentState[key] || {}, value);
            } else {
                merged[key] = value;
            }
        });
        return merged;
    }

    _validateState(state) {
        return true; // Override in child classes for custom validation
    }

    _addToHistory(state) {
        this.stateHistory.unshift({ ...state, timestamp: Date.now() });
        if (this.stateHistory.length > this.maxHistoryLength) {
            this.stateHistory.pop();
        }
    }

    getStateHistory() {
        return [...this.stateHistory];
    }

    revertToPreviousState() {
        if (this.stateHistory.length > 0) {
            const previousState = this.stateHistory[0];
            this.stateHistory.shift();
            this.state = { ...previousState };
            delete this.state.timestamp;
            this.render();
            return true;
        }
        return false;
    }

    render() {
        // Override this method in child classes
    }
}


class SearchBar extends Component {
    constructor(element) {
        super(element);
        this.state = { query: '' };
        this._bindEvents();
        this.input = null;
        this.render();
    }

    _bindEvents() {
        this.element.addEventListener('input', (e) => {
            if (e.target.matches('input')) {
                this.state.query = e.target.value;
                this.emit('stateChange', { newState: this.state });
            }
        });
    }
    
    render() {
        if (!this.input) {
            this.element.innerHTML = `
                <input type="text" placeholder="Search..." value="${this.state.query}" />
            `;
            this.input = this.element.querySelector('input');
        } else {
            this.input.value = this.state.query;
        }
    }
}


class Dropdown extends Component {
    constructor(element, selectable, isMulti = false) {
        super(element);
        this.isMulti = isMulti;
        this.state = { 
            isOpen: false, 
            items: selectable, 
            selectedItems: []
        };
        this._bindEvents();
    }

    _bindEvents() {
        this.element.addEventListener('click', (e) => {
            if (e.target.matches('.btn')) {
                e.stopPropagation();
                this.setState({ isOpen: !this.state.isOpen });
            } else if (e.target.matches('.dropdown-item')) {
                const clickedItem = e.target.textContent.trim();
                let newSelectedItems;
                
                if (this.isMulti) {
                    newSelectedItems = [...this.state.selectedItems];
                    const itemIndex = newSelectedItems.indexOf(clickedItem);
                    
                    if (itemIndex === -1) {
                        newSelectedItems.push(clickedItem);
                    } else {
                        newSelectedItems.splice(itemIndex, 1);
                    }
                } else {
                    newSelectedItems = this.state.selectedItems[0] === clickedItem ? [] : [clickedItem];
                }

                this.setState({
                    selectedItems: newSelectedItems,
                    isOpen: this.isMulti ? true : false
                });
                this.emit('itemSelected', newSelectedItems);
            }
        });

        document.addEventListener('click', (e) => {
            if (!this.element.contains(e.target) && this.state.isOpen) {
                this.setState({ isOpen: false });
            }
        });
    }
    
    render() {
        const dropdownClasses = this.state.isOpen ? 'dropdown-content show' : 'dropdown-content';
        const buttonText = this.state.selectedItems.length > 0 
            ? this.state.selectedItems.join(', ') 
            : 'Select an option';
        
        this.element.innerHTML = `
            <button class="btn">${buttonText}</button>
            <div class="${dropdownClasses}">
                ${this.state.items.map(item => `
                    <div class="dropdown-item${this.state.selectedItems.includes(item) ? ' selected' : ''}">
                        ${item}
                    </div>
                `).join('')}
            </div>
        `;
    }
}


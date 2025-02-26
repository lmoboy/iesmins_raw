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
    }

    setState(newState) {
        this.state = { ...this.state, ...newState };
        this.render();
    }

    render() {
        // Override this method in child classes
    }
}
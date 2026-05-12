/**
 * EliteNoxus - Main JavaScript
 */

// API Base URL
const API_BASE = '/api';

// Utility Functions
function showAlert(message, type = 'info') {
    alert(message);
}

function showLoading() {
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

// API Request Helper
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        }
    };

    const config = { ...defaultOptions, ...options };

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Error en la solicitud');
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        showAlert(error.message, 'error');
        throw error;
    }
}

// Token Management
const TokenManager = {
    async create() {
        return await apiRequest('/tokens/create_token.php', { method: 'POST' });
    },

    async validate(token) {
        return await apiRequest(`/tokens/validate_token.php?token=${encodeURIComponent(token)}`);
    },

    async getInfo() {
        return await apiRequest('/tokens/get_token_info.php');
    }
};

// Agent Management
const AgentManager = {
    async getAgents() {
        return await apiRequest('/agents/get_agents.php');
    },

    async combine(agents) {
        return await apiRequest('/agents/combine_agents.php', {
            method: 'POST',
            body: JSON.stringify({ agents })
        });
    }
};

// Post Management
const PostManager = {
    async create(contenido, tipo = 'texto') {
        return await apiRequest('/posts/create_post.php', {
            method: 'POST',
            body: JSON.stringify({ contenido, tipo })
        });
    },

    async getAll(limit = 30, offset = 0) {
        return await apiRequest(`/posts/get_posts.php?limit=${limit}&offset=${offset}`);
    },

    async delete(postId) {
        return await apiRequest(`/posts/delete_post.php?id=${postId}`, {
            method: 'DELETE'
        });
    }
};

// User Management
const UserManager = {
    async register(nombre, email, password) {
        return await apiRequest('/users/register.php', {
            method: 'POST',
            body: JSON.stringify({ nombre, email, password })
        });
    },

    async login(email, password) {
        return await apiRequest('/users/login.php', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
    },

    async getProfile() {
        return await apiRequest('/users/profile.php');
    }
};

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('EliteNoxus initialized');

    // Add CSRF protection for forms
    const forms = document.querySelectorAll('form[data-api]');
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
});

async function handleFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const action = form.dataset.api;
    const method = form.method || 'POST';

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        showLoading();
        const result = await apiRequest(action, {
            method,
            body: JSON.stringify(data)
        });

        if (result.success) {
            showAlert(result.message || 'Operación exitosa', 'success');

            if (result.redirect) {
                window.location.href = result.redirect;
            }
        }
    } catch (error) {
        // Error already handled in apiRequest
    } finally {
        hideLoading();
    }
}

// Session Management
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        window.location.href = '/logout.php';
    }
}

// Theme Management
const ThemeManager = {
    current: 'red',

    setTheme(theme) {
        this.current = theme;
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('noxus_theme', theme);
    },

    loadTheme() {
        const saved = localStorage.getItem('noxus_theme');
        if (saved) {
            this.setTheme(saved);
        }
    }
};

// Initialize theme
ThemeManager.loadTheme();

// Notification System
const Notifications = {
    container: null,

    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'notifications';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                display: flex;
                flex-direction: column;
                gap: 10px;
            `;
            document.body.appendChild(this.container);
        }
    },

    show(message, type = 'info') {
        this.init();

        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            padding: 15px 20px;
            border-radius: 8px;
            background: ${type === 'success' ? '#22c55e' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        `;

        this.container.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

// Export functions to global scope
window.EliteNoxus = {
    TokenManager,
    AgentManager,
    PostManager,
    UserManager,
    ThemeManager,
    Notifications,
    apiRequest,
    showAlert,
    logout
};

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

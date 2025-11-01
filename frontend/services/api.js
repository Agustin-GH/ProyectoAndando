/**
 * API Service - Centralized API communication
 * This file handles all requests to the backend API
 */

// Configuration
const API_CONFIG = {
    baseURL: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? '/backend/api.php'  // Local development
        : '/backend/api.php'  // Production (adjust as needed)
};

/**
 * Make an API request
 * @param {string} endpoint - API endpoint (e.g., '/auth/login')
 * @param {Object} options - Fetch options
 * @returns {Promise<any>} Response data
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_CONFIG.baseURL}${endpoint}`;
    
    const defaultOptions = {
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    try {
        const response = await fetch(url, finalOptions);
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || `HTTP ${response.status}`);
        }
        
        return data;
    } catch (error) {
        console.error('API Request Error:', error);
        throw error;
    }
}

/**
 * API methods organized by feature
 */
const API = {
    // Health check
    health: () => apiRequest('/health'),
    
    // Authentication
    auth: {
        register: (data) => apiRequest('/auth/register', {
            method: 'POST',
            body: JSON.stringify(data)
        }),
        login: (data) => apiRequest('/auth/login', {
            method: 'POST',
            body: JSON.stringify(data)
        }),
        logout: () => apiRequest('/auth/logout', {
            method: 'POST'
        }),
        getSession: () => apiRequest('/session')
    },
    
    // Admin
    admin: {
        listUsers: () => apiRequest('/admin/users'),
        getUser: (id) => apiRequest(`/admin/get_user?id=${id}`),
        deleteUser: (userId) => apiRequest('/admin/delete_user', {
            method: 'POST',
            body: JSON.stringify({ user_id: userId })
        }),
        getRoles: () => apiRequest('/admin/get_roles'),
        getRoleHistory: () => apiRequest('/admin/get_role_history'),
        updateRole: (userId, newRole) => apiRequest('/admin/update_role', {
            method: 'POST',
            body: JSON.stringify({ user_id: userId, new_role: newRole })
        })
    }
};

// Export for use in other scripts
if (typeof window !== 'undefined') {
    window.API = API;
    window.apiRequest = apiRequest;
}

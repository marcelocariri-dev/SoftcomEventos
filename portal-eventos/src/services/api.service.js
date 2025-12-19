// src/services/api.service.js

class ApiService {
  constructor() {
    this.token = localStorage.getItem('token');
  }

  getHeaders(isFormData = false) {
    const headers = {};
    
    if (!isFormData) {
      headers['Content-Type'] = 'application/json';
    }
    
    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }
    
    return headers;
  }

  async request(url, options = {}) {
    try {
      const response = await fetch(url, {
        ...options,
        headers: {
          ...this.getHeaders(options.isFormData),
          ...options.headers,
        },
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Erro na requisição');
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  async get(url) {
    return this.request(url, { method: 'GET' });
  }

  async post(url, body) {
    return this.request(url, {
      method: 'POST',
      body: JSON.stringify(body),
    });
  }

  async put(url, body) {
    return this.request(url, {
      method: 'PUT',
      body: JSON.stringify(body),
    });
  }

  async delete(url) {
    return this.request(url, { method: 'DELETE' });
  }

  async postFormData(url, formData) {
    return this.request(url, {
      method: 'POST',
      body: formData,
      isFormData: true,
    });
  }

  setToken(token) {
    this.token = token;
    localStorage.setItem('token', token);
  }

  removeToken() {
    this.token = null;
    localStorage.removeItem('token');
  }
}

export default new ApiService();

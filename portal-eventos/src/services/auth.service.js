// src/services/auth.service.js

import axios from 'axios';

// Criar instância separada para auth (sem /v1)
const authAxios = axios.create({
  baseURL: import.meta.env.VITE_API_URL?.replace('/v1', '') || 'http://localhost:73/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Instância normal do axios para outras rotas (com /v1)
import axiosInstance from './axios';

const authService = {
  /**
   * Registrar novo usuário
   */
  async register(userData) {
    try {
      const response = await authAxios.post('/register', {
        name: userData.name,
        email: userData.email,
        password: userData.password,
      });

      const { access_token, user } = response.data;

      // Salvar token e usuário no localStorage
      localStorage.setItem('token', access_token);
      localStorage.setItem('user', JSON.stringify(user));

      return { token: access_token, user };
    } catch (error) {
      const errorMessage = error.response?.data?.message || 
                          error.response?.data?.errors?.email?.[0] ||
                          'Erro ao criar conta. Tente novamente.';
      throw new Error(errorMessage);
    }
  },

  /**
   * Login do usuário
   */
  async login(email, password) {
    try {
      const response = await authAxios.post('/login', {
        email,
        password,
      });

      const { access_token, user } = response.data;

      // Salvar token e usuário no localStorage
      localStorage.setItem('token', access_token);
      localStorage.setItem('user', JSON.stringify(user));

      return { token: access_token, user };
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao fazer login. Verifique suas credenciais.'
      );
    }
  },

  /**
   * Logout do usuário
   */
  async logout() {
    try {
      const token = localStorage.getItem('token');
      if (token) {
        await authAxios.post('/logout', {}, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
      }
    } catch (error) {
      console.error('Erro ao fazer logout:', error);
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  },

  /**
   * Verificar se está autenticado
   */
  isAuthenticated() {
    return !!localStorage.getItem('token');
  },

  /**
   * Obter usuário atual
   */
  getCurrentUser() {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      try {
        return JSON.parse(userStr);
      } catch (error) {
        return null;
      }
    }
    return null;
  },

  /**
   * Obter token
   */
  getToken() {
    return localStorage.getItem('token');
  },
};

export default authService;
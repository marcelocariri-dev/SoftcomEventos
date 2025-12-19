// src/services/locais.service.js

import axios from './axios';  // ← Minúsculo!

const locaisService = {
  /**
   * Listar todos os locais
   */
  async getLocal(params = {}) {
    try {
      const response = await axios.get('/locais', { params });
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar Locais.'
      );
    }
  },

  /**
   * Buscar locais com filtros
   */
  async searchLocais(searchData) {
    try {
      const response = await axios.post('/locais/buscar', searchData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao buscar locais.'
      );
    }
  },

  /**
   * Obter detalhes de um local
   */
  async getLocalById(id) {
    try {
      const response = await axios.get(`/locais/${id}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar local.'
      );
    }
  },

  /**
   * Criar novo local
   */
  async createLocal(localData) {
    try {
      const response = await axios.post('/locais', localData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao criar local.'
      );
    }
  },

  /**
   * Atualizar local
   */
  async updateLocal(id, localData) {
    try {
      const response = await axios.put(`/locais/${id}`, localData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao atualizar local.'
      );
    }
  },

  /**
   * Deletar local
   */
  async deleteLocal(id) {
    try {
      const response = await axios.delete(`/locais/${id}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao deletar local.'
      );
    }
  },
};

export default locaisService;
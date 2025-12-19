// src/services/participantes.service.js

import axios from './axios';

const participantesService = {
  /**
   * Criar participante a partir do usuário logado
   */
  async criarParticipante(userData = null) {
    try {
      // Se não passar dados, usa o usuário logado
      const payload = userData || {};
      
      const response = await axios.post('/participantes', payload);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao criar participante.'
      );
    }
  },

  /**
   * Obter participante por ID
   */
  async getParticipanteById(id) {
    try {
      const response = await axios.get(`/participantes/${id}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar participante.'
      );
    }
  },

  /**
   * Obter participante do usuário logado
   */
  async getMeuParticipante() {
    try {
      const response = await axios.get('/participantes/me');
      return response.data;
    } catch (error) {
      // Se não existir, retorna null
      if (error.response?.status === 404) {
        return null;
      }
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar participante.'
      );
    }
  },

  /**
   * Atualizar participante
   */
  async updateParticipante(id, data) {
    try {
      const response = await axios.put(`/participantes/${id}`, data);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao atualizar participante.'
      );
    }
  },
};

export default participantesService;
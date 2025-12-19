// src/services/inscricoes.service.js

import axios from './axios';

const inscricoesService = {
  /**
   * Criar inscrição (cadastra participante + gera ingresso)
   */
  async criarInscricao(eventoId, ingressoId, participanteData = null) {
    try {
      const payload = {
        evento_id: eventoId,
        ingresso_id: ingressoId,
      };

      // Se tiver dados do participante, incluir
      if (participanteData) {
        payload.participante = participanteData;
      }

      const response = await axios.post('/inscricoes', payload);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao se inscrever no evento.'
      );
    }
  },

  /**
   * Obter detalhes de uma inscrição
   */
  async getInscricaoById(inscricaoId) {
    try {
      const response = await axios.get(`/inscricoes/${inscricaoId}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar inscrição.'
      );
    }
  },

  /**
   * Listar minhas inscrições
   */
  async getMinhasInscricoes() {
    try {
      const response = await axios.get('/inscricoes/minhas');
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar inscrições.'
      );
    }
  },

  /**
   * Cancelar inscrição
   */
  async cancelarInscricao(inscricaoId) {
    try {
      const response = await axios.delete(`/inscricoes/${inscricaoId}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao cancelar inscrição.'
      );
    }
  },
};

export default inscricoesService;
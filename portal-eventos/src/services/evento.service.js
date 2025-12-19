// src/services/evento.service.js

import axios from './axios';

const eventoService = {
  /**
   * Listar todos os eventos
   */
  async getEventos(params = {}) {
    try {
      const response = await axios.get('/eventos', { params });
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar eventos.'
      );
    }
  },

  /**
   * Buscar eventos com filtros
   */
  async searchEventos(searchData) {
    try {
      const response = await axios.post('/eventos/buscar', searchData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao buscar eventos.'
      );
    }
  },

  /**
   * Obter detalhes de um evento
   */
  async getEventoById(id) {
    try {
      const response = await axios.get(`/eventos/${id}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar evento.'
      );
    }
  },

  /**
   * Criar novo evento
   */
  async createEvento(eventoData) {
    try {
      const response = await axios.post('/eventos', eventoData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao criar evento.'
      );
    }
  },

  /**
   * Atualizar evento
   */
  async updateEvento(id, eventoData) {
    try {
      const response = await axios.put(`/eventos/${id}`, eventoData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao atualizar evento.'
      );
    }
  },

  /**
   * Deletar evento
   */
  async deleteEvento(id) {
    try {
      const response = await axios.delete(`/eventos/${id}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao deletar evento.'
      );
    }
  },

  /**
   * Eventos próximos
   */
  async getEventosProximos() {
    try {
      const response = await axios.get('/eventos/proximos');
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar eventos próximos.'
      );
    }
  },

  /**
   * Meus eventos (inscritos)
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
   * Eventos que eu criei
   */
  async getMeusEventos() {
    try {
      const response = await axios.get('/eventos/meus');
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar seus eventos.'
      );
    }
  },

  /**
   * Inscrever-se em um evento
   */
  async inscreverEvento(eventoId, ingressoId) {
    try {
      const response = await axios.post('/inscricoes', {
        evento_id: eventoId,
        ingresso_id: ingressoId,
      });
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao se inscrever no evento.'
      );
    }
  },

  /**
   * Obter ingressos de um evento
   */
  async getIngressosByEvento(eventoId) {
    try {
      const response = await axios.get(`/ingressos/evento/${eventoId}`);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao carregar ingressos.'
      );
    }
  },

  /**
   * Upload de imagem do evento
   */
  async uploadImagemEvento(eventoId, imageFile) {
    try {
      const formData = new FormData();
      formData.append('imagem', imageFile);

      const response = await axios.post(`/eventos/${eventoId}/imagem`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao fazer upload da imagem.'
      );
    }
  },

  /**
   * Criar ingresso para um evento
   */
  async createIngresso(ingressoData) {
    try {
      const response = await axios.post('/ingressos', ingressoData);
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.message || 'Erro ao criar ingresso.'
      );
    }
  },
};

export default eventoService;
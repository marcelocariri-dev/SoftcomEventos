// src/config/api.js

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

export const API_ENDPOINTS = {
  // Auth
  LOGIN: `${API_BASE_URL}/login`,
  REGISTER: `${API_BASE_URL}/register`,
  LOGOUT: `${API_BASE_URL}/logout`,
  
  // Eventos
  EVENTOS: `${API_BASE_URL}/eventos`,
  EVENTO_DETAIL: (id) => `${API_BASE_URL}/eventos/${id}`,
  EVENTOS_SEARCH: `${API_BASE_URL}/eventos/buscar`,
  
  // Locais
  LOCAIS: `${API_BASE_URL}/locais`,
  LOCAL_DETAIL: (id) => `${API_BASE_URL}/locais/${id}`,
  
  // Participantes
  PARTICIPANTES: `${API_BASE_URL}/participantes`,
  PARTICIPANTE_DETAIL: (id) => `${API_BASE_URL}/participantes/${id}`,
  
  // Ingressos
  INGRESSOS: `${API_BASE_URL}/ingressos`,
  INGRESSOS_EVENTO: (eventoId) => `${API_BASE_URL}/ingressos/evento/${eventoId}`,
  
  // Inscrições
  INSCRICOES: `${API_BASE_URL}/inscricoes`,
  MINHAS_INSCRICOES: `${API_BASE_URL}/inscricoes/minhas`,
  
  // User
  PROFILE: `${API_BASE_URL}/perfil`,
};

export default API_BASE_URL;

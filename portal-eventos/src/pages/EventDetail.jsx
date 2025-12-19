// src/pages/EventDetail.jsx - Detalhes + Inscrição

import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Calendar, MapPin, Clock, Users, ArrowLeft, Ticket, Loader2 } from 'lucide-react';
import eventoService from '../services/evento.service';
import inscricoesService from '../services/Inscricoes.service';
import participantesService from '../services/participantes.service';
import authService from '../services/auth.service';

const EventDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  
  const [evento, setEvento] = useState(null);
  const [ingressos, setIngressos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [inscrevendo, setInscrevendo] = useState(false);
  const [error, setError] = useState('');
  const [selectedIngresso, setSelectedIngresso] = useState(null);

  useEffect(() => {
    console.log('🔍 EventDetail montado - ID do evento:', id);
    if (id) {
      loadEvento();
    } else {
      console.error('❌ ID do evento não definido!');
      setError('ID do evento não encontrado na URL');
      setLoading(false);
    }
  }, [id]); // ← Recarrega quando o ID mudar

  const loadEvento = async () => {
    try {
      setLoading(true);
      setError('');
      
      console.log('📤 Carregando evento ID:', id);
      
      const [eventoData, ingressosData] = await Promise.all([
        eventoService.getEventoById(id),
        eventoService.getIngressosByEvento(id)
      ]);
      
      console.log('✅ Evento carregado:', eventoData);
      console.log('✅ Ingressos carregados:', ingressosData);
      
      // Extrair dados (pode vir em diferentes formatos)
      const evento = eventoData?.data || eventoData;
      const ingressos = ingressosData?.data || ingressosData || [];
      
      setEvento(evento);
      setIngressos(Array.isArray(ingressos) ? ingressos : []);
      
    } catch (err) {
      console.error('❌ Erro ao carregar evento:', err);
      console.error('Detalhes:', err.response?.data);
      setError('Erro ao carregar evento');
    } finally {
      setLoading(false);
    }
  };

  const handleInscrever = async (ingressoId) => {
    // Verificar se está logado
    if (!authService.isAuthenticated()) {
      sessionStorage.setItem('redirectAfterLogin', `/evento/${id}`);
      navigate('/login');
      return;
    }

    try {
      setInscrevendo(true);
      setError('');
      
      console.log('🎫 Iniciando inscrição no evento...');
      console.log('Evento ID:', id);
      console.log('Ingresso ID:', ingressoId);

      // 1. Verificar se já existe participante para o usuário
      console.log('📤 1. Verificando se usuário já tem participante...');
      let participante = await participantesService.getMeuParticipante();
      
      // 2. Se não existir, criar participante
      if (!participante) {
        console.log('📤 2. Criando participante...');
        const user = authService.getCurrentUser();
        
        const participanteData = {
          nome: user.name,
          email: user.email,
          // Adicione outros campos se necessário
        };
        
        participante = await participantesService.criarParticipante(participanteData);
        console.log('✅ 2. Participante criado:', participante);
      } else {
        console.log('✅ 1. Participante já existe:', participante);
      }

      // Extrair ID do participante
      const participanteId = participante?.data?.id || participante?.id;
      
      if (!participanteId) {
        throw new Error('ID do participante não encontrado');
      }

      console.log('✅ ID do participante:', participanteId);

      // 3. Buscar valor do ingresso
      const ingressoSelecionado = ingressos.find(ing => ing.id === ingressoId);
      const valorPago = ingressoSelecionado?.valor || 0;

      console.log('💰 Valor do ingresso:', valorPago);

      // 4. Criar inscrição
      console.log('📤 3. Criando inscrição...');
      const inscricaoResponse = await inscricoesService.criarInscricao(
        id,           // evento_id
        ingressoId,   // ingresso_id
        participanteId, // participante_id
        valorPago     // valor_pago
      );

      console.log('✅ 3. Inscrição criada:', inscricaoResponse);

      // 5. Extrair ID da inscrição
      const inscricaoId = inscricaoResponse?.data?.id || inscricaoResponse?.id;
      
      if (!inscricaoId) {
        throw new Error('ID da inscrição não encontrado');
      }

      console.log('✅ ID da inscrição:', inscricaoId);
      console.log('🔄 Redirecionando para /ingresso/' + inscricaoId);

      // 6. Redirecionar para página do ingresso
      navigate(`/ingresso/${inscricaoId}`);
      
    } catch (err) {
      console.error('❌ Erro ao se inscrever:', err);
      console.error('Detalhes:', err.response?.data);
      setError(err.message || 'Erro ao se inscrever no evento');
    } finally {
      setInscrevendo(false);
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    const months = [
      'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
      'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];
    
    return `${days[date.getDay()]}, ${date.getDate()} de ${months[date.getMonth()]} de ${date.getFullYear()}`;
  };

  const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center mt-20">
        <Loader2 className="animate-spin h-12 w-12 text-primary" />
      </div>
    );
  }

  if (!evento) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center mt-20">
        <div className="text-center">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Evento não encontrado</h2>
          <a href="/" className="text-primary hover:underline">Voltar para eventos</a>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 mt-20">
      {/* Hero Image */}
      <div className="relative h-96 bg-gradient-to-br from-primary to-accent-purple">
        {evento.imagem && (
          <img 
            src={evento.imagem} 
            alt={evento.titulo}
            className="w-full h-full object-cover"
          />
        )}
        <div className="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
          <div className="text-center text-white">
            <h1 className="text-5xl font-bold mb-4">{evento.titulo}</h1>
            {evento.status === 'publicado' && (
              <span className="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                Disponível
              </span>
            )}
          </div>
        </div>
        
        {/* Back Button */}
        <button
          onClick={() => navigate(-1)}
          className="absolute top-6 left-6 p-3 bg-white/10 backdrop-blur-md rounded-lg text-white hover:bg-white/20 transition"
        >
          <ArrowLeft size={24} />
        </button>
      </div>

      <div className="container mx-auto px-4 -mt-32 relative z-10 pb-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <div className="bg-white rounded-2xl shadow-xl p-8">
              
              {/* Error Message */}
              {error && (
                <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                  <p className="text-red-600">{error}</p>
                </div>
              )}

              {/* Informações do Evento */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div className="flex items-start space-x-3">
                  <Calendar className="text-primary mt-1" size={20} />
                  <div>
                    <p className="text-sm text-gray-600">Data</p>
                    <p className="font-semibold">{formatDate(evento.data_inicio)}</p>
                  </div>
                </div>

                <div className="flex items-start space-x-3">
                  <Clock className="text-primary mt-1" size={20} />
                  <div>
                    <p className="text-sm text-gray-600">Horário</p>
                    <p className="font-semibold">{formatTime(evento.data_inicio)}</p>
                  </div>
                </div>

                {evento.local && (
                  <div className="flex items-start space-x-3 md:col-span-2">
                    <MapPin className="text-primary mt-1" size={20} />
                    <div>
                      <p className="text-sm text-gray-600">Local</p>
                      <p className="font-semibold">{evento.local.nome}</p>
                      <p className="text-sm text-gray-600">
                        {evento.local.endereco}, {evento.local.numero} - {evento.local.bairro}
                      </p>
                      <p className="text-sm text-gray-600">
                        {evento.local.cidade} - {evento.local.estado}, {evento.local.cep}
                      </p>
                    </div>
                  </div>
                )}

                {evento.capacidade_maxima && (
                  <div className="flex items-start space-x-3">
                    <Users className="text-primary mt-1" size={20} />
                    <div>
                      <p className="text-sm text-gray-600">Capacidade</p>
                      <p className="font-semibold">{evento.capacidade_maxima} pessoas</p>
                    </div>
                  </div>
                )}
              </div>

              {/* Descrição */}
              <div className="pt-6 border-t">
                <h3 className="text-xl font-bold mb-3">Sobre o evento</h3>
                <p className="text-gray-700 whitespace-pre-line">{evento.descricao}</p>
              </div>
            </div>
          </div>

          {/* Sidebar - Ingressos */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-2xl shadow-xl p-6 sticky top-24">
              <h3 className="text-2xl font-bold mb-6 flex items-center">
                <Ticket className="mr-2" size={24} />
                Ingressos
              </h3>

              {ingressos.length === 0 ? (
                <div className="text-center py-8">
                  <Ticket size={48} className="mx-auto text-gray-300 mb-4" />
                  <p className="text-gray-600">Nenhum ingresso disponível no momento</p>
                </div>
              ) : (
                <div className="space-y-4">
                  {ingressos.map((ingresso) => (
                    <div
                      key={ingresso.id}
                      className={`border rounded-lg p-4 transition ${
                        selectedIngresso === ingresso.id 
                          ? 'border-primary bg-primary/5' 
                          : 'border-gray-200 hover:border-primary'
                      }`}
                    >
                      <div className="flex items-start justify-between mb-2">
                        <div>
                          <h4 className="font-semibold text-lg">{ingresso.tipo_ingresso}</h4>
                          {ingresso.descricao && (
                            <p className="text-sm text-gray-600">{ingresso.descricao}</p>
                          )}
                        </div>
                      </div>

                      <div className="flex items-center justify-between mt-4">
                        <div>
                          <p className="text-2xl font-bold text-primary">
                            R$ {parseFloat(ingresso.valor).toFixed(2)}
                          </p>
                          <p className="text-xs text-gray-500">
                            {ingresso.quantidade_disponivel > 0 
                              ? `${ingresso.quantidade_disponivel} disponíveis`
                              : 'Esgotado'
                            }
                          </p>
                        </div>
                        <button
                          onClick={() => handleInscrever(ingresso.id)}
                          disabled={inscrevendo || ingresso.quantidade_disponivel <= 0}
                          className="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition text-sm font-medium disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center space-x-2"
                        >
                          {inscrevendo ? (
                            <>
                              <Loader2 className="animate-spin" size={16} />
                              <span>Inscrevendo...</span>
                            </>
                          ) : (
                            <span>Comprar</span>
                          )}
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              )}

              {/* Informações Adicionais */}
              <div className="mt-6 p-4 bg-blue-50 rounded-lg">
                <p className="text-sm text-blue-800">
                  <strong>💡 Dica:</strong> Faça login para garantir seu ingresso!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default EventDetail;
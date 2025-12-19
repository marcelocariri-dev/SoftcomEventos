// src/pages/Ingresso.jsx - Visualizar Ingresso

import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Ticket, Calendar, MapPin, Clock, User, Download, Share2, ArrowLeft, Loader2, CheckCircle } from 'lucide-react';
import inscricoesService from '../services/Inscricoes.service';
import authService from '../services/auth.service';

const Ingresso = () => {
  const { inscricaoId } = useParams();
  const navigate = useNavigate();
  
  const [inscricao, setInscricao] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    // Verificar autenticação
    if (!authService.isAuthenticated()) {
      navigate('/login');
      return;
    }

    loadInscricao();
  }, [inscricaoId, navigate]);

  const loadInscricao = async () => {
    try {
      setLoading(true);
      console.log('📤 Carregando inscrição ID:', inscricaoId);
      
      const response = await inscricoesService.getInscricaoById(inscricaoId);
      console.log('✅ Resposta completa:', response);
      
      // Laravel retorna: response.data.data
      const data = response?.data?.data || response?.data || response;
      console.log('✅ Dados extraídos:', data);
      
      setInscricao(data);
    } catch (err) {
      console.error('❌ Erro ao carregar ingresso:', err);
      setError('Erro ao carregar ingresso');
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    // Se vier formatado do Laravel, usar direto
    if (evento?.data_inicio_formatada) {
      const parts = evento.data_inicio_formatada.split(' ');
      if (parts[0]) {
        const [day, month, year] = parts[0].split('/');
        const date = new Date(year, month - 1, day);
        const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        const months = [
          'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
          'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
        ];
        return `${days[date.getDay()]}, ${day} de ${months[parseInt(month) - 1]} de ${year}`;
      }
    }
    
    // Fallback
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR', { 
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatTime = (dateString) => {
    // Se vier formatado do Laravel, extrair hora
    if (evento?.data_inicio_formatada) {
      const parts = evento.data_inicio_formatada.split(' ');
      if (parts[1]) {
        return parts[1]; // Já vem formatado: "16:00"
      }
    }
    
    // Fallback
    const date = new Date(dateString);
    return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
  };

  const handleDownload = () => {
    // TODO: Implementar download do ingresso em PDF
    alert('Download do ingresso em PDF em desenvolvimento');
  };

  const handleShare = () => {
    if (navigator.share) {
      navigator.share({
        title: `Ingresso - ${inscricao?.evento?.titulo}`,
        text: `Confira meu ingresso para ${inscricao?.evento?.titulo}!`,
        url: window.location.href,
      });
    } else {
      // Fallback: copiar link
      navigator.clipboard.writeText(window.location.href);
      alert('Link copiado para a área de transferência!');
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center mt-20">
        <Loader2 className="animate-spin h-12 w-12 text-primary" />
      </div>
    );
  }

  if (error || !inscricao) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center mt-20">
        <div className="text-center">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">
            {error || 'Ingresso não encontrado'}
          </h2>
          <button
            onClick={() => navigate('/admin')}
            className="text-primary hover:underline"
          >
            Ver meus ingressos
          </button>
        </div>
      </div>
    );
  }

  // Extrair dados com fallbacks e debug
  const evento = inscricao?.evento;
  const ingresso = inscricao?.ingresso;
  const participante = inscricao?.participante;
  const codigo_inscricao = inscricao?.codigo_inscricao;
  
  // Usar campos formatados do Laravel quando disponíveis
  const valorPago = inscricao?.ingresso?.valor_formatado || 
                   `R$ ${parseFloat(inscricao?.valor_pago || ingresso?.valor || 0).toFixed(2).replace('.', ',')}`;
  
  const dataInscricao = inscricao?.data_inscricao_formatada || 
                       (inscricao?.data_inscricao ? new Date(inscricao.data_inscricao).toLocaleString('pt-BR') : null);
  
  // Debug
  console.log('📊 Dados da inscrição:', {
    evento: evento?.titulo,
    ingresso: ingresso?.tipo_ingresso,
    participante: participante?.nome,
    codigo: codigo_inscricao,
    valor_pago: inscricao?.valor_pago,
    valor_formatado: valorPago,
    data_inscricao: dataInscricao,
    status: inscricao?.status_formatado
  });

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary/10 to-accent-purple/10 py-12 px-4 mt-20">
      <div className="max-w-3xl mx-auto">
        
        {/* Header */}
        <div className="mb-8 flex items-center justify-between">
          <button
            onClick={() => navigate('/admin')}
            className="flex items-center space-x-2 text-gray-600 hover:text-primary transition"
          >
            <ArrowLeft size={20} />
            <span>Meus Ingressos</span>
          </button>

          <div className="flex space-x-2">
            <button
              onClick={handleShare}
              className="p-2 bg-white rounded-lg hover:bg-gray-50 transition"
              title="Compartilhar"
            >
              <Share2 size={20} className="text-gray-600" />
            </button>
            <button
              onClick={handleDownload}
              className="p-2 bg-white rounded-lg hover:bg-gray-50 transition"
              title="Baixar PDF"
            >
              <Download size={20} className="text-gray-600" />
            </button>
          </div>
        </div>

        {/* Success Message */}
        <div className={`mb-8 p-6 border-2 rounded-2xl ${
          inscricao?.status === 'confirmado' 
            ? 'bg-green-50 border-green-200' 
            : inscricao?.status === 'pendente'
            ? 'bg-yellow-50 border-yellow-200'
            : 'bg-red-50 border-red-200'
        }`}>
          <div className="flex items-center space-x-3">
            <CheckCircle size={32} className={
              inscricao?.status === 'confirmado'
                ? 'text-green-600'
                : inscricao?.status === 'pendente'
                ? 'text-yellow-600'
                : 'text-red-600'
            } />
            <div>
              <h3 className={`text-xl font-bold ${
                inscricao?.status === 'confirmado'
                  ? 'text-green-900'
                  : inscricao?.status === 'pendente'
                  ? 'text-yellow-900'
                  : 'text-red-900'
              }`}>
                {inscricao?.status_formatado || 'Status desconhecido'}
              </h3>
              <p className={
                inscricao?.status === 'confirmado'
                  ? 'text-green-700'
                  : inscricao?.status === 'pendente'
                  ? 'text-yellow-700'
                  : 'text-red-700'
              }>
                {inscricao?.status === 'confirmado' 
                  ? 'Seu ingresso foi gerado com sucesso'
                  : inscricao?.status === 'pendente'
                  ? 'Aguardando confirmação de pagamento'
                  : 'Inscrição cancelada'
                }
              </p>
            </div>
          </div>
        </div>

        {/* Ingresso Card */}
        <div className="bg-white rounded-3xl shadow-2xl overflow-hidden">
          
          {/* Header do Ingresso */}
          <div className="bg-gradient-to-r from-primary to-accent-purple p-8 text-white">
            <div className="flex items-start justify-between mb-4">
              <div className="flex items-center space-x-3">
                <div className="p-3 bg-white/20 rounded-lg">
                  <Ticket size={32} />
                </div>
                <div>
                  <p className="text-sm opacity-90">Ingresso</p>
                  <h2 className="text-2xl font-bold">{ingresso?.tipo_ingresso}</h2>
                </div>
              </div>
              <div className="text-right">
                <p className="text-sm opacity-90">Código</p>
                <p className="text-xl font-bold font-mono">{codigo_inscricao}</p>
              </div>
            </div>

            <h1 className="text-3xl font-bold mb-2">{evento?.titulo}</h1>
          </div>

          {/* Conteúdo do Ingresso */}
          <div className="p-8">
            
            {/* Informações do Evento */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
              <div className="flex items-start space-x-4">
                <div className="p-3 bg-primary/10 rounded-lg">
                  <Calendar className="text-primary" size={24} />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Data</p>
                  <p className="font-semibold text-gray-900">
                    {formatDate(evento?.data_inicio)}
                  </p>
                </div>
              </div>

              <div className="flex items-start space-x-4">
                <div className="p-3 bg-primary/10 rounded-lg">
                  <Clock className="text-primary" size={24} />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Horário</p>
                  <p className="font-semibold text-gray-900">
                    {formatTime(evento?.data_inicio)}
                  </p>
                </div>
              </div>

              {evento?.local && (
                <div className="flex items-start space-x-4 md:col-span-2">
                  <div className="p-3 bg-primary/10 rounded-lg">
                    <MapPin className="text-primary" size={24} />
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">Local</p>
                    <p className="font-semibold text-gray-900">{evento.local.nome}</p>
                    <p className="text-sm text-gray-600">
                      {evento.local.endereco}, {evento.local.numero} - {evento.local.bairro}
                    </p>
                    <p className="text-sm text-gray-600">
                      {evento.local.cidade} - {evento.local.estado}
                    </p>
                  </div>
                </div>
              )}

              <div className="flex items-start space-x-4">
                <div className="p-3 bg-primary/10 rounded-lg">
                  <User className="text-primary" size={24} />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Participante</p>
                  <p className="font-semibold text-gray-900">
                    {participante?.nome || authService.getCurrentUser()?.name}
                  </p>
                  {participante?.cpf && (
                    <p className="text-sm text-gray-600">
                      CPF: {participante.cpf}
                    </p>
                  )}
                </div>
              </div>

              <div className="flex items-start space-x-4">
                <div className="p-3 bg-primary/10 rounded-lg">
                  <Ticket className="text-primary" size={24} />
                </div>
                <div>
                  <p className="text-sm text-gray-600">Valor</p>
                  <p className="font-semibold text-gray-900">
                    {valorPago}
                  </p>
                </div>
              </div>
            </div>

            {/* QR Code Placeholder */}
            <div className="border-t pt-8">
              <div className="flex flex-col items-center">
                <div className="w-64 h-64 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                  <div className="text-center">
                    <div className="w-48 h-48 bg-white border-4 border-gray-300 mx-auto mb-4 flex items-center justify-center">
                      <p className="text-gray-400 text-sm">QR Code</p>
                    </div>
                  </div>
                </div>
                <p className="text-center text-gray-600 mb-2">
                  Apresente este código no dia do evento
                </p>
                <p className="text-center font-mono text-lg font-bold text-gray-900">
                  {codigo_inscricao}
                </p>
              </div>
            </div>

            {/* Instruções */}
            <div className="mt-8 p-6 bg-blue-50 rounded-xl">
              <h4 className="font-bold text-blue-900 mb-3">Instruções Importantes:</h4>
              <ul className="space-y-2 text-sm text-blue-800">
                <li className="flex items-start">
                  <span className="mr-2">•</span>
                  <span>Apresente este ingresso (digital ou impresso) na entrada do evento</span>
                </li>
                <li className="flex items-start">
                  <span className="mr-2">•</span>
                  <span>Chegue com antecedência para evitar filas</span>
                </li>
                <li className="flex items-start">
                  <span className="mr-2">•</span>
                  <span>Guarde este ingresso até o final do evento</span>
                </li>
                <li className="flex items-start">
                  <span className="mr-2">•</span>
                  <span>Em caso de dúvidas, entre em contato com a organização</span>
                </li>
              </ul>
            </div>

            {/* Botões de Ação */}
            <div className="mt-8 flex flex-col sm:flex-row gap-4">
              <button
                onClick={handleDownload}
                className="flex-1 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium flex items-center justify-center space-x-2"
              >
                <Download size={20} />
                <span>Baixar PDF</span>
              </button>
              <button
                onClick={() => navigate(`/evento/${evento?.id}`)}
                className="flex-1 px-6 py-3 border-2 border-primary text-primary rounded-lg hover:bg-primary/5 transition font-medium"
              >
                Ver Evento
              </button>
            </div>
          </div>
        </div>

        {/* Footer Info */}
        <div className="mt-8 text-center text-sm text-gray-600">
          {dataInscricao && (
            <p>Inscrição realizada em {dataInscricao}</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default Ingresso;
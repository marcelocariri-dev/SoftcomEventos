// src/pages/Home.jsx - Listagem Real do Backend

import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Search, Filter, ChevronDown, Calendar, MapPin, Loader2 } from 'lucide-react';
import eventoService from '../services/evento.service';

const Home = () => {
  const navigate = useNavigate();
  
  const [eventos, setEventos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedEstado, setSelectedEstado] = useState('');
  const [selectedCategoria, setSelectedCategoria] = useState('');
  const [error, setError] = useState('');

  const estados = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
    'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
    'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
  ];

  const categorias = [
    'Show', 'Festival', 'Teatro', 'Esporte', 'Workshop',
    'Conferência', 'Festa', 'Exposição', 'Outros'
  ];

  useEffect(() => {
    loadEventos();
  }, []);

  const loadEventos = async () => {
    try {
      setLoading(true);
      setError('');
      
      const response = await eventoService.getEventos();
      console.log('Eventos carregados:', response);
      
      const eventosData = response.data || response || [];
      setEventos(Array.isArray(eventosData) ? eventosData : []);
      
    } catch (err) {
      console.error('Erro ao carregar eventos:', err);
      setError('Erro ao carregar eventos. Tente novamente.');
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = async (e) => {
    e.preventDefault();
    
    try {
      setLoading(true);
      setError('');
      
      const params = {};
      if (searchTerm) params.search = searchTerm;
      if (selectedEstado) params.estado = selectedEstado;
      if (selectedCategoria) params.categoria = selectedCategoria;
      
      const response = await eventoService.getEventos(params);
      const eventosData = response.data || response || [];
      setEventos(Array.isArray(eventosData) ? eventosData : []);
      
    } catch (err) {
      console.error('Erro ao buscar eventos:', err);
      setError('Erro ao buscar eventos. Tente novamente.');
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    const months = [
      'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
      'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];
    
    return `${days[date.getDay()]}, ${date.getDate()} de ${months[date.getMonth()]}`;
  };

  const handleEventoClick = (eventoId) => {
    navigate(`/evento/${eventoId}`);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Section */}
      <section className="bg-gradient-to-br from-primary via-primary-dark to-accent-purple text-white py-20 mt-20">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-4xl md:text-5xl font-bold mb-6">
              Ouse ir além e viva o novo
            </h1>
            <p className="text-xl mb-8 text-white/90">
              Descubra os melhores eventos da sua região
            </p>

            {/* Search Bar */}
            <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
              <div className="flex flex-col md:flex-row gap-3">
                <div className="flex-1 relative">
                  <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
                  <input
                    type="text"
                    placeholder="Buscar eventos..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-12 pr-4 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white"
                  />
                </div>
                <button
                  type="submit"
                  className="px-8 py-4 bg-white text-primary font-semibold rounded-lg hover:bg-gray-100 transition"
                >
                  Buscar
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>

      {/* Filters */}
      <section className="bg-white border-b">
        <div className="container mx-auto px-4 py-6">
          <div className="flex flex-col md:flex-row gap-4">
            {/* Estado Filter */}
            <div className="flex-1">
              <div className="relative">
                <select
                  value={selectedEstado}
                  onChange={(e) => setSelectedEstado(e.target.value)}
                  className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  <option value="">Todos os estados</option>
                  {estados.map(estado => (
                    <option key={estado} value={estado}>{estado}</option>
                  ))}
                </select>
                <ChevronDown className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" size={20} />
              </div>
            </div>

            {/* Categoria Filter */}
            <div className="flex-1">
              <div className="relative">
                <select
                  value={selectedCategoria}
                  onChange={(e) => setSelectedCategoria(e.target.value)}
                  className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  <option value="">Todas as categorias</option>
                  {categorias.map(categoria => (
                    <option key={categoria} value={categoria}>{categoria}</option>
                  ))}
                </select>
                <ChevronDown className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" size={20} />
              </div>
            </div>

            {/* Aplicar Filtros */}
            <button
              onClick={handleSearch}
              className="px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium"
            >
              Filtrar
            </button>
          </div>
        </div>
      </section>

      {/* Error Message */}
      {error && (
        <div className="container mx-auto px-4 py-4">
          <div className="p-4 bg-red-50 border border-red-200 rounded-lg">
            <p className="text-red-600">{error}</p>
          </div>
        </div>
      )}

      {/* Events Grid */}
      <section className="container mx-auto px-4 py-12">
        <div className="mb-8">
          <h2 className="text-3xl font-bold text-gray-900 mb-2">
            Próximos eventos
          </h2>
          <p className="text-gray-600">
            {eventos.length} evento(s) encontrado(s)
          </p>
        </div>

        {loading ? (
          <div className="flex justify-center items-center py-20">
            <Loader2 className="animate-spin h-12 w-12 text-primary" />
          </div>
        ) : eventos.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {eventos.map(evento => (
              <div
                key={evento.id}
                onClick={() => handleEventoClick(evento.id)}
                className="group block bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer"
              >
                {/* Imagem do evento */}
                <div className="relative h-48 overflow-hidden bg-gray-200">
                  <img
                    src={evento.imagem || 'https://via.placeholder.com/400x250/FF6B35/FFFFFF?text=Evento'}
                    alt={evento.titulo}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                    onError={(e) => {
                      e.target.src = 'https://via.placeholder.com/400x250/FF6B35/FFFFFF?text=Evento';
                    }}
                  />
                  
                  {evento.status === 'publicado' && (
                    <div className="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                      Disponível
                    </div>
                  )}

                  {evento.status === 'privado' && (
                    <div className="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                      Privado
                    </div>
                  )}
                </div>

                {/* Conteúdo */}
                <div className="p-4">
                  <h3 className="text-lg font-bold text-gray-900 group-hover:text-primary transition mb-2 line-clamp-2">
                    {evento.titulo}
                  </h3>

                  <div className="space-y-2 text-sm text-gray-600">
                    <div className="flex items-center">
                      <Calendar className="w-4 h-4 mr-2 text-primary" />
                      <span>{formatDate(evento.data_inicio)}</span>
                    </div>

                    {evento.local && (
                      <div className="flex items-center">
                        <MapPin className="w-4 h-4 mr-2 text-primary" />
                        <span className="line-clamp-1">
                          {evento.local.cidade}, {evento.local.estado}
                        </span>
                      </div>
                    )}
                  </div>

                  {/* Preço */}
                  {evento.valor_padrao && (
                    <div className="mt-4 pt-4 border-t border-gray-100">
                      <div className="flex items-center justify-between">
                        <span className="text-gray-600 text-sm">A partir de</span>
                        <span className="text-primary font-bold text-lg">
                          R$ {parseFloat(evento.valor_padrao).toFixed(2)}
                        </span>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center py-20">
            <Filter size={48} className="mx-auto text-gray-300 mb-4" />
            <h3 className="text-xl font-semibold text-gray-900 mb-2">
              Nenhum evento encontrado
            </h3>
            <p className="text-gray-600 mb-6">
              Tente ajustar os filtros ou buscar por outros termos
            </p>
            <button
              onClick={() => {
                setSearchTerm('');
                setSelectedEstado('');
                setSelectedCategoria('');
                loadEventos();
              }}
              className="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition"
            >
              Limpar filtros
            </button>
          </div>
        )}
      </section>

      {/* CTA Section */}
      <section className="bg-gradient-to-r from-primary to-accent-purple text-white py-16">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Gerencie e venda seus eventos sem sair do celular
          </h2>
          <p className="text-xl mb-8 text-white/90">
            Conheça os detalhes de como transformar a sua gestão de eventos
          </p>
          <a
            href="/criar-evento"
            className="inline-block px-8 py-4 bg-white text-primary font-semibold rounded-lg hover:bg-gray-100 transition text-lg"
          >
            Criar meu evento
          </a>
        </div>
      </section>
    </div>
  );
};

export default Home;
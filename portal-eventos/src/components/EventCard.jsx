// src/components/EventCard.jsx

import React from 'react';
import { Link } from 'react-router-dom';
import { Calendar, MapPin } from 'lucide-react';

const EventCard = ({ evento }) => {
  const formatDate = (dateString) => {
    const date = new Date(dateString);
    const days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    const months = [
      'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
      'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];
    
    return `${days[date.getDay()]}, ${date.getDate()} de ${months[date.getMonth()]}`;
  };

  const getImageUrl = (evento) => {
    if (evento.imagem) {
      return evento.imagem;
    }
    return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="250" viewBox="0 0 400 250"%3E%3Crect width="400" height="250" fill="%23f3f4f6"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="20" fill="%239ca3af"%3ESem imagem%3C/text%3E%3C/svg%3E';
  };

  return (
    <Link 
      to={`/evento/${evento.id}`}
      className="group block bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300"
    >
      {/* Imagem do evento */}
      <div className="relative h-48 overflow-hidden bg-gray-200">
        <img
          src={getImageUrl(evento)}
          alt={evento.titulo}
          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
          onError={(e) => {
            e.target.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="250" viewBox="0 0 400 250"%3E%3Crect width="400" height="250" fill="%23f3f4f6"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="20" fill="%239ca3af"%3ESem imagem%3C/text%3E%3C/svg%3E';
          }}
        />
        
        {/* Badge de status */}
        {evento.status === 'publicado' && (
          <div className="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
            Disponível
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
            <Calendar size={16} className="mr-2 text-primary" />
            <span>{formatDate(evento.data_inicio)}</span>
          </div>

          {evento.local && (
            <div className="flex items-center">
              <MapPin size={16} className="mr-2 text-primary" />
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
    </Link>
  );
};

export default EventCard;

// src/components/Header.jsx - VERSÃO SIMPLIFICADA

import React, { useState } from 'react';
import { Menu, X, User } from 'lucide-react';

const Header = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <header className="bg-white shadow-md fixed w-full top-0 z-50">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <a href="/" className="flex items-center">
            <div className="text-3xl font-bold text-primary">
              Event<span className="text-secondary">Go</span>
            </div>
          </a>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center space-x-8">
            <a href="/" className="text-gray-700 hover:text-primary transition">
              Eventos
            </a>
            <a href="/sobre" className="text-gray-700 hover:text-primary transition">
              Sobre nós
            </a>
            <a href="/produtores" className="text-gray-700 hover:text-primary transition">
              Para produtores
            </a>
          </nav>

          {/* Desktop Actions */}
          <div className="hidden md:flex items-center space-x-4">
            <a
              href="/conta"
              className="px-4 py-2 rounded-lg border border-gray-300 hover:border-primary transition"
            >
              Entrar
            </a>
            <a
              href="/criar-evento"
              className="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium"
            >
              Criar evento
            </a>
          </div>

          {/* Mobile Menu Button */}
          <button
            className="md:hidden"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Mobile Menu */}
        {isMenuOpen && (
          <div className="md:hidden py-4 border-t">
            <nav className="flex flex-col space-y-4">
              <a
                href="/"
                className="text-gray-700 hover:text-primary transition"
                onClick={() => setIsMenuOpen(false)}
              >
                Eventos
              </a>
              <a
                href="/sobre"
                className="text-gray-700 hover:text-primary transition"
                onClick={() => setIsMenuOpen(false)}
              >
                Sobre nós
              </a>
              <a
                href="/produtores"
                className="text-gray-700 hover:text-primary transition"
                onClick={() => setIsMenuOpen(false)}
              >
                Para produtores
              </a>

              <div className="pt-4 border-t space-y-2">
                <a
                  href="/conta"
                  className="block px-4 py-2 border border-gray-300 rounded-lg text-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  Entrar
                </a>
                <a
                  href="/criar-evento"
                  className="block px-4 py-2 bg-primary text-white rounded-lg text-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  Criar evento
                </a>
              </div>
            </nav>
          </div>
        )}
      </div>
    </header>
  );
};

export default Header;
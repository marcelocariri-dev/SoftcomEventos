// src/components/Footer.jsx - VERSÃO SIMPLIFICADA

import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-secondary text-white mt-20">
      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* EventGo */}
          <div>
            <h3 className="text-xl font-bold mb-4">EventGo</h3>
            <ul className="space-y-2">
              <li>
                <a href="/sobre" className="text-gray-300 hover:text-white transition">
                  Sobre nós
                </a>
              </li>
              <li>
                <a href="/produtores" className="text-gray-300 hover:text-white transition">
                  Para produtores
                </a>
              </li>
              <li>
                <a href="#" className="text-gray-300 hover:text-white transition">
                  Trabalhe conosco
                </a>
              </li>
              <li>
                <a href="/termos" className="text-gray-300 hover:text-white transition">
                  Termos de uso
                </a>
              </li>
              <li>
                <a href="/privacidade" className="text-gray-300 hover:text-white transition">
                  Política de privacidade
                </a>
              </li>
            </ul>
          </div>

          {/* Precisa de ajuda */}
          <div>
            <h3 className="text-xl font-bold mb-4">Precisa de ajuda?</h3>
            <ul className="space-y-2 text-gray-300">
              <li>
                <strong>Suporte para compradores:</strong>
                <br />
                (84) 99858-5364
                <br />
                <a href="https://wa.me/5584998585364" className="text-primary hover:text-primary-light">
                  WhatsApp
                </a>
              </li>
              <li>
                <strong>Suporte para produtores:</strong>
                <br />
                (84) 98631-8803
                <br />
                <a href="https://wa.me/5584986318803" className="text-primary hover:text-primary-light">
                  WhatsApp
                </a>
              </li>
              <li>
                <a href="mailto:contato@eventgo.com.br" className="hover:text-white transition">
                  contato@eventgo.com.br
                </a>
              </li>
            </ul>
          </div>

          {/* Redes sociais */}
          <div>
            <h3 className="text-xl font-bold mb-4">Siga-nos</h3>
            <div className="flex space-x-4">
              <a
                href="#"
                className="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-primary transition"
              >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
              <a
                href="#"
                className="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-primary transition"
              >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
            </div>
          </div>

          {/* App Download */}
          <div>
            <h3 className="text-xl font-bold mb-4">Baixe o app</h3>
            <div className="space-y-3">
              <a href="#" className="block">
                <div className="bg-black text-white px-4 py-2 rounded-lg text-sm">
                  📱 App Store
                </div>
              </a>
              <a href="#" className="block">
                <div className="bg-black text-white px-4 py-2 rounded-lg text-sm">
                  📱 Google Play
                </div>
              </a>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
          <p>Copyright © 2025 EventGo - Seu Portal de Eventos</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
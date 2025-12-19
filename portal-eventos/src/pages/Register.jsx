// src/pages/Register.jsx - Integrado com Backend

import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Eye, EyeOff, Check, X, Loader } from 'lucide-react';
import authService from '../services/auth.service';

const Register = () => {
  const navigate = useNavigate();
  
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  // Validações de senha
  const passwordValidations = {
    minLength: formData.password.length >= 8,
    hasUpperCase: /[A-Z]/.test(formData.password),
    hasLowerCase: /[a-z]/.test(formData.password),
    hasNumber: /[0-9]/.test(formData.password),
  };

  const isPasswordValid = Object.values(passwordValidations).every(v => v);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
    setError('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!isPasswordValid) {
      setError('A senha não atende aos requisitos mínimos');
      return;
    }

    if (formData.password !== formData.password_confirmation) {
      setError('As senhas não coincidem');
      return;
    }

    try {
      setLoading(true);
      setError('');
      
      // Registrar usando authService
      await authService.register({
        name: formData.name,
        email: formData.email,
        password: formData.password,
      });

      setSuccess('Conta criada com sucesso! Redirecionando...');
      
      setTimeout(() => {
        navigate('/admin');
      }, 1500);
      
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 mt-20">
      <div className="max-w-md w-full">
        <div className="bg-white rounded-2xl shadow-xl p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold text-gray-900">
              Vamos criar sua conta
            </h2>
            <p className="mt-2 text-gray-600">
              Junte-se ao EventGo
            </p>
          </div>

          {/* Success Message */}
          {success && (
            <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
              <p className="text-green-600 text-sm">{success}</p>
            </div>
          )}

          {/* Error Message */}
          {error && (
            <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
              <p className="text-red-600 text-sm">{error}</p>
            </div>
          )}

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Nome */}
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                Nome completo
              </label>
              <input
                id="name"
                name="name"
                type="text"
                required
                value={formData.name}
                onChange={handleChange}
                disabled={loading}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition disabled:bg-gray-100"
                placeholder="Seu nome completo"
              />
            </div>

            {/* Email */}
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                E-mail
              </label>
              <input
                id="email"
                name="email"
                type="email"
                required
                value={formData.email}
                onChange={handleChange}
                disabled={loading}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition disabled:bg-gray-100"
                placeholder="seu@email.com"
              />
            </div>

            {/* Senha */}
            <div>
              <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                Senha
              </label>
              <div className="relative">
                <input
                  id="password"
                  name="password"
                  type={showPassword ? 'text' : 'password'}
                  required
                  value={formData.password}
                  onChange={handleChange}
                  disabled={loading}
                  className="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition disabled:bg-gray-100"
                  placeholder="Sua senha"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  disabled={loading}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                  {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                </button>
              </div>

              {/* Password Requirements */}
              <div className="mt-4 space-y-2">
                <p className="text-sm font-medium text-gray-700">Sua senha deve ter:</p>
                <div className="space-y-1">
                  <PasswordRequirement
                    met={passwordValidations.minLength}
                    text="Pelo menos 8 caracteres"
                  />
                  <PasswordRequirement
                    met={passwordValidations.hasUpperCase}
                    text="Letras maiúsculas"
                  />
                  <PasswordRequirement
                    met={passwordValidations.hasLowerCase}
                    text="Letras minúsculas"
                  />
                  <PasswordRequirement
                    met={passwordValidations.hasNumber}
                    text="Números"
                  />
                </div>
              </div>
            </div>

            {/* Confirmar Senha */}
            <div>
              <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 mb-2">
                Confirmar senha
              </label>
              <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                value={formData.password_confirmation}
                onChange={handleChange}
                disabled={loading}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition disabled:bg-gray-100"
                placeholder="Confirme sua senha"
              />
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              disabled={loading || !isPasswordValid}
              className="w-full py-3 px-4 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
            >
              {loading ? (
                <>
                  <Loader size={20} className="animate-spin" />
                  <span>Criando conta...</span>
                </>
              ) : (
                <span>Criar minha conta</span>
              )}
            </button>

            {/* Login Link */}
            <div className="text-center">
              <p className="text-gray-600">
                Já tem uma conta?{' '}
                <a
                  href="/login"
                  className="text-primary hover:text-primary-dark font-medium"
                >
                  Fazer login
                </a>
              </p>
            </div>

            {/* Terms */}
            <p className="text-xs text-gray-500 text-center">
              Ao usar este serviço, você aceita nossos{' '}
              <a href="/termos" className="text-primary hover:underline">
                termos de uso
              </a>{' '}
              e{' '}
              <a href="/privacidade" className="text-primary hover:underline">
                política de privacidade
              </a>
              .
            </p>
          </form>
        </div>
      </div>
    </div>
  );
};

// Componente auxiliar para mostrar requisitos da senha
const PasswordRequirement = ({ met, text }) => (
  <div className="flex items-center text-sm">
    {met ? (
      <Check size={16} className="text-green-500 mr-2" />
    ) : (
      <X size={16} className="text-gray-300 mr-2" />
    )}
    <span className={met ? 'text-green-700' : 'text-gray-500'}>{text}</span>
  </div>
);

export default Register;
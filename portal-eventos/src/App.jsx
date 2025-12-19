// src/App.jsx - Completo com todas as rotas

import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Header from './components/Header';
import Footer from './components/Footer';
import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import EventDetail from './pages/EventDetail';
import Ingresso from './pages/Ingresso';
import Admin from './pages/Admin';
import CreateEvent from './pages/CreateEvent';
//import EditEvent from './pages/EditEvent';

import authService from './services/auth.service';


// Protected Route Component
const ProtectedRoute = ({ children }) => {
  const isAuthenticated = authService.isAuthenticated();
  
  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }
  
  return children;
};

function App() {
  return (
    <Router>
      <div className="flex flex-col min-h-screen">
        <Header />
        
        <main className="flex-grow">
          <Routes>
            {/* Rotas Públicas */}
            <Route path="/" element={<Home />} />
            <Route path="/login" element={<Login />} />
            <Route path="/conta" element={<Register />} />
            <Route path="/evento/:id" element={<EventDetail />} />
            
            {/* Rotas Protegidas */}
            <Route 
              path="/admin" 
              element={
                <ProtectedRoute>
                  <Admin />
                </ProtectedRoute>
              } 
            />

            <Route 
              path="/criar-evento" 
              element={
                <ProtectedRoute>
                  <CreateEvent />
                </ProtectedRoute>
              } 
            />

            

            <Route 
              path="/ingresso/:inscricaoId" 
              element={
                <ProtectedRoute>
                  <Ingresso />
                </ProtectedRoute>
              } 
            />
            
            {/* Redirecionar páginas não encontradas para Home */}
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </main>
        
        <Footer />
      </div>
    </Router>
  );
}

export default App;
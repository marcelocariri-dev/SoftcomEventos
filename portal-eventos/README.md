# 🎉 EventGo - Frontend React + Vite

Portal de eventos moderno desenvolvido com React, Vite e Tailwind CSS, integrado com backend Laravel.

![React](https://img.shields.io/badge/React-18.2-61DAFB?logo=react)
![Vite](https://img.shields.io/badge/Vite-5.0-646CFF?logo=vite)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-38B2AC?logo=tailwind-css)

## 🚀 Características

- ⚡ **Vite** - Build tool ultra-rápido
- ⚛️ **React 18** - Biblioteca JavaScript moderna
- 🎨 **Tailwind CSS 3** - Framework CSS utility-first
- 🗺️ **React Router 6** - Navegação SPA
- 🔒 **JWT Authentication** - Autenticação segura
- 📱 **Responsive Design** - Mobile-first
- 🎯 **Design baseado em Outgo.com.br**

## 📁 Estrutura do Projeto

```
eventgo-frontend/
├── public/
│   └── vite.svg
├── src/
│   ├── components/
│   │   ├── Header.jsx          # Cabeçalho com navegação
│   │   ├── Footer.jsx          # Rodapé
│   │   └── EventCard.jsx       # Card de evento
│   ├── pages/
│   │   ├── Home.jsx            # Página inicial
│   │   ├── EventDetail.jsx     # Detalhes do evento
│   │   ├── Register.jsx        # Criar conta
│   │   ├── Login.jsx           # Login
│   │   └── Profile.jsx         # Perfil do usuário
│   ├── services/
│   │   ├── api.service.js      # Serviço base de API
│   │   ├── auth.service.js     # Autenticação
│   │   └── evento.service.js   # Eventos
│   ├── config/
│   │   └── api.js              # Configuração de endpoints
│   ├── App.jsx                 # Componente principal
│   ├── main.jsx                # Entry point
│   └── index.css               # Estilos globais
├── .env                        # Variáveis de ambiente
├── .env.example                # Exemplo de variáveis
├── index.html                  # HTML principal
├── vite.config.js              # Configuração do Vite
├── tailwind.config.js          # Configuração do Tailwind
├── postcss.config.js           # Configuração do PostCSS
└── package.json                # Dependências
```

## 🛠️ Instalação

### Pré-requisitos

- Node.js 16+
- npm ou yarn
- Backend Laravel rodando

### Passo a Passo

1. **Criar projeto com Vite:**
```bash
npm create vite@latest eventgo-frontend -- --template react
cd eventgo-frontend
```

2. **Copiar arquivos do projeto:**
   - Copie todos os arquivos fornecidos para a pasta do projeto

3. **Instalar dependências:**
```bash
npm install
```

4. **Configurar variáveis de ambiente:**
```bash
cp .env.example .env
```

Edite `.env`:
```env
VITE_API_URL=http://localhost:8000/api
```

5. **Iniciar servidor de desenvolvimento:**
```bash
npm run dev
```

Acesse: **http://localhost:3000**

## 📦 Scripts Disponíveis

```bash
# Desenvolvimento
npm run dev

# Build para produção
npm run build

# Preview do build
npm run preview

# Lint
npm run lint
```

## 🎨 Páginas Implementadas

### 🏠 **Home** (`/`)
- Hero section com busca
- Filtros por estado e categoria
- Grid de eventos
- CTA para produtores

### 📝 **Criar Conta** (`/conta`)
- Formulário de registro
- Validação de senha em tempo real
- Requisitos de senha visuais

### 🔐 **Login** (`/login`)
- Autenticação com email/senha
- Toggle de senha
- Lembrar-me

### 🎫 **Detalhes do Evento** (`/evento/:id`)
- Informações completas do evento
- Lista de ingressos disponíveis
- Compra de ingressos
- Localização e horário

### 👤 **Perfil** (`/perfil`)
- Visualização de dados
- Edição de informações
- Protegido por autenticação

## 🔗 Integração com Backend Laravel

### Endpoints Utilizados

```javascript
// Auth
POST   /api/register
POST   /api/login
POST   /api/logout
GET    /api/me

// Eventos
GET    /api/eventos
GET    /api/eventos/:id
POST   /api/eventos/buscar
GET    /api/eventos/ativos

// Ingressos
GET    /api/ingressos/evento/:eventoId

// Perfil
GET    /api/perfil
PUT    /api/perfil
```

### Configurar CORS no Laravel

Edite `config/cors.php`:

```php
return [
    'paths' => ['api/*'],
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

Não esqueça de reiniciar o Laravel:
```bash
php artisan config:clear
php artisan serve
```

## 🎨 Personalização

### Cores

Edite `tailwind.config.js`:

```javascript
colors: {
  primary: {
    DEFAULT: '#FF6B35',  // Laranja
    dark: '#E85A2A',
    light: '#FF8555',
  },
  secondary: {
    DEFAULT: '#1A1A2E',  // Azul escuro
    light: '#2A2A3E',
  },
  accent: {
    purple: '#9D4EDD',
    blue: '#3A86FF',
  }
}
```

### Porta do Servidor

Edite `vite.config.js`:

```javascript
server: {
  port: 3000,  // Altere aqui
}
```

## 🔒 Autenticação

O sistema usa **JWT tokens** armazenados no `localStorage`:

```javascript
// Login
authService.login(email, password)

// Verificar se está autenticado
authService.isAuthenticated()

// Obter usuário atual
authService.getCurrentUser()

// Logout
authService.logout()
```

### Rotas Protegidas

Use o componente `ProtectedRoute`:

```jsx
<Route
  path="/perfil"
  element={
    <ProtectedRoute>
      <Profile />
    </ProtectedRoute>
  }
/>
```

## 📱 Responsividade

O projeto é totalmente responsivo:

| Breakpoint | Tamanho | Colunas |
|------------|---------|---------|
| Mobile | < 768px | 1 |
| Tablet | 768px - 1023px | 2 |
| Desktop | 1024px - 1279px | 3 |
| Large | ≥ 1280px | 4 |

## 🚀 Build para Produção

```bash
# Build
npm run build

# Preview local
npm run preview
```

### Deploy

**Netlify:**
```bash
npm run build
netlify deploy --prod --dir=dist
```

**Vercel:**
```bash
npm run build
vercel --prod
```

**Servidor Apache/Nginx:**
```bash
npm run build
# Copie a pasta dist/ para o servidor
```

## 🐛 Troubleshooting

### Erro de CORS
- Verifique `config/cors.php` no Laravel
- Adicione a origem correta (ex: http://localhost:3000)
- Reinicie o servidor Laravel

### Tailwind não funciona
- Verifique se `index.css` tem as diretivas `@tailwind`
- Certifique-se de que `tailwind.config.js` está correto
- Reinicie o servidor Vite

### Porta em uso
```bash
# Linux/Mac
kill -9 $(lsof -t -i:3000)

# Windows
netstat -ano | findstr :3000
taskkill /PID <PID> /F
```

### API não responde
- Verifique se o Laravel está rodando
- Confirme a URL no `.env`
- Verifique o console do navegador

## 📚 Tecnologias

- **React 18.2** - UI Library
- **Vite 5.0** - Build Tool
- **React Router 6.21** - Routing
- **Tailwind CSS 3.4.1** - Styling
- **Lucide React** - Icons
- **PostCSS** - CSS Processing
- **Autoprefixer** - CSS Vendor Prefixes

## 🎯 Próximas Features

- [ ] Carrinho de compras
- [ ] Checkout de ingressos
- [ ] Painel do produtor
- [ ] Sistema de favoritos
- [ ] Compartilhamento social
- [ ] Notificações
- [ ] Recuperação de senha
- [ ] Histórico de compras
- [ ] Avaliações de eventos

## 📄 Licença

MIT License

## 👥 Suporte

Para dúvidas ou problemas:
- Consulte a documentação
- Abra uma issue no repositório
- Entre em contato com a equipe

---

**Desenvolvido com ❤️ usando React + Vite + Tailwind CSS**

🚀 **Pronto para produção!**

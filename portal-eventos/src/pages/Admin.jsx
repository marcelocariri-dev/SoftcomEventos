import React from 'react';

const eventosAnteriores = [
  {
    slug: "dynamus",
    titulo: "Dynamus",
    data: "Sexta, 24 de outubro",
    local: "Nísia Floresta",
    imagem: "https://imagens.outgo.com.br/events/208436/thumb_md_b898ae10-320a-4896-b7a1-4927a69440f8.jpeg",
    privado: true,
    ingressos: 1
  },
  {
    slug: "voluntarios-conferencia-gps",
    titulo: "Voluntários Conferência GPS",
    data: "Sábado, 11 de outubro",
    local: "João Pessoa",
    imagem: "https://imagens.outgo.com.br/events/191738/thumb_md_8ce7de7f-a8f5-47f5-8854-84fcf898326f.jpeg",
    privado: true,
    ingressos: 1
  },
  {
    slug: "conferencia-provincial-juventude",
    titulo: "Conferência Provincial Juventude",
    data: "Sexta, 26 de setembro",
    local: "João Pessoa",
    imagem: "https://imagens.outgo.com.br/events/140720/thumb_md_bc178981-bdb3-43d1-8aaf-3042998e5815.jpeg"
  },
  {
    slug: "conferencia-de-pentecostes",
    titulo: "Conferência de Pentecostes",
    data: "Sexta, 6 de junho",
    local: "João Pessoa",
    imagem: "https://imagens.outgo.com.br/events/84257/thumb_md_95822747-a51c-4bec-881e-563b30e75443.jpeg",
    privado: true
  },
  {
    slug: "conferencia-sobrenatural",
    titulo: "Conferência Sobrenatural",
    data: "Sexta, 2 de maio",
    local: "João Pessoa",
    imagem: "/assets/images/avatar-evento.png"
  },
  {
    slug: "fds-one",
    titulo: "FDS ONE",
    data: "Sexta, 14 de fevereiro",
    local: "Conde",
    imagem: "https://imagens.outgo.com.br/events/64306/thumb_md_dbf73896-81e5-40f4-8c80-569b3c49b6c1.jpeg"
  },
  {
    slug: "pos-virada",
    titulo: "Pós Virada",
    data: "Quarta, 1 de janeiro",
    local: "João Pessoa",
    imagem: "https://imagens.outgo.com.br/events/66043/thumb_md_7a74125a-6e6a-4fa4-a237-754bbf2e1ca0.jpeg",
    privado: true
  },
];

function Admin() {
  return (
    <div className="min-h-screen bg-gray-900 text-white">
      {/* Header */}
      <header className="border-b border-gray-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
          <div className="flex items-center space-x-8">
            <h1 className="text-2xl font-bold text-red-600">outgo</h1>
            <nav className="hidden md:flex space-x-8">
              <a href="#" className="hover:text-red-500 transition">Eventos</a>
              <a href="#" className="hover:text-red-500 transition">Para produtores</a>
            </nav>
          </div>
          <div className="flex items-center space-x-4">
            <a href="/criar-evento" className="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-full font-medium transition">
              Criar evento
            </a>
            <div className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                <span className="text-lg font-bold">M</span>
              </div>
              <span className="font-medium">Marcelo</span>
            </div>
          </div>
        </div>
      </header>

      {/* Perfil Header */}
      <section className="bg-gray-800 py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
          <div className="flex items-center space-x-6">
            <div className="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center text-4xl font-bold">
              M
            </div>
            <div>
              <h2 className="text-3xl font-bold">Marcelo Cariri Da Costa Queiroz</h2>
              <p className="text-gray-400">marcelocariridacostaqueiroz</p>
            </div>
          </div>
          <div className="flex space-x-4">
            <button className="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-full font-medium transition">
              Editar perfil
            </button>
            <button className="border border-gray-600 hover:border-gray-500 px-6 py-2 rounded-full font-medium transition">
              Sair
            </button>
          </div>
        </div>
      </section>

      {/* Eventos Anteriores */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h3 className="text-2xl font-bold mb-8">Eventos anteriores</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {eventosAnteriores.map((evento) => (
              <a
                key={evento.slug}
                href={`/${evento.slug}`}
                className="block group"
              >
                <article className="bg-gray-800 rounded-lg overflow-hidden hover:ring-2 hover:ring-red-600 transition">
                  <div className="relative">
                    <img
                      src={evento.imagem}
                      alt={evento.titulo}
                      className="w-full h-48 object-cover"
                      loading="lazy"
                    />
                  </div>
                  <div className="p-4">
                    <div className="flex items-start justify-between mb-2">
                      {evento.privado && (
                        <span className="bg-red-900 text-red-200 text-xs px-2 py-1 rounded">
                          Privado
                        </span>
                      )}
                    </div>
                    <h3 className="font-bold text-lg mb-1 group-hover:text-red-500 transition">
                      {evento.titulo}
                    </h3>
                    <p className="text-gray-400 text-sm mb-1">{evento.data}</p>
                    <p className="text-gray-400 text-sm">{evento.local}</p>
                    {evento.ingressos && (
                      <p className="text-gray-300 text-sm mt-3">
                        🎟️ {evento.ingressos} ingresso{evento.ingressos > 1 ? 's' : ''}
                      </p>
                    )}
                  </div>
                </article>
              </a>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}

export default Admin;
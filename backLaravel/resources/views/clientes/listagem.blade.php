@extends('layouts/admin-app')
@section("content")
@section('content')
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center font-bold">
                <h5 class="card-title m-b-0">Lista Clientes</h5>
                <div class="float-right">
                    <a href="{{ url("/clientes/novo") }}" class="btn btn-primary"><i class="fas fa-plus"></i> Novo cliente</a>
                </div>
            </div>
    

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

<div class="table">
    <table class="table table-striped ">
        <thead class="col 12">
            <tr>
                <th>
                    <label class="customcheckbox m-b-2">
                        <input type="checkbox" id="mainCheckbox">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">CPF</th>
                <th scope="col">Email</th>
                <th scope="col">Telefone</th>
                <th scope="col">Status</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody class="customtable">
            @forelse($clientes as $cliente)
                <tr class="">
                    <th>
                        <label class="customcheckbox">
                            <input type="checkbox" class="listCheckbox">
                            <span class="checkmark"></span>
                        </label>
                    </th>
                    <th scope="row">{!! $cliente->id !!}</th>
                 
                    <td>{{ $cliente->nome }}</td>
                    <td>{{ $cliente->cpf }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->telefone}}</td>
                    <td>
                        <span class="badge {{ $cliente->ativo ? 'badge-success' : 'badge-danger' }}">
                            {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url("/clientes/{$cliente->id}/editar") }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="{{ url("/clientes/{$cliente->id}/excluir") }}" class="btn btn-danger btn-sm botao-excluir"><i class="fas fa-trash"></i></a>

                       
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Nenhum cliente cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
</div>
</div>
@endsection
@push("js")
<script>
    $(document).ready(() => {
        $(".botao-excluir").on("click", (event) => {
            event.preventDefault();

            const url = $(event.currentTarget).attr("href");
            console.log("URL:", url); // Debug
        console.log("Token:", csrfToken); // Debug

            fetch(url, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                }
            }).then(async (retorno) => {
                const dados = await retorno.json();

                if (dados.status === true) {
                    toastr.success("Sucesso!", "Grupo excluído com sucesso.");

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                    return;
                }

                toastr.error("Erro!", "Não foi possível excluir o cliente.");
            });

            return false;
        });
    });
</script>
    
@endpush
@extends('layouts/admin-app')
@section("content")
@section('content')
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center font-bold">
                <h5 class="card-title m-b-0">Grupos</h5>
                <div class="float-right">
                    <a href="{{ url("/grupos/novo") }}" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Grupo</a>
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
                <th scope="col">Status</th>
               
            </tr>
        </thead>
        <tbody class="customtable">
            @forelse($grupos as $grupo)
                <tr class="">
                    <th>
                        <label class="customcheckbox">
                            <input type="checkbox" class="listCheckbox">
                            <span class="checkmark"></span>
                        </label>
                    </th>
                    <th scope="row">{!! $grupo->id !!}</th>
                 
                    <td>{{ $grupo->nome }}</td>
                   <td>
                        <span class="badge {{ $grupo->ativo ? 'badge-success' : 'badge-danger' }}">
                            {{ $grupo->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url("/grupos/{$grupo->id}/editar") }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="{{ url("/grupos/{$grupo->id}/excluir") }}" class="btn btn-danger btn-sm botao-excluir"><i class="fas fa-trash"></i></a>

                       
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Nenhum grupo cadastrado.</td>
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
                    toastr.success("Sucesso!", "grupo excluído com sucesso.");

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                    return;
                }

                toastr.error("Erro!", "Não foi possível excluir o grupo.");
            });

            return false;
        });
    });
</script>
    
@endpush
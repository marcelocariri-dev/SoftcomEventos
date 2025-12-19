@extends('layouts/admin-app')
@section("content")
<div class="card">
    <h5 class="card-header ">CADASTRO DE CLIENTES</h5>
    <div class="card-body">
        {!! Form::model($clientes, ['url' => url("/clientes/salvar"), "method" => "POST"]) !!}
        {!! Form::hidden("id") !!}
        <div class="row mx-2">
            <div class="col-6">
        <div class="form-group">
           {!! Form::label('nome', 'NOME'); !!}
           {!! Form::text('nome', null, ['class'=> 'form-control']); !!}
             
            @error("nome")
            <span class="text-danger"> 
            {!! $message !!}
            </span>
        @enderror
   
        
        </div>
        </div>
           
        <div class="col-6">
            <div class="form-group">
               {!! Form::label('cpf', 'CPF'); !!}
               {!! Form::text('cpf', null, ['class'=> 'form-control', 'maxlength' => '11']); !!}
               @error("cpf")
               <span class="text-danger"> 
               {!! $message !!}
               </span>
           @enderror
            </div>
        </div>
        
        <div class="col-6 ">
            <div class="form-group">
               {!! Form::label('email', 'EMAIL'); !!}
               {!! Form::text('email', null, ['class'=> 'form-control']); !!}
               @error("email")
               <span class="text-danger"> 
               {!! $message !!}
               </span>
           @enderror
            </div>
            </div>
        
            <div class="col-6 ">
                <div class="form-group">
                   {!! Form::label('telefone', 'TELEFONE'); !!}
                   {!! Form::text('telefone', null, ['class'=> 'form-control']); !!}
                   @error("telefone")
                   <span class="text-danger"> 
                   {!! $message !!}
                   </span>
               @enderror
                </div>
                </div>
        </div>

<div class="d-flex justify-content-end mx-2 mt-3 mb-3">   
    <button type="submit" class="btn btn-success">
        <i class="fas fa-check "></i>
        
        REGISTRAR</button>
</div>
      
        {!! Form::close() !!}

    </div>
</div>

@endsection
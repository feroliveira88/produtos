@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Produtos</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @forelse($produtos as $produto)
                        <div class="col-md-3 text-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="{{ $produto->foto }}" style="width: 100%;max-width: 200px;">
                                </div>
                                <div class="col-md-12">
                                    <b>{{ $produto->nome }}</b>
                                </div>
                                <div class="col-md-12">
                                    {{ $produto->descricao }}
                                </div>
                                <div class="col-md-12">
                                    Preço: $ {{ $produto->valor }}
                                </div>
                                <div class="col-md-12">
                                    <a href="produtos/edit/{{ $produto->id }}"><button class="btn btn-sm btn-default">Editar</button></a> <a href="produtos/delete/{{ $produto->id }}"><button class="btn btn-sm btn-default">Excluir</button></a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>Nenhum Produto encontrado!</p>
                    @endforelse
                </div>
                <div class="col-md-12 text-center">{{ $produtos->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

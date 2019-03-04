@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
<h1>Saldo</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saldo</a></li>
</ol>
    
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <a href="{{ route('balance.deposit') }}" class="btn btn-primary">Recarregar <i class="fa fa-cart-plus"></i></a>
        
        @if($amount > 0)
            <a href="{{ route('balance.withdraw') }}" class="btn btn-danger">Sacar <i class="fa fa-cart-arrow-down"></i></a>
        @endif
        
        @if($amount > 0)
            <a href="{{ route('balance.transfer') }}" class="btn btn-info">Transferir <i class="fa fa-exchange"></i></a>
        @endif
        
        
        
        
    </div>
    <div class="box-body">
        @include('admin.includes.alerts')
        <div class="small-box bg-green">
            <div class="inner">
                <h3>R$ {{ number_format($amount,2,',','.') }}</h3>
                
                <p>Bounce Rate</p>
            </div>
            <div class="icon">
                <i class="ion ion-cash"></i>
            </div>
            <a href="#" class="small-box-footer">Histórico<i class="fa fa-arrow-circle-left"></i></a>
        </div>
    </div>
</div>

@stop
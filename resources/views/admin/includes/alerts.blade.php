@if($errors->any())
@foreach($errors->all() as $error)
<div class="alert alert-warning">
    <p> {{ $error }}</p>
</div>
@endforeach
@endif

@if(session('success'))
<div class="alert alert-success">
    <p> {{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <p> {{ session('error') }}</p>
</div>
@endif
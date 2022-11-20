@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="w-100">
        {!! json_decode($artikel->artikel) !!}
    </div>
</div>
@endsection
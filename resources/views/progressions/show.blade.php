@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $progression->title }}</h1>
    <p>{{ $progression->description }}</p>
    <a href="{{ route('progressions.edit', $progression->id) }}" class="btn btn-warning">Modifier</a>
    <form method="POST" action="{{ route('progressions.destroy', $progression->id) }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>
</div>
@endsection

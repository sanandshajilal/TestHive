@extends('layouts.app') {{-- or your own layout --}}

@section('content')
    <form method="POST" action="{{ url('admin/login') }}">
        @csrf
        <input type="email" name="email" required>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
@endsection

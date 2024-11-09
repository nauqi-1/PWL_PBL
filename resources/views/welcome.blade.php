@extends('layouts.template')

@section('content')

<div class = "card">
    <div class = "card-header">
        <h3 class = "card-title">Halo! X3 Apa kabar??? owo hawk tuah</h3>
        <div class = "card-tools"></div>
    </div>
    <div class = "card-body">
        Selamat datang semua, ini adalah laman utama website :3
    </div>
</div>
@endsection
@push('css')
    
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
        $('#myModal').load(url,function() {
            $('#myModal').modal('show');
        });
    }
    </script>
@endpush
@extends('admin.layouts.main')

@section('title')
    Halaman -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.halaman') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali
            <a href="{{ route('admin.konten.info', 'halaman_id=' . $halaman->id) }}" class="btn btn-primary mt-3 px-4 font-weight-bold ml-3" style="border-radius: 20px;">Tambah</a>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body p-2">
            <table class="table table-borderless table-sm mb-0">
                <tr><td width="100px">Halaman</td><td>: <b>{{ $halaman->nama }}</b></td></tr>
                <tr><td width="100px">Url</td><td>: <b>{{ url('/') . '/' . $halaman->slug }}</b></td></tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12" id="div_data_konten">
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let formPencarian = $('#form_pencarian'),
            divDataKonten = $('#div_data_konten');
        divDataKonten.html('<div class="text-center"><h4>Loading <i class="fa fa-spinner fa-spin"></i></h4></div>')
        formPencarian.submit(function (e) {
            e.preventDefault();
            search_data();
        });
        function search_data() {
            $.post("{{ route('admin.konten.search') }}", {
                _token: '{{ csrf_token() }}',
                action: ['edit'],
                halaman_id: '{{ $halaman->id }}'
            }, function (result) {
                divDataKonten.html(result);
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
        search_data();
    </script>
@endpush

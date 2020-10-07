@extends('admin.layouts.main')

@section('title')
    Kategori -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.kategori.info') }}" class="btn btn-primary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Tambah</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="div_data_kategori">
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function toggle_pencarian() {
            $('#card_pencarian').slideToggle();
        }

        let selectedPage = 1,
            formPencarian = $('#form_pencarian'),
            divDataKategori = $('#div_data_kategori');
        divDataKategori.html('<div class="text-center"><h4>Loading <i class="fa fa-spinner fa-spin"></i></h4></div>')
        formPencarian.submit(function (e) {
            e.preventDefault();
            search_data();
        });
        function search_data(page = '') {
            if (page.toString() === '-1') page = selectedPage - 1;
            if (page.toString() === '+1') page = selectedPage + 1;
            if (page === '') page = selectedPage;
            selectedPage = page;

            $.post("{{ route('admin.kategori.search') }}?page=" + selectedPage, {
                _token: '{{ csrf_token() }}',
                action: ['edit', 'tambah_sub', 'konten'],
            }, function (result) {
                divDataKategori.html(result);
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
        search_data();

        function reposisi(id, arah) {
            $.post("{{ route('admin.kategori.reposisi') }}", {
                _token: '{{ csrf_token() }}', id, arah
            }, function () {
                window.location.reload();
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
    </script>
@endpush

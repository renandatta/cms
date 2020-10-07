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
            <a href="{{ route('admin.halaman.info') }}" class="btn btn-primary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Tambah</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="div_data_halaman">
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
            divDataHalaman = $('#div_data_halaman');
        divDataHalaman.html('<div class="text-center"><h4>Loading <i class="fa fa-spinner fa-spin"></i></h4></div>')
        formPencarian.submit(function (e) {
            e.preventDefault();
            search_data();
        });
        function search_data(page = '') {
            if (page.toString() === '-1') page = selectedPage - 1;
            if (page.toString() === '+1') page = selectedPage + 1;
            if (page === '') page = selectedPage;
            selectedPage = page;

            $.post("{{ route('admin.halaman.search') }}?page=" + selectedPage, {
                _token: '{{ csrf_token() }}',
                action: ['edit', 'tambah_sub', 'konten'],
            }, function (result) {
                divDataHalaman.html(result);
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
        search_data();

        function reposisi(id, arah) {
            $.post("{{ route('admin.halaman.reposisi') }}", {
                _token: '{{ csrf_token() }}', id, arah
            }, function () {
                window.location.reload();
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
    </script>
@endpush
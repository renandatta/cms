@extends('admin.layouts.main')

@section('title')
    Pesan -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="div_data_pesan">
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
            divDataPesan = $('#div_data_pesan');
        divDataPesan.html('<div class="text-center"><h4>Loading <i class="fa fa-spinner fa-spin"></i></h4></div>')
        formPencarian.submit(function (e) {
            e.preventDefault();
            search_data();
        });
        function search_data(page = '') {
            if (page.toString() === '-1') page = selectedPage - 1;
            if (page.toString() === '+1') page = selectedPage + 1;
            if (page === '') page = selectedPage;
            selectedPage = page;

            $.post("{{ route('admin.pesan.search') }}?page=" + selectedPage, {
                _token: '{{ csrf_token() }}',
                action: ['delete'],
            }, function (result) {
                divDataPesan.html(result);
            }).fail(function (xhr) {
                console.log(xhr.responseText);
            });
        }
        search_data();

        function hapus_data(id) {
            Swal.fire({
                title: 'Hapus data?',
                text: "Data yang dihapus tidak dapat dikembalikan lagi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.value) {
                    $.post("{{ route('admin.pesan.delete') }}", {
                        _token: '{{ csrf_token() }}', id, ajax: 1
                    }, () => {
                        search_data();
                    });
                }
            })
        }
    </script>
@endpush

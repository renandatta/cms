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
            <a href="{{ route('admin.halaman') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali</a>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            @include('admin.tools._alert')
            <form action="{{ route('admin.halaman.save') }}" method="post">
                @csrf
                <input type="hidden" name="kode" value="{{ $kode }}">
                <input type="hidden" name="parent_kode" value="{{ $parent_kode }}">
                @if(!empty($halaman))
                    <input type="hidden" name="id" value="{{ $halaman->id }}">
                @endif
                <div class="row">
                    <div class="col-md-6">
                        @if(!empty($parent))
                            <div class="form-group">
                                <label for="parent">Halaman Induk</label>
                                <input type="text" class="form-control" id="parent" name="parent" value="{{ !empty($parent) ? $parent->nama : '' }}" readonly>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ !empty($halaman) ? $halaman->nama : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ !empty($halaman) ? $halaman->slug : '' }}" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.halaman') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
        @if(!empty($halaman))
            <div class="card-footer text-right p-2">
                <button type="button" class="btn btn-secondary float-left" onclick="tampilkan_referensi()">Cari Referensi</button>
                <form action="{{ route('admin.halaman.delete') }}" method="post" id="form_hapus">
                    @csrf
                    <input type="hidden" name="id" value="{{ $halaman->id }}">
                    <button type="button" class="btn btn-danger" onclick="hapus_data()">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="modal fade" id="modal_referensi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_referensi_judul">Pilih Referensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama</th>
                            <th>Url</th>
                            <th width="50px"></th>
                        </tr>
                        </thead>
                        <tbody id="list_data_referensi"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function hapus_data() {
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
                    $('#form_hapus').submit();
                }
            })
        }

        let $list_data_referensi = $('#list_data_referensi');
        $list_data_referensi.html('<tr><td colspan="99" class="text-center">Loading ...</td></tr>');
        $.post("{{ route('admin.kategori.search') }}", {
            _token: '{{ csrf_token() }}',
            ajax: 1
        }, (result) => {
            $list_data_referensi.html('');
            $.each(result, (i, value) => {
                let row = $('<tr>');
                row.append('<td>'+ (i+1) +'</td>');
                row.append('<td>'+ value.nama +'</td>');
                row.append('<td>'+ value.slug +'</td>');
                row.append('<td class="p-2 text-right"><button class="btn btn-secondary btn-sm" onclick="pilih_referensi(`'+ value.nama +'`, `'+ value.slug +'`)">pilih</button></td>');
                $list_data_referensi.append(row);

                $.each(value.children, (j, value2) => {
                    let row2 = $('<tr>');
                    row2.append('<td>'+ (j+1) +'</td>');
                    row2.append('<td>'+ value2.nama +'</td>');
                    row2.append('<td>'+ value2.slug +'</td>');
                    row2.append('<td class="p-2 text-right"><button class="btn btn-secondary btn-sm" onclick="pilih_referensi(`'+ value2.nama +'`, `'+ value2.slug +'`)">pilih</button></td>');
                    $list_data_referensi.append(row2);
                });
            });
        }).fail((xhr) => {
            console.log(xhr.responseText);
        });
        function tampilkan_referensi() {
            $('#modal_referensi').modal('show');
        }
        function pilih_referensi(nama, slug) {
            $('#nama').val(nama);
            $('#slug').val('{{ !empty($parent) ? $parent->slug . '/' : '' }}' + slug);
            $('#modal_referensi').modal('toggle');
        }
    </script>
@endpush

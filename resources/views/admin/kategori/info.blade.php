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
            <a href="{{ route('admin.kategori') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali</a>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            @include('admin.tools._alert')
            <form action="{{ route('admin.kategori.save') }}" method="post">
                @csrf
                <input type="hidden" name="kode" value="{{ $kode }}">
                <input type="hidden" name="parent_kode" value="{{ $parent_kode }}">
                @if(!empty($kategori))
                    <input type="hidden" name="id" value="{{ $kategori->id }}">
                @endif
                <div class="row">
                    <div class="col-md-6">
                        @if(!empty($parent))
                            <div class="form-group">
                                <label for="parent">Kategori Induk</label>
                                <input type="text" class="form-control" id="parent" name="parent" value="{{ !empty($parent) ? $parent->nama : '' }}" readonly>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ !empty($kategori) ? $kategori->nama : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ !empty($kategori) ? $kategori->slug : '' }}" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.kategori') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
        @if(!empty($kategori))
            <div class="card-footer text-right p-2">
                <form action="{{ route('admin.kategori.delete') }}" method="post" id="form_hapus">
                    @csrf
                    <input type="hidden" name="id" value="{{ $kategori->id }}">
                    <button type="button" class="btn btn-danger" onclick="hapus_data()">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
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
    </script>
@endpush

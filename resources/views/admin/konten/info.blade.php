@extends('admin.layouts.main')

@section('title')
    Konten -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.konten') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali</a>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            @include('admin.tools._alert')
            <form action="{{ route('admin.konten.save') }}" method="post">
                @csrf
                @if(!empty($konten))
                    <input type="hidden" name="id" value="{{ $konten->id }}">
                @endif
                @if($halaman_id != null)
                    <input type="hidden" name="halaman_id" value="{{ $halaman_id }}">
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ !empty($konten) ? $konten->nama : '' }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="konten">Konten</label>
                    <textarea name="konten" id="konten" rows="10" class="form-control">{!! !empty($konten) ? $konten->konten : '' !!}</textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.konten') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
        @if(!empty($konten))
            <div class="card-footer text-right p-2">
                <form action="{{ route('admin.konten.delete') }}" method="post" id="form_hapus">
                    @csrf
                    <input type="hidden" name="id" value="{{ $konten->id }}">
                    <button type="button" class="btn btn-danger" onclick="hapus_data()">
                        Hapus
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link href="{{ asset('lib/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('lib/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $('#konten').summernote({
            height: 500
        });
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

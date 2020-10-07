@extends('admin.layouts.main')

@section('title')
    Berita -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.berita') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali</a>
        </div>
    </div>
    <form action="{{ route('admin.berita.save') }}" method="post">
        @csrf
        @if(!empty($berita))
            <input type="hidden" name="id" value="{{ $berita->id }}">
        @endif
        @include('admin.tools._alert')
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <input type="text" class="form-control form-control-lg border-radius-0" name="judul" id="judul" placeholder="Judul" value="{{ !empty($berita) ? $berita->judul : '' }}">
                        <textarea name="konten" id="konten">{!! !empty($berita) ? $berita->konten : '' !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ !empty($berita) ? $berita->slug : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="text" class="form-control datepicker" id="tanggal" name="tanggal" value="{{ !empty($berita) ? format_date($berita->tanggal) : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-control select2">
                                @foreach($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <textarea class="form-control" id="tags" name="tags" rows="4"> {{ !empty($berita) ? $berita->tags : '' }} </textarea>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.konten') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if(!empty($berita))
        <div class="text-right p-2">
            <form action="{{ route('admin.berita.delete') }}" method="post" id="form_hapus">
                @csrf
                <input type="hidden" name="id" value="{{ $berita->id }}">
                <button type="button" class="btn btn-danger" onclick="hapus_data()">
                    Hapus
                </button>
            </form>
        </div>
    @endif
@endsection

@push('styles')
    <link href="{{ asset('lib/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
    <style>
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding: .325rem .75rem;
        }
        .select2-container .select2-selection--single {
            height: calc(1.5em + .75rem + 2px);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + .75rem + 2px);
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('lib/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $('#konten').summernote({
            height: 700
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
        $('#judul').change(function () {
            $.post("{{ route('slug') }}", {
                _token: '{{ csrf_token() }}', nama: $('#judul').val()
            }, function (result) {
                $('#slug').val(result);
            });
        });
    </script>
@endpush

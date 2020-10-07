@extends('admin.layouts.main')

@section('title')
    User -
@endsection

@section('content')
    <div class="row mt-5">
        <div class="col-6">
            <h4 class="mb-0"># {{ end($breadcrumbs)['caption'] }}</h4>
            <p>{{ end($breadcrumbs)['desc'] }}</p>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.user') }}" class="btn btn-secondary mt-3 px-4 font-weight-bold" style="border-radius: 20px;">Kembali</a>
        </div>
    </div>
    <div class="card border-0">
        <div class="card-body">
            @include('admin.tools._alert')
            <form action="{{ route('admin.user.save') }}" method="post">
                @csrf
                @if(!empty($user))
                    <input type="hidden" name="id" value="{{ $user->id }}">
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ !empty($user) ? $user->nama : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ !empty($user) ? $user->email : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" @if(empty($user)) required @endif>
                            @if(!empty($user))
                                <span class="form-text text-muted">Kosongi apabila tidak diubah</span>
                            @endif
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.user') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
        @if(!empty($user))
            <div class="card-footer text-right p-2">
                <form action="{{ route('admin.user.delete') }}" method="post" id="form_hapus">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
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

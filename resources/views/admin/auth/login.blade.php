@extends('admin.layouts.auth')

@section('title')
    Login -
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 mt-5">
                <div class="text-center">
                    <img src="{{ asset('lemon.png') }}" alt="" class="img-fluid" style="width: 100px">
                    <h4>Konten Manajemen Sistem v.2</h4>
                    <br>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('login_proses') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email login" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Simpan Login
                                </label>
                            </div>

                            <button type="submit" class="btn btn-block btn-primary">Login</button>
                            <hr>
                            <h6 class="text-center">Untuk informasi lebih lanjut hubungi <br> <a href="mailto:renandattarooziq@gmail.com">renandattarooziq@gmail.com</a></h6>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

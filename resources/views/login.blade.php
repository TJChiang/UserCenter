@extends('default')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="text-center mt-5">
            <div class="d-flex flex-column bd-highlight mb-3">
                <h2 class="font-weight-bold py-3">登入</h2>
            </div>

            <form method="POST" autocomplete="off">
                @csrf
                <div class="px-2 px-md-5">
                    <div class="row mb-3">
                        <label for="Login_Account" class="col-sm-2 col-form-label">帳號</label>
                        <div class="col-sm-10">
                            <input id="Login_Account" name="account" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="Login_Password" class="col-sm-2 col-form-label">密碼</label>
                        <div class="col-sm-10">
                            <input id="Login_Password" name="password" type="password" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="btn_login" class="btn btn-primary">登入</button>
                </div>
            </form>
            <hr>
        </div>
        <form class="form-signin">
            @csrf
            <div class="text-center mb-4">
                <a href="{{ $url }}"><img class="mb-4" src="{{ asset("/images/btn_login_base.png") }}"></a>
            </div>
        </form>
    </div>
</div>
@endsection

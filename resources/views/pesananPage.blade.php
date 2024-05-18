<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order</title>
    <link rel="icon" type="image/x-icon" href="images/logoP3L.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('homeStyle.css') }}" rel="stylesheet">
</head>

<body>

    <!-- header -->
    <header class="header">
        <img class="logo" src="{{ asset('images/logoP3L.png') }}" alt="Logo P3L" style="height: 50px; width: 50px;">

        <nav class="navbar">
            <a href="/#home" class="nav-link">home</a>
            <a href="/#about" class="nav-link">about</a>
            <a href="/produkHome" class="nav-link">produk</a>
            <a href="/#gallery" class="nav-link">foto</a>
            <a href="/#order" class="nav-link">order</a>
        </nav>

        <div class="icons">
            @if (Auth::check())
                <div id="cart-btn" class="fas fa-shopping-cart"></div>
                <div id="profile-btn" onclick="window.location.href='{{ route('user.userProfile') }}'"
                    class="fas fa-user"></div>
            @endif
            @if (Auth::check())
                <div id="logout-btn" onclick="window.location.href='{{ route('actionLogout') }}'"
                    class="fas fa-sign-out-alt">
                </div>
            @else
                <div id="login-btn" onclick="window.location.href='{{ url('login') }}'" class="fas fa-sign-in-alt">
                </div>
            @endif
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>
    </header>
    <!-- header end -->

    <section class="order" id="order">
        <h1 class="heading"><span>order</span> now </h1>

        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                    aria-label="Warning:">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <span style="font-size: 20px">{{ session('error') }}</span>
            </div>
        @endif

        <div class="row">
            <div class="image">
                <img src="{{ asset('images/order.gif') }}" alt="Order Image">
            </div>

            <form class="user" action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="inputBox">
                    <input type="text" class="form-control @error('tanggal_ambil') is-invalid @enderror"
                        id="InputTanggalAmbil" placeholder="Tanggal Ambil" value="{{ old('tanggal_ambil') }}"
                        name="tanggal_ambil" onfocus="(this.type='date')" onblur="(this.type='text')" required
                        min="{{ date('Y-m-d', strtotime('+2 days')) }}">
                    @error('tanggal_ambil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <input type="number" class="form-control @error('poin_digunakan') is-invalid @enderror"
                        id="InputPoinDigunakan" placeholder="Poin Digunakan"
                        value="{{ old('poin_digunakan', Auth::user()->poin) }}" name="poin_digunakan" required>
                    @error('poin_digunakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <select class="form-control @error('jenis_delivery') is-invalid @enderror" name="jenis_delivery"
                        required>
                        <option selected disabled>Pilih Jenis Delivery</option>
                        <option value="pickUp">Pick Up</option>
                        <option value="delivery">Delivery</option>
                    </select>
                    @error('jenis_delivery')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <textarea class="form-control @error('alamat') is-invalid @enderror" id="InputAlamat" placeholder="Alamat"
                    name="alamat" required cols="30" rows="10">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn"> order now </button>
            </form>
        </div>
    </section>

    <!-- footer -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="homeScript.js"></script>
    <script>
        lightGallery(document.querySelector('.gallery .gallery-container'));
    </script>
</body>

</html>

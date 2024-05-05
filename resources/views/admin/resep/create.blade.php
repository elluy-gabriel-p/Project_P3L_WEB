<!DOCTYPE html>
<html lang="en">

<head>
    <title>Atma Kitchen - Add Resep</title>
    @include('admin.template.head')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('admin.template.left-sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('admin.template.navbar')
                <!-- End of Topbar -->

                <div class="container">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Add Resep</h1>
                                </div>
                                <form class="user" action="{{ route('resep.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nama Resep</label>
                                        <input type="text"
                                            class="form-control form-control-user @error('nama_resep') is-invalid @enderror"
                                            id="InputNamaResep" placeholder="Nama Resep"
                                            value="{{ old('nama_resep') }}" name="nama_resep">
                                        @error('nama_resep')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Jumlah Stok</label>
                                        <input type="number"
                                            class="form-control form-control-user @error('jumlah_stok') is-invalid @enderror"
                                            id="InputJumlahStokResep" placeholder="Jumlah Stok"
                                            value="{{ old('jumlah_stok') }}" name="jumlah_stok">
                                        @error('jumlah_stok')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Save
                                    </button>
                                    
                                </form>

                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('admin.template.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    @include('admin.template.logoutModal')

    @include('admin.template.script')

</body>

</html>

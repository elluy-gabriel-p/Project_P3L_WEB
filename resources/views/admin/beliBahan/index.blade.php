<!DOCTYPE html>
<html lang="en">

<head>
    <title>Atma Kitchen - Pembelian Bahan Baku</title>
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

            <div id="content">

                <!-- Topbar -->
                @include('admin.template.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Pembelian Bahan Baku</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="{{ route('beliBahan.create') }}" class="btn btn-md btn-success mb-3">TAMBAH
                                PEMBELIAN BAHAN BAKU</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Bahan Baku</th>
                                            <th>Harga Bahan Baku</th>
                                            <th>Kuantitas</th>
                                            <th>Tanggal Pengeluaran</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nama Bahan Baku</th>
                                            <th>Harga Bahan Baku</th>
                                            <th>Kuantitas</th>
                                            <th>Tanggal Pengeluaran</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @forelse ($pembelian_bahan_baku as $item)
                                            <tr>
                                                <td>{{ $item->bahan_baku->nama_bahan }}</td>
                                                <td>{{ $item->harga_bahan_baku }}</td>
                                                <td>{{ $item->kuantitas }}</td>
                                                <td>{{ $item->tanggal_pengeluaran }}</td>
                                                <td>
                                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                                        action="{{ route('beliBahan.destroy', $item->id_bahan_baku) }}"
                                                        method="POST">
                                                        <a href="{{ route('beliBahan.edit', $item->id_bahan_baku) }}"
                                                            class="btn btn-sm btn-primary">Edit</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                            </tr>
                                        @empty
                                            <div class="alert alert-danger">
                                                Belum Memiliki Pembelian Bahan Baku
                                            </div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - CI3</title>
    <style>
        body {
            background: url('bg.jpeg');
            background-size: cover;
            color: aqua;
        }

        button.btn-update {
            background-color: yellow;
        }

        button.btn-delete {
            background-color: red;
            color: #fff;
        }

        button.btn-update:hover,
        button.btn-delete:hover {
            background-color: rgb(240, 240, 240);
            color: #000;
        }
    </style>
</head>

<body>
    <center>
        <h1>Codeigniter 3 API</h1>
    </center>
    <center>
        <button class="btn-add" style="cursor: pointer; margin-left: -64.5%; margin-bottom: 5px;">Tambah</button>
        <table style="border: 1px solid aqua;">
            <thead>
                <tr>
                    <th style="border: 1px solid aqua;" align="center">No</th>
                    <th style="border: 1px solid aqua;">Nama</th>
                    <th style="border: 1px solid aqua;">Alamat</th>
                    <th style="border: 1px solid aqua;">Gambar</th>
                    <th style="border: 1px solid aqua;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $key => $item) : ?>
                    <tr>
                        <td style="border: 1px solid aqua;" align="center"><?= $key + 1; ?></td>
                        <td style="border: 1px solid aqua; width: 30%;"><?= $item->nama_siswa; ?></td>
                        <td style="border: 1px solid aqua; width: 50%;"><?= $item->alamat; ?></td>
                        <td style="border: 1px solid aqua;" align="center">
                            <?php if ($item->gambar) : ?>
                                <img src="<?php echo base_url('uploads/' . $item->gambar); ?>" alt="Gambar Siswa" style="width: 40px;">
                            <?php else : ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                        <td style="border: 1px solid aqua;">
                            <button class="btn-update" data-id="<?= $item->id; ?>" style="cursor: pointer;">Update</button>
                            <button class="btn-delete" data-id="<?= $item->id; ?>" style="cursor: pointer;">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form id="form-tambah-siswa" enctype="multipart/form-data" style="display: none;">
            <h2>Form Tambah Data Siswa</h2>
            <div>
                <label>Nama Siswa:</label><br>
                <input type="text" name="nama_siswa" required>
            </div>
            <div>
                <label>Alamat:</label><br>
                <input type="text" name="alamat" required>
            </div>
            <div>
                <label>Gambar:</label><br>
                <input type="file" name="gambar">
            </div>
            <br>
            <div>
                <button type="submit">Simpan</button>
            </div>
        </form>

        <form id="form-update-siswa" enctype="multipart/form-data" style="display: none;">
            <h2>Form Update Siswa</h2>
            <input type="hidden" name="id" value="">
            <div>
                <label>Nama Siswa:</label><br>
                <input type="text" name="nama_siswa" required>
            </div>
            <div>
                <label>Alamat:</label><br>
                <input type="text" name="alamat" required>
            </div>
            <div>
                <label>Gambar:</label><br>
                <input type="file" name="gambar">
            </div>
            <div>
                <label>Gambar Saat Ini:</label><br>
                <img id="gambar-siswa" src="" alt="Gambar Siswa" style="width: 100px;">
            </div>
            <br>
            <div>
                <button type="submit">Update</button>
            </div>
        </form>
    </center>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tambah Data
        $(document).ready(function() {
            $('#form-tambah-siswa').submit(function(event) {
                event.preventDefault();

                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url("siswa/simpan"); ?>',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan: ' + error
                        });
                    }
                });
            });
        });

        // Button Update
        $(document).ready(function() {
            $('.btn-update').click(function() {
                var id = $(this).data('id');

                // Ajax request untuk mengambil data siswa berdasarkan ID
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url("siswa/detail/") ?>' + id,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Isi nilai form update dengan data siswa yang diterima
                            $('#form-update-siswa input[name="id"]').val(response.data.id);
                            $('#form-update-siswa input[name="nama_siswa"]').val(response.data.nama_siswa);
                            $('#form-update-siswa input[name="alamat"]').val(response.data.alamat);
                            $('#gambar-siswa').attr('src', '<?php echo base_url("uploads/") ?>' + response.data.gambar);

                            // Tampilkan form update
                            $('#form-update-siswa').show();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            });
        });

        // Update Data
        $(document).ready(function() {
            $('#form-update-siswa').submit(function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                // Menambahkan gambar ke dalam FormData
                var gambar = $('#form-update-siswa input[name="gambar"]')[0].files[0];
                formData.append('gambar', gambar);

                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url("siswa/update"); ?>',
                    data: formData,
                    dataType: 'json',
                    contentType: false, // Set false agar jQuery tidak mengatur Content-Type
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan: ' + error
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.btn-add').click(function() {
                $('#form-tambah-siswa').show();
            });
        });

        // Button Hapus
        $(document).ready(function() {
            $('.btn-delete').click(function() {
                var id = $(this).data('id');

                // Konfirmasi pengguna sebelum menghapus data
                var confirmation = confirm('Apakah Anda yakin ingin menghapus data ini?');

                if (confirmation) {
                    // Ajax request untuk menghapus data siswa berdasarkan ID
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url("siswa/delete/") ?>' + id,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan: ' + error
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
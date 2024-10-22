<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Pengesahan</title>
    <style>
        @page {
            size: F4;
            margin: 20mm;
        }

        @media print {
            @page {
                size: F4;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            position: relative;
        }

        header {
            font-size: 6pt;
            text-align: left;
            padding: 2mm 10mm;
            position: fixed;
            width: calc(100% - 20mm);
            top: 0;
            background: white;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header span {
            font-size: 6pt;
            /* Set font size for the header */
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 20mm 10mm 20mm 10mm; /* adjust margins to reduce left margin */
            padding: 0 20mm;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10mm;
        }

        .content {
            margin-top: 10mm;
            font-size: 12px;
        }

        .content p {
            margin: 3mm 0;
        }

        .signature-section {
            margin-top: 20mm;
            display: flex;
            justify-content: space-between;
        }

        .signature-section div {
            width: 30%;
            text-align: center;
        }

        .signature-section div p {
            margin: 10mm 0;
        }

        .text-center {
            text-align: center;
        }

        /* Watermark CSS */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: url('images/watermark.png') no-repeat center center;
            background-size: contain;
            opacity: 0.1;
            z-index: 0;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <div class="watermark"></div>
    <header id="header">
        <span>{{ now()->format('d/m/Y H:i:s') }}</span>
        <span>/SIMAHA/UTY</span>
    </header>
    <div class="container">
        <h1>LEMBAR PENGESAHAN</h1>
        <div class="content">
            <p><strong>Judul Kegiatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $proposal->judul_kegiatan }}</p>
            <p><strong>Waktu Pelaksanaan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $waktu_kegiatan }}</p>
            <p><strong>Tempat Pelaksanaan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $proposal->tempat_pelaksanaan }}</p>
            <p><strong>Jumlah Mahasiswa Terlibat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong></p>
            <p><strong>Jumlah Peserta Terlibat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong></p>
            <p><strong>Jumlah Dosen Terlibat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong></p>
            <p><strong>Jumlah Dana Diperlukan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $proposal->anggaran_kegiatan }}</p>
            <p><strong>Jumlah Dana Diajukan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $proposal->anggaran_diajukan }}</p>
            <p><strong>Narahubung&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong></p>
            <p><strong>No. HP Narahubung&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong></p>
        </div>
        <div class="signature-section">
            <div>
                <br>
                <p>Ketua Pelaksana</p>
                <br><br>
                <p>Nama</p>
                <p>NPM</p>
            </div>
            <div>
                <p>Pelaksana,</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <br><br>
                <p>Ketua Umum Himpunan/UKM*</p>
                <br><br>
                <p>Nama</p>
                <p>NPM</p>
            </div>
            <div>
                <br>
                <p>Sekretaris</p>
                <br><br>
                <p>Nama</p>
                <p>NPM</p>
            </div>
        </div>

        <div class="signature-section">
            <div>
                <br><br>
                <p>Ketua Program Studi Program Sarjana*</p>
                <br>
                <p>Nama</p>
                <p>NIK</p>
            </div>
            <div>
                <p>Menyetujui,</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>Wakil Rektor III Bidang Kemahasiswaan & Alumni Universitas Teknologi Yogyakarta</p>

                <br><br>
                <p>Nama</p>
                <p>NIK</p>
            </div>
            <div>
                <br><br>
                <p>Ketua Program Studi Program Diploma*</p>
                <br>
                <p>Nama</p>
                <p>NPM</p>
            </div>
        </div>

        <div class="signature-section">
            <div>
                <p>Dekan Fakultas Sains & Teknologi / Bisnis & Humaniora*</p>
                <br><br>
                <p>Nama</p>
                <p>NIK</p>
            </div>
            <div>
                <p>Direktur program Diploma*</p>
                <br><br><br><br>
                <p>Nama</p>
                <p>NIK</p>
            </div>
        </div>
        <br>
        <p>*)Hapus jika tidak perlu</p>
    </div>

    <script>
        function updateTime() {
            var now = new Date();
            var day = now.getDate().toString().padStart(2, '0');
            var month = (now.getMonth() + 1).toString().padStart(2, '0'); // Months are zero based
            var year = now.getFullYear();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var dateString = day + '/' + month + '/' + year;
            var timeString = hours + ':' + minutes + ':' + seconds;
            document.getElementById('time').textContent = dateString + ' ' + timeString;
        }
        setInterval(updateTime, 1000); // Update every second
        updateTime(); // Initial call to display the time immediately
    </script>
</body>

</html>

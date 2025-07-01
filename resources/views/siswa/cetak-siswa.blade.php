<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil VIKOR</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin-bottom: 5px; }
        .header h2 { margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Hasil VIKOR</h1>
        <h2>Siswa: {{ $hasil->alternatif->user->name }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nama</td>
                <td>{{ $hasil->alternatif->user->name }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>{{ $hasil->alternatif->user->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td>Utility (S)</td>
                <td>{{ number_format($hasil->nilai_s, 4) }}</td>
            </tr>
            <tr>
                <td>Regret (R)</td>
                <td>{{ number_format($hasil->nilai_r, 4) }}</td>
            </tr>
            <tr>
                <td>Indeks VIKOR (Q)</td>
                <td>{{ number_format($hasil->nilai_q, 4) }}</td>
            </tr>
            <tr>
                <td>Peringkat</td>
                <td>{{ $hasil->ranking }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>{{ $hasil->status }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i') }}
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Menunggu Konfirmasi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md text-center max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Pendaftaran Berhasil!</h2>
        <p class="text-gray-600 mb-6">Akun Anda berhasil dibuat, tetapi sedang menunggu konfirmasi dari administrator. Anda akan menerima pemberitahuan setelah akun Anda diaktifkan.</p>
        <a href="{{ url('/') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>
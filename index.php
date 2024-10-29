<?php
$mergedJson = null;

// Cek apakah ada file yang diunggah
if (isset($_FILES['geojson_files'])) {
    $files = $_FILES['geojson_files']['tmp_name'];
    $features = [];

    // Loop setiap file yang diunggah
    foreach ($files as $file) {
        // Baca konten file GeoJSON
        $content = file_get_contents($file);
        $geojson = json_decode($content, true);

        // Tambahkan fitur dari file GeoJSON ke array fitur utama
        if (isset($geojson['features'])) {
            $features = array_merge($features, $geojson['features']);
        }
    }

    // Buat GeoJSON baru dengan semua fitur yang digabungkan
    $merged_geojson = [
        "type" => "FeatureCollection",
        "features" => $features
    ];

    // Encode hasil ke JSON untuk ditampilkan di bawah card
    $mergedJson = json_encode($merged_geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabungkan File GeoJSON</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
        }

        /* Dark Theme */
        body.dark {
            background-color: #121212;
            color: #f4f6f9;
        }

        /* Ensure container turns dark in dark mode */
        .container.bg-dark-mode {
            background-color: #333;
            color: #f4f6f9;
        }

        .footer {
            font-size: 0.9em;
            color: #888;
        }

        /* Field untuk JSON Result */
        #jsonResult {
            white-space: pre-wrap;
            word-break: break-word;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-top: 20px;
        }

        body.dark #jsonResult {
            background-color: #2c2c2c;
            color: #e1e1e1;
        }
    </style>
</head>
<body class="bg-light text-dark">
    <div class="container my-5 p-4 rounded shadow-sm bg-white">
        <h1 class="mb-4 text-center">Gabungkan File GeoJSON</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="geojson_files" class="form-label">Pilih beberapa file GeoJSON:</label>
                <input type="file" name="geojson_files[]" id="geojson_files" accept=".geojson" multiple class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Gabungkan</button>
        </form>

        <!-- Dark Mode Switch -->
        <div class="form-check form-switch mt-4 d-flex justify-content-center">
            <input class="form-check-input" type="checkbox" id="theme-toggle">
            <label class="form-check-label ms-2" for="theme-toggle">Dark Mode</label>
        </div>

        <div class="footer text-center mt-4">
            &copy; 2024 GeoJSON Merger. All rights reserved.
        </div>

        <!-- JSON Result -->
        <?php if ($mergedJson): ?>
            <h3 class="mt-4">Hasil JSON yang Digabungkan:</h3>
            <div id="jsonResult" class="p-3 border">
                <pre><?php echo htmlspecialchars($mergedJson); ?></pre>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        const container = document.querySelector('.container');

        // Toggle dark mode on body and container
        themeToggle.addEventListener('change', () => {
            const isDark = themeToggle.checked;
            body.classList.toggle('dark', isDark);
            container.classList.toggle('bg-dark-mode', isDark);

            // Set Bootstrap classes for dark and light mode
            body.classList.toggle('bg-dark', isDark);
            body.classList.toggle('text-light', isDark);
            body.classList.toggle('bg-light', !isDark);
            body.classList.toggle('text-dark', !isDark);

            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // Apply saved theme on load
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark', 'bg-dark', 'text-light');
            container.classList.add('bg-dark-mode');
            themeToggle.checked = true;
        }
    </script>
</body>
</html>

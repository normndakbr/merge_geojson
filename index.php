<?php
$mergedJson = null;

if (isset($_FILES['geojson_files'])) {
    $files = $_FILES['geojson_files']['tmp_name'];
    $features = [];

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $geojson = json_decode($content, true);

        if (isset($geojson['features'])) {
            $features = array_merge($features, $geojson['features']);
        }
    }

    $merged_geojson = [
        "type" => "FeatureCollection",
        "features" => $features
    ];

    $mergedJson = json_encode($merged_geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabungkan File GeoJSON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            transition: background-color 0.3s, color 0.3s;
        }

        body.dark {
            background-color: #121212;
            color: #f4f6f9;
        }

        .container.bg-dark-mode {
            background-color: #333;
            color: #f4f6f9;
        }

        .footer {
            font-size: 0.9em;
            color: #888;
        }

        #jsonResult {
            white-space: pre-wrap;
            word-break: break-word;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-top: 20px;
            max-height: 35vh;
            overflow-y: auto;
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

        <div class="form-check form-switch mt-4 d-flex justify-content-center">
            <input class="form-check-input" type="checkbox" id="theme-toggle">
            <label class="form-check-label ms-2" for="theme-toggle">Dark Mode</label>
        </div>

        <div class="footer text-center mt-4">
            &copy; 2024 GeoJSON Merger. All rights reserved.
        </div>

        <?php if ($mergedJson): ?>
            <h3 class="mt-4">Hasil JSON yang Digabungkan:</h3>
            <div id="jsonResult" class="p-3 border">
                <pre id="mergedJsonContent"><?php echo htmlspecialchars($mergedJson); ?></pre>
            </div>
            <button class="btn btn-secondary mt-3" onclick="copyToClipboard()">Copy Code</button>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        const container = document.querySelector('.container');

        themeToggle.addEventListener('change', () => {
            const isDark = themeToggle.checked;
            body.classList.toggle('dark', isDark);
            container.classList.toggle('bg-dark-mode', isDark);

            body.classList.toggle('bg-dark', isDark);
            body.classList.toggle('text-light', isDark);
            body.classList.toggle('bg-light', !isDark);
            body.classList.toggle('text-dark', !isDark);

            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark', 'bg-dark', 'text-light');
            container.classList.add('bg-dark-mode');
            themeToggle.checked = true;
        }

        function copyToClipboard() {
            const jsonContent = document.getElementById('mergedJsonContent').innerText;
            navigator.clipboard.writeText(jsonContent).then(() => {
                alert('JSON berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin JSON ke clipboard: ' + err);
            });
        }
    </script>
</body>
</html>

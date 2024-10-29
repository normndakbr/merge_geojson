<?php
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

    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="merged.geojson"');
    echo json_encode($merged_geojson);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gabungkan File GeoJSON</title>
</head>
<body>
    <h1>Gabungkan Beberapa File GeoJSON</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="geojson_files">Pilih beberapa file GeoJSON:</label>
        <input type="file" name="geojson_files[]" id="geojson_files" accept=".geojson" multiple required>
        <br><br>
        <button type="submit">Gabungkan</button>
    </form>
</body>
</html>

<?php
$folder = "/home/cplexpressbd/public_html/public/assets/search/";
$files = glob($folder . "*");

foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file); // Delete file
    }
}

echo "All images deleted successfully.\n";
?>

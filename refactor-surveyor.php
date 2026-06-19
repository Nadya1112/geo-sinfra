<?php
$files = glob(__DIR__ . '/resources/views/surveyor/*.blade.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // 1. Tambahkan darkMode: 'class'
    if (strpos($content, "tailwind.config = {") !== false && strpos($content, "darkMode: 'class'") === false) {
        $content = str_replace("tailwind.config = {", "tailwind.config = {\n            darkMode: 'class',", $content);
    }
    
    // 2. Tambahkan script inisialisasi dark mode sebelum tag <style> atau sesudah script config
    $initScript = "
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>";

    if (strpos($content, "localStorage.getItem('theme')") === false) {
        // Coba insert sebelum <style>
        if (strpos($content, "</script>\n    <style>") !== false) {
            $content = str_replace("</script>\n    <style>", "</script>" . $initScript . "\n    <style>", $content);
        } else if (strpos($content, "</script>\r\n    <style>") !== false) {
            $content = str_replace("</script>\r\n    <style>", "</script>" . $initScript . "\r\n    <style>", $content);
        }
    }
    
    // 3. Tambahkan class dasar pada body
    $content = preg_replace('/<body class="([^"]+)"/', '<body class="$1 dark:bg-navy-950 dark:text-white transition-colors duration-300"', $content);

    if ($original !== $content) {
        file_put_contents($file, $content);
        echo "Diperbarui: " . basename($file) . "\n";
    } else {
        echo "Dilewati: " . basename($file) . "\n";
    }
}
echo "Selesai.\n";

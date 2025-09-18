<?php
function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function escape($str) {
    return str_replace(['<script>','</script>'], '', $str);
}

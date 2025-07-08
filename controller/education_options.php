<?php
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
header('Content-Type: application/json; charset=utf-8');
if ($idx <= 0) {
    echo json_encode(['types' => []]);
    exit;
}

$types = $db->query(
    "SELECT idx, f_type AS title FROM df_site_education_type WHERE news_idx = :idx ORDER BY idx ASC",
    ['idx'=>$idx]
);

echo json_encode(['types' => $types]);
<?php
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
header('Content-Type: application/json; charset=utf-8');
if ($idx <= 0) {
    echo json_encode([
        'part'  => [],
        'field' => [],
        'event' => []
    ]);
    exit;
}

$parts  = $db->query(
    "SELECT idx, f_part AS title 
     FROM df_site_competition_part 
     WHERE competition_idx = :idx 
     ORDER BY idx ASC", 
    ['idx'=>$idx]
);
$fields = $db->query(
    "SELECT idx, f_field AS title 
     FROM df_site_competition_field 
     WHERE competition_idx = :idx 
     ORDER BY idx ASC", 
    ['idx'=>$idx]
);
$events = $db->query(
    "SELECT idx, f_event AS title 
     FROM df_site_competition_event 
     WHERE competition_idx = :idx 
     ORDER BY idx ASC", 
     ['idx'=>$idx]
);

echo json_encode([
    'part'  => $parts, 
    'field' => $fields, 
    'event' => $events
]);

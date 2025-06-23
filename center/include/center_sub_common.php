<?php
$category = $_GET['category'];
$year = !empty($_GET['year']) ? $_GET['year'] : date('Y');

// 분류 맵핑 (한글)
$category_map = [
    'makeup' => [
        'ko' => '메이크업',
        'en' => 'MAKE-UP'
    ],
    'nail' => [
        'ko' => '네일',
        'en' => 'NAIL'
    ],
    'hair' => [
        'ko' => '헤어',
        'en' => 'HAIR'
    ],
    'skin' => [
        'ko' => '피부',
        'en' => 'SKIN'
    ],
    'half' => [
        'ko' => '반영구',
        'en' => 'SEMI-PERMANENT'
    ],
    'foreign' => [
        'ko' => '해외인증',
        'en' => 'OVERSEAS CERTIFICATION'
    ],
    'teacher' => [
        'ko' => '강사인증',
        'en' => 'INSTRUCTOR CERTIFICATION'
    ]
];

$Menu = "03";
switch ($category) {
    case 'makeup':
        $sMenu = "03-2";
        $ssMenu = "03-2-" . $ssMenu_num;
        break;
    case 'nail':
        $sMenu = "03-3";
        $ssMenu = "03-3-" . $ssMenu_num;
        break;
    case 'hair':
        $sMenu = "03-4";
        $ssMenu = "03-4-" . $ssMenu_num;
        break;
    case 'skin':
        $sMenu = "03-5";
        $ssMenu = "03-5-" . $ssMenu_num;
        break;
    case 'half':
        $sMenu = "03-6";
        $ssMenu = "03-6-" . $ssMenu_num;
        break;
    case 'foreign':
        $sMenu = "03-7";
        $ssMenu = "03-7-" . $ssMenu_num;
        break;
    case 'teacher':
        $sMenu = "03-8";
        $ssMenu = "03-8-" . $ssMenu_num;
        break;
    default:
        $sMenu = "03-1";
        $ssMenu = "03-1-" . $ssMenu_num;
        break;
}

$valid_categories = ['makeup', 'nail', 'hair', 'skin', 'half', 'foreign', 'teacher'];
if (!isset($category) || !in_array($category, $valid_categories)) {
    //echo "<script>alert('잘못된 접근입니다.'); location.href='/';</script>";
    //exit;
}


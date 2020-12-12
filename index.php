<?php
// импортируем новости из старого ВП 10 летней давности

include('../wp-load.php');
global $wpdb;

$category_old_to_new =
    [
        '7' => '84', // Эрудиция Гика. Новости High Tech
        '4' => '81', // ... архив новостей 2002-2007
        '10' => '84', // Занимательная информация или "Это полезно знать"
        '9' => '19', // Безопасность, конфиденциальность, право
        '8' => '53', // E-commerce
        '11' => '83', // Наши семинары - блог Руслана Новикова / Что касается меня
        //'12' => '0', // ПРОУПСКАЕМ Финансы пропускаенм !!!
    ];


$posts = $wpdb->get_results("SELECT p.*, cat.category_id FROM `wp_posts_old` p LEFT JOIN `wp_post2cat` cat ON p.ID = cat.post_id");


foreach ($posts as $item) {

    if (isset($category_old_to_new[$item->category_id])) {
    // Create post object
        $my_post = [
            'post_title' => $item->post_title,
            'post_content' => $item->post_content,
            'post_status' => 'publish',
            'post_date' => $item->post_date,
            'guid' => $item->guid,
            'post_author' => 1,
            'post_category' => [$category_old_to_new[$item->category_id]]
        ];

        echo wp_insert_post($my_post) . " <br>";

    } else {

        echo "Пропускаем {$item->post_title}<br>";
    }
}

<?php
// импортируем новости из старого ВП 10 летней давности

include('../wp-load.php');
global $wpdb;

$category_old_to_new =
    [
        //NULL
        //3 Hi-tech. Наука и техника. Исследования. Открытия.
        //5 Кибер-безопасность, конфиденциальность
        //6 Занимательная информация
        //8 Экономика. Бизнес. Финансы. Право
        //14 моби
        //15 Перехват запросов браузера приносит хакерам миллио...
        //21 Интернет маркетинг. SEO. Юзабилити.

        '3' => '84', // Эрудиция Гика. Новости High Tech
        '6' => '84', // Занимательная информация или "Это полезно знать"
        '5' => '19', // Безопасность, конфиденциальность, право
        '21' => '53', // E-commerce
        '14' => '84',
        '15' => '5',
    ];

// все новости не нужны - части чно пересекаются со старым источником > 6105 только эти берем
// заранее позаботься о том чтобы соответсующие талицы друпала были в текушем проекте
// novikovdrupal_node  novikovdrupal_term_node  novikovdrupal_node_revisions
//$posts = $wpdb->get_results("SELECT * FROM `novikovdrupal_node` n LEFT JOIN novikovdrupal_term_node t ON `n`.`nid` = `t`.`nid` LEFT JOIN novikovdrupal_node_revisions r ON `n`.`nid` = `r`.`nid` WHERE `n`.`nid` > 6105 and type = 'blog'");
$posts = $wpdb->get_results("SELECT * FROM `novikovdrupal_node` n LEFT JOIN novikovdrupal_term_node t ON `n`.`nid` = `t`.`nid` LEFT JOIN novikovdrupal_node_revisions r ON `n`.`nid` = `r`.`nid` WHERE `n`.`nid` > 6105 and type = 'blog' and language = 'ru'");
//print_r($posts[0]);
//            [title] => Разработчики Metasploit открывают курсы по работе со своей программой
//            [created] => 1279260209
//            [tid] => 5
//            [body] => <p>


foreach ($posts as $item) {
    // есть null
    if (!$item->tid) $item->tid = 3;

    if (isset($category_old_to_new[$item->tid])) {
        // Create post object
        $my_post = [
            'post_title' => $item->title,
            'post_content' => $item->body,
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', $item->created),
            'post_author' => 1,
            'post_category' => [$category_old_to_new[$item->tid]]
        ];

        echo wp_insert_post($my_post) . " <br>";

    } else {

        echo "Пропускаем новость {$item->post_title}<br>";
    }
}

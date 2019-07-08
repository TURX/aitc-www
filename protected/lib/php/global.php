<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/protected/lib/php/date.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/protected/lib/php/translation.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/protected/lib/php/post.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/protected/content/php/navbar.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/protected/content/php/card.php");

function dynamic_element_handle(string $id, array $elements) {
    $content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/protected/content/html/" . $id . ".html");
    $search = array();
    $replace = array();
    foreach ($elements as $key => $value) {
        $search[] = "{{ " . $key . " }}";
        $replace[] = $value;
    }
    $result = str_replace($search, $replace, $content);
    $result = preg_replace("{{.*}}", "", $result);
    return $result;
}

function header_show(string $title, string $current, array $links) {
    if (substr($_SERVER['REQUEST_URI'], -9) == "index.php") exit;
    echo(dynamic_element_handle("header", array(
        "lang" => translation_get("lang_code"),
        "title" => $title . " - " . translation_get("club_name"),
        "club_name" => translation_get("club_name"),
        "navbar_items" => navbar_item_generator($links, $current)
    )));
    $glob_top = markdown_read("global", "top");
    if ($glob_top != "The post does not exist.") {
        echo("<div class='col-12 glob-top'>" . $glob_top . "</div>");
    }
    if ($glob_top == "The post does not exist." && explode("?", $_SERVER['REQUEST_URI'])[0] != "/") {
        if (isset($_SERVER['PATH_INFO'])) {
            $url = htmlspecialchars($_SERVER['PATH_INFO']);
            $url = substr($url, 1);
            $url = explode("/", $url);
            if ($url[0] != "post" && $url[0] != "vid") echo("<div style='padding-top: 75px; display: inline' class='col-12'></div>");
        } else echo("<div style='padding-top: 75px; display: inline' class='col-12'></div>");
    }
}

function footer_show() {
    echo(dynamic_element_handle("footer", array(
        "switch_lang" => translation_get("switch_lang"),
        "club_name" => translation_get("club_name"),
        "follow_us" => translation_get("follow_us"),
        "copyright" => translation_get("copyright")
    )));
}

function card_show(string $type, string $id, array $links) {
    echo(dynamic_element_handle("card", array(
        "addition" => ($id > 9 ? "" : "card-top"),
        "title" => post_meta_get($type, $id)["title-" . translation_get("lang_code")],
        "subtitle" => ($id > 9 ? date_get(substr($id, 0, 8)) : translation_get("top")),
        "links" => card_links_generator($links)
    )));
}
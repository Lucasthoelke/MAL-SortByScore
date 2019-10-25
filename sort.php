<?php
ini_set('max_execution_time', 600);

$user       =   "";
$type       =   "anime";
$malLink    =   "https://myanimelist.net/malappinfo.php?u=" . $user ."&status=plantowatch&type=" . $type;

$malInfo    =   simplexml_load_file($malLink) or die("Error: Cannot create object");

$user       =   $malInfo->myinfo->user_name;

$list       =   $malInfo->anime;
$myList     =   array();

foreach ($list as $item)
{
    $myList[]   =   getAnimeInfo($item->series_title);
}

usort($myList, "sortByScore");

echo "User: " . $user;
debug($myList);

function getAnimeInfo($q)
{
    $username   =   "";
    $password   =   "";
    $url        =   "https://" . $username . ":" . $password ."@myanimelist.net/api/anime/search.xml?q=" . $q;
    $malInfo    =   simplexml_load_file($url) or die("Error: Cannot create object from mal");

    $Anime = new Anime();

    $Anime->id      =   (String)$malInfo->entry[0]->id;
    $Anime->title   =   (String)$malInfo->entry[0]->title[0];
    $Anime->english =   (String)$malInfo->entry[0]->english[0];
    $Anime->episodes=   (int)$malInfo->entry[0]->episodes[0];
    $Anime->score   =   (float)$malInfo->entry[0]->score[0];

    return $Anime;
}

function sortByScore($a, $b)
{
    if ($a->score == $b->score) {
        return 0;
    }
    return ($a->score < $b->score) ? -1 : 1;
}

function debug($input)
{
    echo "<pre>";
    print_r($input);
    echo "</pre>";
}

class Anime
{
    public $id;
    public $title;
    public $english;
    public $episodes;
    public $score;
}

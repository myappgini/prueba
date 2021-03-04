<?php
//not functional yet
include "ElementsHbs.php";

defaults_options();

function defaults_options()//detail modal windows options
{
    $if =  new Widgets;
    $if->InfoBox();
    $if->data->text="Hola Mundo";
    var_dump($if);

    $a = Widgets::a();
    $a->data->text="Hola Mundo-----";
    var_dump($a->InfoBox());
}

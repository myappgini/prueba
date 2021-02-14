<?php

class Btn
{
    private $btn = [
        "enable"    => true,
        "text"      => "Button",
        "color"     => "default",
        "size"      => "",
        "class"     => "",
        "attr"      => "",
        "icon"      => [
                        "enable"    =>true,
                        "icon"      =>"",
        ],
    ];
    public function GetBtn()
    {
        return $btn;
    }
    public function SetAttr($var = "")
    {
        $btn['attr']=$var;
        return;
    }
    public function SetText($var = "")
    {
        $btn['text']=$var;
    }
}

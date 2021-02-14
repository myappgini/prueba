<?php
//not functional yet
function defaults_options()//detail modal windows options
{
    $btn=[
        "enable"=>true,
        "text"=>"Button",
        "color"=>"default",
        "size"=>"",
        "class"=>"",
        "attr"=>"",
        "icon"=>[
            "enable"=>true,
            "icon"=>"",
        ]];
    $close_btn = $btn;
    $close_btn['text']="close";
    $close_btn['icon']['icon']="glyphicon glyphicon-remove";

    return [
        'modal_header'=>[
            "headline"=>"Headline title",
            "id"=>"",
            "size"=>"",
            "dismiss"=>true,
            "header_color"=>"",
            "body_color"=>"",
            "body_class"=>"",
        ],
        'modal_footer'=>[
            "footer_color"=>"",
            "close_btn"=>$close_btn,
        ],
        //send task box options
        'send_box_options'=>[
            "headline"=>"Send Task to user",
            "color"=>"success",
            "solid"=>false,
            "with-border"=>false,
            "class"=>"",
            "attr"=>"",
            "box-tool"=>[
                "enable"=>false,
                "collapsable"=>true,
                "removable"=>true,
            ],
        ],
    ];
}

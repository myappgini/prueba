<?php
class Elements
{
    public $id = "#id";
    public $enable = true;
    public $text = "Text";
}
class Widgets
{
    public $data;
    public function __construct($data=false)
    {
        if (!$data) {
            $data = new Elements;
        }
        $this->data=$data;
    }
    public function InfoBox()
    {
        return
            $this->data;
    }
    public function setText($var)
    {
        $this->data->text=$var;
    }
    public function a()
    {
        return new self();
    }
}

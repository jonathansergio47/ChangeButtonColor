<?php

namespace Js\ChangeButtonColor\Api\Data;

interface ColorInterface
{
    const ID =  'id';
    const COLOR = 'color';

    public function getId();

    public function setId($id);

    public function getColor();

    public function setColor($color);
}

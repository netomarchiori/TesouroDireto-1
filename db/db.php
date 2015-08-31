<?php
 class TesouroDB extends SQLite3
{
    function __construct($location)
    {
        $this->open($location);
    }
}
 ?>

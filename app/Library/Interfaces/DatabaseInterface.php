<?php
// app/Library/Interfaces/DatabaseInterface.php

namespace App\Library\Interfaces;

interface DatabaseInterface
{
    public function connect();
    public function disconnect();
    public function query($sql);
    public function execute($sql, $params = []);
}

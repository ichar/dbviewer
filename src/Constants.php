<?php
// src/Constants.php

namespace App\Constants {

    const VERSION = '1.0';

    function getView(string $id) {

        $views = [
            'sellers'   => 'public."DIC_Sellers_tb"',
            'orders'    => 'public."Orders_tb"',
            'default'   => 'public."Orders_tb"',
        ];

        $value = $views[$id];

        return isset($value) ?  $value : $views['default'];
    }
}

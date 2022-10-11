<?php
// src/Constants.php

namespace App; 

class Constants {

    const VERSION = '1.0';

    #function getVersion() {
    #    return $this->VERSION;
    #}

    function getView(string $id) {
        $views = [
            'sellers'       => 'public."DIC_Sellers_tb"',
            'subdivisions'  => 'public."DIC_Subdivisions_tb"',
            'equipments'    => 'public."DIC_Equipments_tb"',
            'orders'        => 'public."Orders_tb"',
            'vendors'       => 'public."DIC_Vendors_tb"',
            'stock'         => 'public."DIC_StockList_tb"',
            'default'       => 'public."Orders_vw"'
        ];

        $view = $views[$id];

        return isset($view) ? $view : $views['default'];
    }
    
    function getColumListForView(string $pageid, string $key = null) {

        $columns = array(
            'orders' => array(
                'TID'       => ['type' => '', 'title' => 'TID'],
                'Article'   => ['type' => '', 'title' => 'Артикул'],
                'Purpose'   => ['type' => '', 'title' => 'Обоснование'],
                'Price'     => ['type' => '', 'title' => 'Цена'],
                'Currency'  => ['type' => '', 'title' => 'Валюта'],
                'Total'     => ['type' => '', 'title' => 'Количество'],
                'SellerID'  => ['type' => '', 'title' => 'Поставщик'],
                'Status'    => ['type' => '', 'title' => 'Статус']
            )
        );
    
        if (array_key_exists($pageid, $columns)) {
            $items = [];
            foreach ($columns[$pageid] as $column => $item) {
                array_push($items, ($key and in_array($key, $item)) ? $item[$key] : $column);
            }
            return $items;
        }
        else
            return null;
    }
}

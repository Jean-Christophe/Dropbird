<?php

class Order extends OrderCore
{

public static function generateReference()
{
    $last_id = Db::getInstance()->getValue('
        SELECT MAX(id_order)
        FROM '._DB_PREFIX_.'orders');
    return str_pad((int)$last_id + 1, 9, '000000000', STR_PAD_LEFT);
 
}

}
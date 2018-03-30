<?php

namespace PetStore\ApiBundle\Handler;

interface StoreHandlerInterface
{
    public function getInventory();

    public function placeOrder(\Common\DTO\Order $body);

    public function getOrderById(integer $orderId);

    public function deleteOrder(integer $orderId);

}
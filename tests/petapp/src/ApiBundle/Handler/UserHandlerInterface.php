<?php

namespace PetStore\ApiBundle\Handler;

interface UserHandlerInterface
{
    public function createUser(\Common\DTO\User $body);

    public function createUsersWithArrayInput(\Common\DTO\UserCollection $body);

    public function createUsersWithListInput(\Common\DTO\UserCollection $body);

    public function loginUser(\Common\DTO\Query\LoginUserQuery $query);

    public function logoutUser();

    public function getUserByName(string $username);

    public function updateUser(string $username, \Common\DTO\User $body);

    public function deleteUser(string $username);

}
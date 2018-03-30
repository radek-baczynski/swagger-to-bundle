<?php

namespace PetStore\ApiBundle\Handler;

interface PetHandlerInterface
{
    public function addPet(\Common\DTO\Pet $body);

    public function updatePet(\Common\DTO\Pet $body);

    public function findPetsByStatus(\Common\DTO\Query\FindPetsByStatusQuery $query);

    public function findPetsByTags(\Common\DTO\Query\FindPetsByTagsQuery $query);

    public function getPetById(integer $petId);

    public function updatePetWithForm(integer $petId);

    public function deletePet(integer $petId);

    public function uploadFile(integer $petId);

}
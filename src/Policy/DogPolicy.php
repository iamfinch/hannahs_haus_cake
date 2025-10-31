<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Dog;
use Authorization\IdentityInterface;

/**
 * Dog Policy
 *
 * Defines authorization rules for Dog entities
 */
class DogPolicy
{
    /**
     * Check if user can view a dog entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Dog $resource The dog resource.
     * @return bool
     */
    public function canView(?IdentityInterface $user, Dog $resource): bool
    {
        // Anyone can view dogs (public listing)
        return true;
    }

    /**
     * Check if user can add a new dog
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Dog $resource The dog resource.
     * @return bool
     */
    public function canAdd(?IdentityInterface $user, Dog $resource): bool
    {
        // Only admins can add dogs
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user can edit a dog entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Dog $resource The dog resource.
     * @return bool
     */
    public function canEdit(?IdentityInterface $user, Dog $resource): bool
    {
        // Only admins can edit dogs
        // NOTE: Owners CANNOT edit their adopted dogs to prevent manipulation
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user can delete a dog entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Dog $resource The dog resource.
     * @return bool
     */
    public function canDelete(?IdentityInterface $user, Dog $resource): bool
    {
        // Only admins can delete dogs
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user is the owner of the dog
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\Dog $resource The dog resource.
     * @return bool
     */
    public function isOwner(?IdentityInterface $user, Dog $resource): bool
    {
        // Check if user has adopted this dog
        return $user && $resource->userId !== null && $user->getIdentifier() == $resource->userId;
    }
}

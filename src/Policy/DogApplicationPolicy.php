<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\DogApplication;
use Authorization\IdentityInterface;

/**
 * DogApplication Policy
 *
 * Defines authorization rules for DogApplication entities
 */
class DogApplicationPolicy
{
    /**
     * Check if user can view a dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canView(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Admins can view all applications
        if ($user && $user->get('isAdmin')) {
            return true;
        }

        // Users can view their own applications
        return $user && $user->getIdentifier() == $resource->userId;
    }

    /**
     * Check if user can add a new dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canAdd(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Only authenticated users can submit applications
        return $user !== null;
    }

    /**
     * Check if user can edit a dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canEdit(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Admins can edit any application (for approval workflow)
        if ($user && $user->get('isAdmin')) {
            return true;
        }

        // Users can edit their own PENDING applications only
        if ($user && $user->getIdentifier() == $resource->userId) {
            return $resource->approved === '0';  // Only pending applications
        }

        return false;
    }

    /**
     * Check if user can delete a dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canDelete(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Only admins can delete applications
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user can approve a dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canApprove(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Only admins can approve applications
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user can reject a dog application
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\DogApplication $resource The dog application resource.
     * @return bool
     */
    public function canReject(?IdentityInterface $user, DogApplication $resource): bool
    {
        // Only admins can reject applications
        return $user && $user->get('isAdmin');
    }
}

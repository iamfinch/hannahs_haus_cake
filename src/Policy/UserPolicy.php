<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * User Policy
 *
 * Defines authorization rules for User entities
 */
class UserPolicy
{
    /**
     * Check if user can view a user entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\User $resource The user resource.
     * @return bool
     */
    public function canView(?IdentityInterface $user, User $resource): bool
    {
        // Admins can view anyone
        if ($user && $user->get('isAdmin')) {
            return true;
        }

        // Users can view their own profile
        return $user && $user->getIdentifier() == $resource->id;
    }

    /**
     * Check if user can edit a user entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\User $resource The user resource.
     * @return bool
     */
    public function canEdit(?IdentityInterface $user, User $resource): bool
    {
        // Admins can edit anyone
        if ($user && $user->get('isAdmin')) {
            return true;
        }

        // Users can edit their own profile
        return $user && $user->getIdentifier() == $resource->id;
    }

    /**
     * Check if user can delete a user entity
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\User $resource The user resource.
     * @return bool
     */
    public function canDelete(?IdentityInterface $user, User $resource): bool
    {
        // Only admins can delete users
        return $user && $user->get('isAdmin');
    }

    /**
     * Check if user can add a new user
     *
     * @param \Authorization\IdentityInterface|null $user The user.
     * @param \App\Model\Entity\User $resource The user resource.
     * @return bool
     */
    public function canAdd(?IdentityInterface $user, User $resource): bool
    {
        // Anyone can register (add a new user)
        return true;
    }
}

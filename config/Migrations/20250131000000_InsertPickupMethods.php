<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InsertPickupMethods extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Seed default pickup methods for dog adoptions
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('pickup_methods');

        $data = [
            [
                'name' => 'Flexible',
                'description' => 'Open to either pickup or delivery arrangements'
            ],
            [
                'name' => 'Will Pickup',
                'description' => 'Adopter will pick up the dog at a designated location'
            ],
            [
                'name' => 'Requires Delivery',
                'description' => 'Adopter requires the dog to be delivered to their location'
            ]
        ];

        $table->insert($data)->save();
    }
}

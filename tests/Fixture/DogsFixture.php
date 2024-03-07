<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DogsFixture
 */
class DogsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'dateBorn' => '2024-03-05 20:50:52',
                'color' => 'Lorem ipsum dolor sit amet',
                'retired' => 1,
                'retiredDate' => '2024-03-05 20:50:52',
                'adopted' => 1,
                'adoptedDate' => '2024-03-05 20:50:52',
                'userId' => 1,
            ],
        ];
        parent::init();
    }
}

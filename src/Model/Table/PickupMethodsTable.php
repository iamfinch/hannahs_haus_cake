<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PickupMethods Model
 *
 * @property \App\Model\Table\DogApplicationTable&\Cake\ORM\Association\HasMany $DogApplications
 *
 * @method \App\Model\Entity\PickupMethod newEmptyEntity()
 * @method \App\Model\Entity\PickupMethod newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PickupMethod[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PickupMethod get($primaryKey, $options = [])
 * @method \App\Model\Entity\PickupMethod findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\PickupMethod patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PickupMethod[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PickupMethod|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PickupMethod saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PickupMethod[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PickupMethod[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\PickupMethod[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PickupMethod[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PickupMethodsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('pickup_methods');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // Pickup method can be used in many applications
        $this->hasMany('DogApplications', [
            'foreignKey' => 'pickupMethodId',
            'dependent' => false
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 72)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DogApplication Model
 *
 * @method \App\Model\Entity\DogApplication newEmptyEntity()
 * @method \App\Model\Entity\DogApplication newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DogApplication[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DogApplication get($primaryKey, $options = [])
 * @method \App\Model\Entity\DogApplication findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DogApplication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DogApplication[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DogApplication|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DogApplication saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DogApplication[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DogApplication[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DogApplication[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DogApplication[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DogApplicationTable extends Table
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

        $this->setTable('dog_application');
        $this->setDisplayField('approved');
        $this->setPrimaryKey('id');
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
            ->nonNegativeInteger('userId')
            ->requirePresence('userId', 'create')
            ->notEmptyString('userId');

        $validator
            ->nonNegativeInteger('dogId')
            ->requirePresence('dogId', 'create')
            ->notEmptyString('dogId');

        $validator
            ->nonNegativeInteger('pickupMethodId')
            ->requirePresence('pickupMethodId', 'create')
            ->notEmptyString('pickupMethodId');

        $validator
            ->dateTime('dateCreated')
            ->requirePresence('dateCreated', 'create')
            ->notEmptyDateTime('dateCreated');

        $validator
            ->scalar('approved')
            ->notEmptyString('approved');

        $validator
            ->dateTime('approvedDate')
            ->allowEmptyDateTime('approvedDate');

        return $validator;
    }
}

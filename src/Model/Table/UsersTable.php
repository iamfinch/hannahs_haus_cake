<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('fname');
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
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('phoneNumber')
            ->maxLength('phoneNumber', 255)
            ->allowEmptyString('phoneNumber');

        $validator
            ->scalar('password')
            ->maxLength('password', 72)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('fname')
            ->maxLength('fname', 72)
            ->requirePresence('fname', 'create')
            ->notEmptyString('fname');

        $validator
            ->scalar('lname')
            ->maxLength('lname', 72)
            ->requirePresence('lname', 'create')
            ->notEmptyString('lname');

        $validator
            ->nonNegativeInteger('countryId')
            ->requirePresence('countryId', 'create')
            ->notEmptyString('countryId');

        $validator
            ->nonNegativeInteger('stateId')
            ->requirePresence('stateId', 'create')
            ->notEmptyString('stateId');
        
        $validator
            ->scalar('address1')
            ->maxLength('address1', 255)
            ->requirePresence('address1', 'create')
            ->notEmptyString('address1');

        $validator
            ->scalar('address2')
            ->maxLength('address2', 255)
            ->allowEmptyString('address2');;

        $validator
            ->integer('zipcode')
            ->requirePresence('zipcode', 'create')
            ->notEmptyString('zipcode');

        $validator
            ->nonNegativeInteger('housingTypeId')
            ->requirePresence('housingTypeId', 'create')
            ->notEmptyString('housingTypeId');

        $validator
            ->boolean('hasChildren')
            ->notEmptyString('hasChildren');

        $validator
            ->boolean('everOwnedDogs')
            ->notEmptyString('everOwnedDogs');

        $validator
            ->boolean('primaryCareTaker')
            ->notEmptyString('primaryCareTaker');

        $validator
            ->boolean('isAdmin')
            ->notEmptyString('isAdmin');

        $validator
            ->dateTime('dateCreated')
            ->requirePresence('dateCreated', 'create')
            ->notEmptyDateTime('dateCreated');

        $validator
            ->dateTime('lastModified')
            ->requirePresence('lastModified', 'create')
            ->notEmptyDateTime('lastModified');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
}

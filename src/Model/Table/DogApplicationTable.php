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

        // Application belongs to a User (the applicant)
        $this->belongsTo('Users', [
            'foreignKey' => 'userId',
            'joinType' => 'INNER'
        ]);

        // Application belongs to a Dog
        $this->belongsTo('Dogs', [
            'foreignKey' => 'dogId',
            'joinType' => 'INNER'
        ]);

        // Application belongs to a PickupMethod
        $this->belongsTo('PickupMethods', [
            'foreignKey' => 'pickupMethodId',
            'joinType' => 'INNER'
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Foreign key validation
        $rules->add($rules->existsIn(['userId'], 'Users'), [
            'errorField' => 'userId',
            'message' => 'User does not exist'
        ]);
        $rules->add($rules->existsIn(['dogId'], 'Dogs'), [
            'errorField' => 'dogId',
            'message' => 'Dog does not exist'
        ]);
        $rules->add($rules->existsIn(['pickupMethodId'], 'PickupMethods'), [
            'errorField' => 'pickupMethodId',
            'message' => 'Pickup method does not exist'
        ]);

        // Prevent duplicate pending applications for the same dog by the same user
        $rules->add(function ($entity, $options) {
            if (!$entity->isNew()) {
                return true; // Only check on create
            }

            $existingApplication = $this->find()
                ->where([
                    'userId' => $entity->userId,
                    'dogId' => $entity->dogId,
                    'approved' => '0'  // Pending status
                ])
                ->first();

            return $existingApplication === null;
        }, 'uniquePendingApplication', [
            'errorField' => 'dogId',
            'message' => 'You already have a pending application for this dog'
        ]);

        return $rules;
    }

    /**
     * Find pending applications for a specific dog
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Options including 'dogId'
     * @return \Cake\ORM\Query
     */
    public function findPendingForDog(Query $query, array $options): Query
    {
        return $query->where([
            'DogApplication.dogId' => $options['dogId'],
            'DogApplication.approved' => '0'
        ]);
    }

    /**
     * Find all applications by a specific user
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Options including 'userId'
     * @return \Cake\ORM\Query
     */
    public function findByUser(Query $query, array $options): Query
    {
        return $query->where(['DogApplication.userId' => $options['userId']]);
    }
}

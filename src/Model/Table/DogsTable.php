<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dogs Model
 *
 * @method \App\Model\Entity\Dog newEmptyEntity()
 * @method \App\Model\Entity\Dog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Dog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dog get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Dog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DogsTable extends Table
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

        $this->setTable('dogs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // Dog belongs to a User (the adopting owner) - nullable
        $this->belongsTo('Owners', [
            'className' => 'Users',
            'foreignKey' => 'userId',
            'joinType' => 'LEFT',  // LEFT JOIN because userId can be null
            'propertyName' => 'owner'
        ]);

        // Dog can have many applications
        $this->hasMany('DogApplications', [
            'foreignKey' => 'dogId',
            'dependent' => true  // Delete applications if dog is deleted
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->dateTime('dateBorn')
            ->requirePresence('dateBorn', 'create')
            ->notEmptyDateTime('dateBorn');

        $validator
            ->scalar('color')
            ->maxLength('color', 72)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        $validator
            ->boolean('retired')
            ->notEmptyString('retired');

        $validator
            ->dateTime('retiredDate')
            ->allowEmptyDateTime('retiredDate');

        $validator
            ->boolean('adopted')
            ->notEmptyString('adopted');

        $validator
            ->dateTime('adoptedDate')
            ->allowEmptyDateTime('adoptedDate');

        $validator
            ->nonNegativeInteger('userId')
            ->allowEmptyString('userId');

        return $validator;
    }

    /**
     * Find available dogs (not adopted, not retired)
     *
     * @param \Cake\ORM\Query $query The query object
     * @return \Cake\ORM\Query
     */
    public function findAvailable(Query $query): Query
    {
        return $query->where([
            'Dogs.adopted' => false,
            'Dogs.retired' => false
        ]);
    }

    /**
     * Find adopted dogs for a specific user
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Options including 'userId'
     * @return \Cake\ORM\Query
     */
    public function findAdoptedByUser(Query $query, array $options): Query
    {
        return $query->where([
            'Dogs.userId' => $options['userId'],
            'Dogs.adopted' => true
        ]);
    }
}

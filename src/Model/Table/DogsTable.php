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
}

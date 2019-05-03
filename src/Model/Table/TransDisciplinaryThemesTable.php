<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransDisciplinaryThemes Model
 *
 * @property \App\Model\Table\UnitsTable|\Cake\ORM\Association\HasMany $Units
 *
 * @method \App\Model\Entity\TransDisciplinaryTheme get($primaryKey, $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TransDisciplinaryTheme findOrCreate($search, callable $callback = null, $options = [])
 */
class TransDisciplinaryThemesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('trans_disciplinary_themes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Units', [
            'foreignKey' => 'trans_disciplinary_theme_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        return $validator;
    }
}

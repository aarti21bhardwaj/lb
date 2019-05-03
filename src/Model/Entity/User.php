<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;



/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $dob
 * @property int $role_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $image_path
 * @property $image_name
 * @property int $school_id
 * @property string $legacy_id
 * @property string $gender
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\SchoolUser[] $school_users
 * @property \App\Model\Entity\ResetPasswordHash[] $reset_password_hashes
 * @property \App\Model\Entity\Section[] $sections
 * @property \App\Model\Entity\CampusCourseTeacher[] $campus_course_teachers
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'first_name' => true,
        'middle_name' => true,
        'last_name' => true,
        'email' => true,
        'password' => true,
        'dob' => true,
        'role_id' => true,
        'created' => true,
        'modified' => true,
        'image_path' => true,
        'image_name' => true,
        'school_id' => true,
        'legacy_id' => true,
        'gender' => true,
        'role' => true,
        'school_users' => true,
        'reset_password_hashes' => true,
        'sections' => true,
        'campus_course_teachers' => true,
        'campus_teachers' => true,
        'windows_ad_id' => true,
        'user_permissions' => true,
        'student_guardians' => true,
        'is_active' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
    protected function _setPassword($value){
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);    
    }
    
    /*protected function _getFullName()
    {
        pr('in enitity'); die;
        return $this->_properties['first_name']. '  ' .
        $this->_properties['middle_name'] . '  ' .
        $this->_properties['last_name'];
    }*/

    protected $_virtual = ['image_url','full_name'];

    protected function _getImageUrl()
    {   if(Configure::read('getImageFromBlackBaud') && isset($this->_properties['role_id']) && in_array($this->_properties['role_id'], [1, 3, 4]) && !in_array($this->_properties['legacy_id'], [null, false, ''])) {
            //$url = Router::url('/img/default-img.jpeg',true);
	  //  $url = Router::url("/users/getPicFromBlackBaud/".$this->_properties['legacy_id'], true);
            $url = Router::url("/user_images/".$this->_properties['id'].".jpg", true);

            return $url;
        }

        if(isset($this->_properties['image_name']) && !empty($this->_properties['image_name'])) {
            $url = Router::url('/profiles/'.$this->_properties['image_name'],true);
        }else{
            $url = Router::url('/img/default-img.jpeg',true);
        }
            // pr($url); die('ss');
        return $url;

    }

    protected function _getFullName()
   {   //Was giving an error in new entity
        if(!isset($this->_properties['first_name']) && !isset($this->_properties['last_name'])){
            return false;
        }

        if(!empty($this->_properties['middle_name']) && $this->_properties['middle_name']){
            $name = $this->_properties['first_name'] . ' ' .$this->_properties['middle_name']. ' ' .
           $this->_properties['last_name'];
        }else{
            $name = $this->_properties['first_name'] . ' ' .
           $this->_properties['last_name'];
        }

       return $name;
   }
}

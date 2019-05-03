<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * SectionStudents Controller
 *
 * @property \App\Model\Table\SectionStudentsTable $SectionStudents
 *
 * @method \App\Model\Entity\SectionStudent[] paginate($object = null, array $settings = [])
 */
class SectionStudentsController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sectionId = $this->request->query['section_id'];
        $sectionStudents = $this->SectionStudents->find()
                            ->contain(['Sections', 'Students']);

        if($sectionId){
            $sectionStudents =$sectionStudents->where(['section_id'=>$sectionId]);
        }
       
        $sectionStudents = $sectionStudents->sortBy('student.last_name',SORT_ASC,SORT_STRING)->toArray();
        $sectionStudents = array_values($sectionStudents);
        $success = true;

        $this->set('data',$sectionStudents);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }
}

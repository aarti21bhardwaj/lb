<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Collection\Collection;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Exception\ConflictException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Impacts Controller
 *
 * @property \App\Model\Table\ImpactsTable $Impacts
 *
 * @method \App\Model\Entity\Impact[] paginate($object = null, array $settings = [])
 */
class ImpactsController extends ApiController
{

     public function index()
    {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Bad Request');
        }

        $impactCategoriesData = $this->Impacts->ImpactCategories->find()->all();

        $impactCategoriesIds = $impactCategoriesData->extract('id')->toArray();

        $impactCategories = $impactCategoriesData->map(function($value, $key){
                                                        $value->parent_id = NULL;
                                                        $value->id = 'impact_category'.$value->id;
                                                        return $value;
                                                    })
                                                 ->toArray();

        $impacts = $this->Impacts->find()->where(['impact_category_id IN' => $impactCategoriesIds])
                                         ->map(function($value, $key){
                                            if($value->parent_id == NULL){
                                                $value->parent_id = 'impact_category'.$value->impact_category_id;
                                            }
                                            return $value;
                                         })
                                         ->toArray();


        foreach ($impactCategories as $key => $value) {
            $impacts[] = $value;
        }

        $impactsData = (new Collection($impacts))->nest('id', 'parent_id')->toArray();
        
        $success = true;

        $this->set('data',$impactsData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
        
    }


    public function getImpacts($courseId = null, $unitId)
    {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Bad Request');
       }

        if(!$courseId){
            throw new BadRequestException("Missing course id", 1);
        }

        $this->loadModel('Courses');
        $courseData = $this->Courses->find()->where(['id' => $courseId])->contain(['Units'])->first();
        // pr($courseData); die;

        // $courseUnitIds = (new Collection($courseData->units))->extract('id')->toArray();
        // pr($courseUnitIds); die;

        $impactGrades = $this->Impacts->GradeImpacts->find()->where(['grade_id' => $courseData->grade_id])->all()->extract('impact_id')->toArray();
        if(!$impactGrades){
            throw new NotFoundException('No impacts found for grade id '.$courseData->grade_id.' in course '.$courseData->name);
        }

        $impactCategoriesData = $this->Impacts->ImpactCategories->find()->all();

        $impactCategoriesIds = $impactCategoriesData->extract('id')->toArray();

        $impactCategories = $impactCategoriesData->map(function($value, $key){
                                                        $value->parent_id = NULL;
                                                        $value->id = 'impact_category'.$value->id;
                                                        return $value;
                                                    })
                                                 ->toArray();
        // pr($impactCategories); die;
        $impacts = $this->Impacts->find()->where(['id IN' => $impactGrades])
                                         ->contain(['UnitImpacts' => function($q) use($unitId){
                                            return $q->where(['unit_id' => $unitId]);
                                         }])
                                         ->map(function($value, $key){
                                            if($value->parent_id == NULL){
                                                $value->parent_id = 'impact_category'.$value->impact_category_id;
                                            }
                                             if(!empty($value->unit_impacts)){
                                               $value->is_unit_impact = true;
                                           }else{
                                               $value->is_unit_impact = false;
                                           }
                                                       
                                           unset($value->unit_impacts);
                                           return $value;
                                        })     
                                           ->toArray();


        // pr($impacts); die;

        if(!$impacts){
            throw new NotFoundException("Impacts not found", 1);
            
        }

        foreach ($impactCategories as $key => $value) {
            $impacts[] = $value;
        }

        $impactsData = (new Collection($impacts))->nest('id', 'parent_id')
                                                 ->reject(function($value, $key){
                                                    return in_array($value->children, [null, false, '', []]);
                                                  })
          
                                                 ->toArray();
        
        $impactsData = array_values($impactsData);
        $success = true;

        $this->set('data',$impactsData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
        
    }

    // api use in student evidence
    public function viewImpacts()
    {
       if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Bad Request');
       }

        // $data = $this->request->data; 

        $this->loadModel('Courses');
        // pr($data); die;
        $courseIds = $this->request->data;
        
        $gradeIds = $this->Courses->find()->where(['id IN' => $courseIds])->extract('grade_id')->toArray();
        // pr($courseData); die;

        // $courseUnitIds = (new Collection($courseData->units))->extract('id')->toArray();
        // pr($courseUnitIds); die;

        $impactGrades = $this->Impacts->GradeImpacts->find()->where(['grade_id IN' => $gradeIds])->all()->extract('impact_id')->toArray();
        if(!$impactGrades){
            throw new NotFoundException('No impacts found for grade ids '.implode(',', $gradeIds).' in course '.implode(',', $courseIds));
        }

        $impactCategoriesData = $this->Impacts->ImpactCategories->find()->all();

        $impactCategoriesIds = $impactCategoriesData->extract('id')->toArray();

        $impactCategories = $impactCategoriesData->map(function($value, $key){
                                                        $value->parent_id = NULL;
                                                        $value->id = 'impact_category'.$value->id;
                                                        return $value;
                                                    })
                                                 ->toArray();
        // pr($impactCategories); die;
        $impacts = $this->Impacts->find()->where(['id IN' => $impactGrades])
                                         // ->contain(['UnitImpacts'])
                                         ->map(function($value, $key){
                                            // pr($value);
                                            if($value->parent_id == NULL){
                                                $value->selectable = true;
                                                $value->parent_id = 'impact_category'.$value->impact_category_id;
                                            }
                                           //   if(!empty($value->unit_impacts)){
                                           //     $value->is_unit_impact = true;
                                           // }else{
                                           //     $value->is_unit_impact = false;
                                           // }
                                                       
                                           // unset($value->unit_impacts);
                                           return $value;
                                        })     
                                           ->toArray();


        // pr($impacts); die;

        if(!$impacts){
            throw new NotFoundException("Impacts not found", 1);
            
        }

        foreach ($impactCategories as $key => $value) {
            $impacts[] = $value;
        }

        $impactsData = (new Collection($impacts))->nest('id', 'parent_id')
                                                 ->reject(function($value, $key){
                                                    return in_array($value->children, [null, false, '', []]);
                                                  })
          
                                                 ->toArray();
        
        $impactsData = array_values($impactsData);
        $success = true;

        $this->set('data',$impactsData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
        
    }

    /*Frameworks for teacher evidence*/

    public function getFrameworks()
    {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Bad Request');
       }

       $this->loadModel('TeacherEvidenceSelectableImpacts');
       $selectableImpactCategoryIds = $this->TeacherEvidenceSelectableImpacts->find()->all()->extract('impact_category_id')->toArray();
       if(!$selectableImpactCategoryIds) {
        throw new NotFoundException('No data found in Teacher evidences selectable impacts');
       }

        $impactCategoriesData = $this->Impacts->ImpactCategories->find()->where(['id IN' => $selectableImpactCategoryIds])->all();

        $impactCategories = $impactCategoriesData->map(function($value, $key){
                                                        $value->parent_id = NULL;
                                                        $value->id = 'impact_category'.$value->id;
                                                        return $value;
                                                    })
                                                 ->toArray();
        $impacts = $this->Impacts->find()
                                        ->where(['impact_category_id IN' => $selectableImpactCategoryIds])
                                         // ->contain(['UnitImpacts'])
                                         ->map(function($value, $key){
                                            // pr($value);
                                            if($value->parent_id != NULL){
                                                $value->selectable = true;
                                            }
                                            if($value->parent_id == NULL){
                                                $value->parent_id = 'impact_category'.$value->impact_category_id;
                                            }
                                           //   if(!empty($value->unit_impacts)){
                                           //     $value->is_unit_impact = true;
                                           // }else{
                                           //     $value->is_unit_impact = false;
                                           // }
                                                       
                                           // unset($value->unit_impacts);
                                           return $value;
                                        })     
                                           ->toArray();


        // pr($impacts); die;

        if(!$impacts){
            throw new NotFoundException("Impacts not found", 1);
            
        }

        foreach ($impactCategories as $key => $value) {
            $impacts[] = $value;
        }

        $impactsData = (new Collection($impacts))->nest('id', 'parent_id')
                                                 ->reject(function($value, $key){
                                                    return in_array($value->children, [null, false, '', []]);
                                                  })
          
                                                 ->toArray();
        
        $impactsData = array_values($impactsData);
        $success = true;

        $this->set('data',$impactsData);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
        
    }
    /**
     * View method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
       if(!$this->request->is('get')){
            throw new MethodNotAllowedException('Bad Request');
        }

        $impact = $this->Impacts->findById($id)->first();

        if(!$impact){
            throw new BadRequestException("Record not found");
        }

        $success = true;

        $this->set('data',$impact);
        $this->set('status',$success);
        $this->set('_serialize', ['status','data']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException('Bad Request');
        }
        $data = $this->request->data; 

        if(empty($data['name'])){
            throw new BadRequestException("Missing field name");
            
        }

        $impact = $this->Impacts->newEntity();
        $impact = $this->Impacts->patchEntity($impact, $data);

        if(!$this->Impacts->save($impact)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = [
                                'id' => $impact->id,
                                'text' => $impact->name,
                                'parent_id' => $impact->parent_id
                            ];

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if(!$this->request->is('put')){
            throw new MethodNotAllowedException('Bad Request');
        }
        $data = $this->request->data; 

        if(empty($data)){
            throw new BadRequestException("Empty request data");
            
        }

        $impact = $this->Impacts->find()->where(['id' => $id])->first();

        if(!$impact){
            throw new BadRequestException("Record not found");
        }

        $impact = $this->Impacts->patchEntity($impact, $data);

        if(!$this->Impacts->save($impact)){
            throw new InternalErrorException('Something went wrong');
        }

        $response = array();
        $response['status'] = true;
        $response['data'] = [
                                'id' => $impact->id,
                                'text' => $impact->name,
                                'parent_id' => $impact->parent_id
                            ];

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Impact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if(!$this->request->is(['post','delete'])){
            throw new MethodNotAllowedException('Resquest is not delete');
        }

        $impact = $this->Impacts->find()->where(['id' => $id])->first();
        
        if(!$impact){
            throw new BadRequestException("Record not found");
            
        }

         if ($this->Impacts->delete($impact)) {
            $response['status'] = true;
            $response['message'] = "Deleted Successfully";
        } else {
            throw new InternalErrorException('Something went wrong');
            
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
    

    public function impactsByImpactCategories(){
        if(!$this->request->is(['post'])){
            throw new MethodNotAllowedException('This request is not post');
        }
        $data = $this->request->data;

        if(empty($data['impact_category_id'])){
            throw new BadRequestException("Missing field impact_category_id");
            
        }

        $impacts = $this->Impacts->find()
                                 ->where(['impact_category_id' => $data['impact_category_id']])
                                 ->all()
                                 ->map(function($value, $key) {
                                        $value->text = $value->name; 
                                        return $value;
                                     })
                                ->toArray();
        
        $impacts = (new Collection($impacts))->nest('id','parent_id')->toArray();
        $resData = array();
        if(!$impacts){
            $resData['status'] = false;
            $resData['message'] = 'No impacts till yet for this category';
        }else{
            $resData['status'] = true;
            $resData['data'] = $impacts;
        }

        $this->set('resData', $resData);
        $this->set('_serialize', ['resData']);

    }
}

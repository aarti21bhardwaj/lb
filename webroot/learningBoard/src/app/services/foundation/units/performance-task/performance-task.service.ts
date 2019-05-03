import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../../../configuration/custom-https.service';
import { AppSettings } from '../../../../app-settings';
@Injectable()
export class PerformanceTaskService {

  constructor(
    public http: CustomHttpsService
  ) {
    
   }


  addNewTask(name, start_date=null, end_date=null, unitId, description, courseId, assessmentId, assessmentTypeId,
    isAccessible, assessmentSubtypeId = null, isDigitaltoolUsed = null) {

      console.log('In servive  of add task' + isDigitaltoolUsed)
    // courseId = 1;
    let body = {
      name: name,
      start_date: start_date,
      end_date: end_date,
      description:description,
      assessment_type_id:assessmentTypeId,
      is_accessible : isAccessible,
      assessment_subtype_id: assessmentSubtypeId,
      is_digital_tool_used: isDigitaltoolUsed
    }
    
    if(assessmentId){
      return this.http.put(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId, body);
    }else{
      return this.http.post(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments', body);
    }
    
  }

  getAssessmentStandards(assessmentId, id = null){
    return this.http.get(AppSettings.API_ENDPOINT + 'units/assessmentStandards/'+assessmentId + '/' + id);
  }
  getImpacts(courseId, unitId = null){
    return this.http.get(AppSettings.API_ENDPOINT + 'impacts/getImpacts/' + courseId + '/' + unitId) ;
  }  
  getAssessmentImpacts(assessmentId){
    return this.http.get(AppSettings.API_ENDPOINT + 'units/standards/1');
  }  
  getTaskList(courseId, unitId, assessmentTypeId=null){
    let url = AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/assessments';
    if(assessmentTypeId){
      url = url +  '?assessment_type_id=' + assessmentTypeId;
    }
    return this.http.get(url);
  }

  selectStandards(courseId,unitId,assessmentId,standardId){
    const body = {
      standard_id: standardId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId+'/assessment_standards/', body);
  }
  unselectStandards(courseId,unitId,assessmentId,standardId){
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId+'/assessment_standards/'+standardId);
  }

  /* Get Select standards (curriculum, grades and learning areas) */
  getSelectedStandards() {
    return this.http.get(AppSettings.API_ENDPOINT + 'standards/get');
  }

  /* Post Selected Assessment Standards */
  addMoreStandards(courseId, unitId, assessmentId, learningAreaId, gradeId) {
    const body = {
      'learning_area_id': learningAreaId,
      'grade_id': gradeId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/'
     + unitId + '/assessments/' + assessmentId + '/assessment_strands/', body);
  }

  /* Add more unit standards */
  addMoreUnitStandards(courseId, unitId, learningAreaId, gradeId) {
    const body = {
      'learning_area_id': learningAreaId,
      'grade_id': gradeId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/'
      + unitId  + '/unit_strands/', body);
  }

  selectImpact(courseId, unitId, assessmentId, impactId) {
    const body = {
      impact_id: impactId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId+'/assessment_impacts/', body);
  }
  unselectImpact(courseId,unitId,assessmentId,impactId){
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId+'/assessment_impacts/'+impactId);
  }
  deleteTask(courseId,unitId,assessmentId){
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/'+courseId+'/units/'+unitId+'/assessments/'+assessmentId);
  }

  /* For Unit Standard */

  getUnitStandards(unitId) {
    console.log('In get unit ' + unitId);
    return this.http.get(AppSettings.API_ENDPOINT + 'units/unitStandards/' + unitId);
  }

  addUnitStandards(courseId, unitId, standardId) {
    const body = {
      'standard_id': standardId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_standards', body);
  }

  deleteUnitStandard(courseId, unitId, standardId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_standards/' + standardId);
  }

  getSelectedUnitStandards(courseId, unitId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_standards');
  }
  /* For Unit Impact */

  /* Get impacts will be used to get all impacts */
  addUnitImpact(courseId, unitId, impactId) {
    const body = {
      'impact_id': impactId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_impacts', body);
  }

  deleteUnitImpact(courseId, unitId, impactId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_impacts/' + impactId);
  }

  getSelectedUnitImpacts(courseId, unitId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/' + unitId + '/unit_impacts');
  }

  /* For specific Assessment contents */
  addSpecificAssessment(courseId, unitId, assessmentId, contentCategoryId, data) {
    const body = {
      description: data
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/'
      + unitId + '/assessments/' + assessmentId + '/content_categories/'
       + contentCategoryId + '/assessment_contents/addSpecificContent', body);
  }

  /* Index of assessment specific contents */
  getSpecificContent(courseId, unitId, assessmentId, contentCategoryId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/'
      + unitId + '/assessments/' + assessmentId + '/content_categories/'
      + contentCategoryId + '/assessment_contents/indexSpecificContent');
  }

  /* Edit assessment specific content */
  editspecificContent(courseId, unitId, assessmentId, contentCategoryId, id, data) {
    const body = {
      description: data
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/'
      + unitId + '/assessments/' + assessmentId + '/content_categories/'
      + contentCategoryId + '/assessment_contents/editSpecificContent/' + id, body);
  }
}

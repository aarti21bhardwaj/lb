import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../../configuration/custom-https.service';
import { AppSettings } from '../../../app-settings';

@Injectable()
export class UnitsService {
  unitData:any
  constructor(
    public http: CustomHttpsService
  ) { }

  getUnits(course_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/'+course_id+'/units/');
  }

  getUnit(course_id, unit_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/'+course_id+'/units/'+unit_id);
  }

  getSectionEvent(sectionId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'sectionEvents/view/'+sectionId);
  }

  /* Get all unit types */
  getTypes()  {
    return this.http.get(AppSettings.API_ENDPOINT + 'types');
  }

  /* Get alll transdisciplinary theme */
  getTransTheme() {
    return this.http.get(AppSettings.API_ENDPOINT + 'transDisciplinaryThemes');
  }

  getAcademicAndTerms(){
    return this.http.get(AppSettings.API_ENDPOINT + 'units/academicTerms');
  }

  publishSectionEvent(atrributeId,attributeType,sectionId,startDate,endDate){
    console.log('in pub action vent');
    console.log(startDate);
    console.log('endDate');
    console.log(endDate);
    let body = [{
      start_date: startDate,
      end_date: endDate,
      section_id: sectionId,
      unit_id : null,
      assessment_id : null
    }];
    console.log('body');
    console.log(body);
    if(attributeType == 'unit'){
      body[0].unit_id= atrributeId;
    }
    if(attributeType == 'assessment'){
      body[0].assessment_id= atrributeId;
    }
    
    return this.http.post(AppSettings.API_ENDPOINT + 'sectionEvents/add', body);
  }
  // Adds a resource to the unit
  addUnitResource(course_id, unit_id, data) {
    console.log(data);
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/'+course_id+'/units/'+unit_id, data);
  }

  // Uploads a file and returns its file path and name
  postFile(course_id, unit_id, fileToUpload: File){
    const formData: FormData = new FormData();
    formData.append('fileKey', fileToUpload, fileToUpload.name);
    return this.http
      .post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' +  unit_id + '/unit_resources/uploadResources' , 
          formData)
      .map(() => { return true; })
  }
  saveUnit(name, description, start_date, end_date, template_id, courseId) {
    const body = {
      name: name,
      description: description,
      start_date: start_date,
      end_date: end_date,
      template_id: template_id,
      trans_disciplinary_theme_id: 1
    }

    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units', body);
  }
  editUnits(course_id, unit_id, data) {
    console.log('In unit data edit' + data);
    return this.http.put(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id, data)
  }

  /* Get unit specific Contents */
  getUnitSpecificContent(course_id, unit_id, category_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents/indexSpecificContent');
  }

  /* Add unit specific transfer goals */
  addUnitSpecificContent(course_id, unit_id, category_id, specificGoals) {
    const data = {
      'text':  specificGoals
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents/addContent', data)
  }

  /* Edit unit specific content */
  editUnitSpecificContent(course_id, unit_id, category_id, specificContent, id) {
    const data = {
      'text': specificContent
    }
    return this.http.put(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents/editContent/' + id, data)
  }

  deleteUnitSpecificContent(course_id, unit_id, category_id, id) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents/removeContent/' + id)
  }

  /* Add common unit content */
  addUnitcontent(course_id, unit_id, category_id, contentValueId) {
    const data = {
      'content_value_id' : contentValueId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents', data);
  }

  /* Delete common unit content */
  deleteUnitContent(course_id, unit_id, category_id, contentValueId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents/' + contentValueId)
  }

  /* Index unit content */
  getUnitContent(course_id, unit_id, category_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/content_categories/' + category_id + '/unit_contents');
  }

  /* Add unit content in assessment contents */
  addAssessmentContents(course_id, unit_id, category_id, contentValueId, assessment_id, type) {
    if (type === 'specificContent') {
      const data = {
        'unit_specific_content_id': contentValueId
      }
      return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id +
        '/units/' + unit_id + '/assessments/' + assessment_id + '/content_categories/' + category_id + '/assessment_contents', data);
    }
    if (type === 'commonContent') {
      const data = {
        'content_value_id': contentValueId
      }
      return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id +
        '/units/' + unit_id + '/assessments/' + assessment_id + '/content_categories/' + category_id + '/assessment_contents', data);
    }
  }

  /* Delete unit content in assessmennt contents */
  deleteAssessmentContents(course_id, unit_id, category_id, contentValueId, assessment_id, type) {
    let queryString = '?'
    if (type === 'specificContent') {
      queryString = queryString + 'unit_specific_content_id=' + contentValueId
    }

    if (type === 'commonContent') {
      queryString = queryString + 'content_value_id=' + contentValueId
    }

    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/assessments/' + assessment_id + 
    '/content_categories/' + category_id + '/assessment_contents/removeAssessmentContent' + queryString );
  }

  /* Get all assessment contents */
  getAssessmentcontents(course_id, unit_id, category_id, assessment_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/assessments/' + assessment_id +
      '/content_categories/' + category_id + '/assessment_contents/');
  }

  /* Get all units for unit browser */
  getAllUnits(course_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/getUnits');
  }
}

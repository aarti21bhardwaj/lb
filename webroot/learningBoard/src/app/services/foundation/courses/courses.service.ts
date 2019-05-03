import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../../configuration/custom-https.service';
import { AppSettings } from '../../../app-settings';

@Injectable()
export class CoursesService {
  contentCategoryData: any;
  contentCategories: any = []; /* When API of get course is called from Courses component, this variable will update.  */
  // contentValue: any;
  refreshSummary = false;
  archivedUnits : any;

  constructor(
    public http: CustomHttpsService
  ) {
    console.log('getting courses here');
  }

  getCourses() {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses');
  }

  getCourse(id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + id);
  }

  getUserDetail() {
    return this.http.get(AppSettings.API_ENDPOINT + 'users/me');
  }

  getCommonUnitContent(type) {
    console.log('type from component' + type);
    this.contentCategories.forEach(element => {
      if (element.type === type) {
        console.log('In if when both types are equal');
        this.contentCategoryData = element;
        // this.contentValue = element.content_values;
      }
    });
    console.log('this is type from API' + this.contentCategoryData);
    return this.contentCategoryData;
  }

  getPublishHistory(objectId,objectType) {
    return this.http.get(AppSettings.API_ENDPOINT + 'sectionEvents/getPublishedContent?object_id='+objectId+'&&object_name='+objectType);
  }

  getSectionStudents(sectionId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'sectionStudents?section_id='+sectionId);
  }

  copyOldUnit(courseId, unitId, name) {
    let data: any;
    data = {
      name: name,
      unit_id: unitId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/copyOfUnit/'
      , data);
  }

  deleteUnit(courseId, unitId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/deleteUnit/' + unitId);
  }

  refreshUnitSummary() {
    this.refreshSummary = true;
  }

  archiveUnits(courseId, termId){
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + courseId + '/units/archivedUnits/' + termId);
  }

  getAllCourses() {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/getAllCourses');
  }
}

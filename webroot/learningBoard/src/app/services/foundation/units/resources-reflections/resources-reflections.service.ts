import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../../../configuration/custom-https.service';
import { AppSettings } from '../../../../app-settings';
@Injectable()
export class ResourcesReflectionsService {

  constructor(
    public http: CustomHttpsService
  ) { }

  // Adds a resource to the unit
  addUnitResource(course_id, unit_id, data) {
    console.log(data);
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_resources', data);
  }

  editUnitResource(course_id, unit_id, id, data) {
    console.log(data);
    return this.http.put(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_resources/' + id, data);
  }

  // Uploads a file and returns its file path and name
  postFile(course_id, unit_id, fileToUpload: File) {
    const formData: FormData = new FormData();
    formData.append('fileKey', fileToUpload, fileToUpload.name);
    return this.http
      .post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_resources/uploadResources',
      formData);
  }

  getUnitResources(course_id, unit_id, query_string: string = '') {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_resources' + query_string);
  }

  deleteResource(course_id, unit_id, id) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_resources/' + id);
  }

  addUnitReflection(course_id, unit_id, data) {
    console.log(data);
    return this.http.post(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_reflections', data);
  }

  getUnitReflections(course_id, unit_id, query_string: string = '') {
    return this.http.get(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_reflections' + query_string);
  }

  deleteReflection(course_id, unit_id, id) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_reflections/' + id);
  }

  editReflection(course_id, unit_id, id, data){
    return this.http.put(AppSettings.API_ENDPOINT + 'courses/' + course_id + '/units/' + unit_id + '/unit_reflections/' + id, data);
  }
}

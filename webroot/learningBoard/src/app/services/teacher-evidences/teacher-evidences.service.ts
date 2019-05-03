import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../configuration/custom-https.service';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AppSettings } from '../../app-settings';

@Injectable()
export class TeacherEvidencesService {

  constructor(
    public http: CustomHttpsService
  ) { }

  listEvidences() {
    return this.http.get(AppSettings.API_ENDPOINT + 'teacherEvidences/index');
  }

  getEvidence(id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teacherEvidences/view/' + id);
  }

  deleteEvidence(id) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'teacherEvidences/delete/' + id);
  }

  saveEvidence(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'teacherEvidences/add', data);
  }

  editEvidence(id, data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'teacherEvidences/edit/' + id, data);
  }

  postFile(fileToUpload: File) {
    const formData: FormData = new FormData();
    formData.append('fileKey', fileToUpload, fileToUpload.name);
    return this.http
      .post(AppSettings.API_ENDPOINT + 'teacherEvidences/uploadResources',
        formData);
  }

  getImpacts(data) {
    return this.http.get(AppSettings.API_ENDPOINT + 'impacts/getFrameworks');
  }

  addUnitImpact(evidenceId, impactId) {
    const body = {
      'impact_id': impactId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'teacherEvidences/addImpact/' + evidenceId, body);
  }

  deleteUnitImpact(evidenceId, impactId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'teacherEvidences/deleteImpact/' + impactId + '/' + evidenceId);
  }

  getSelectedUnitImpacts(evidenceId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teacherEvidences/indexImpacts/' + evidenceId);
  }

  /* Self assessment */
  getSelfAssessmentScores(evidenceId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teacherEvidences/getEvidenceImpactScores/' + evidenceId);
  }

  addSelfAssessmentScores(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'teacherEvidences/saveEvidenceImpactScores', data);
  }

}

import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../configuration/custom-https.service';
import { AppSettings } from '../../app-settings';

@Injectable()
export class EvidencesService {

  constructor(
    public http: CustomHttpsService
  ) {

  }

  listEvidences(studentId = null) {
    if (studentId) {
      return this.http.get(AppSettings.API_ENDPOINT + 'evidences/index/' + studentId);
    }else {
      return this.http.get(AppSettings.API_ENDPOINT + 'evidences/index');
    }
  }

  getEvidence(id, studentId = null) {
    if(studentId != null){
      return this.http.get(AppSettings.API_ENDPOINT + 'evidences/view/' + id+'/'+studentId);
    }else{
      return this.http.get(AppSettings.API_ENDPOINT + 'evidences/view/' + id);
    }
  }

  saveEvidence(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evidences/add', data);
  }

  editEvidence(id, data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evidences/edit/' + id, data);
  }

  deleteEvidence(id) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'evidences/delete/' + id);
  }

  postFile(fileToUpload: File) {
    const formData: FormData = new FormData();
    formData.append('fileKey', fileToUpload, fileToUpload.name);
    return this.http
      .post(AppSettings.API_ENDPOINT + 'evidences/uploadResources',
        formData);
  }


  getImpacts(body) {
    return this.http.post(AppSettings.API_ENDPOINT + 'impacts/viewImpacts', body);
  }

  addUnitImpact(evidenceId, impactId) {
    const body = {
      'impact_id': impactId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'evidences/addImpact/' + evidenceId, body);
  }

  deleteUnitImpact(evidenceId, impactId) {
    return this.http.delete(AppSettings.API_ENDPOINT + 'evidences/deleteImpact/' + impactId + '/' + evidenceId);
  }

  getSelectedUnitImpacts(evidenceId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evidences/indexImpacts/' + evidenceId);
  }

  /* Self assessment */
  getSelfAssessmentScores(evidenceId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evidences/getEvidenceImpactScores/' + evidenceId);
  }

  addSelfAssessmentScores(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evidences/saveEvidenceImpactScores', data);
  }

  /* For digital strategy  */
  getEvidenceContent(evidenceId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evidenceContents/getEvidenceContents/' + evidenceId);
  }

  addEvidenceContent(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evidenceContents/add', data);
  }

  deleteEvidenceContent(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evidenceContents/removeEvidenceContent', data);
  }

  getDigitalStrategies(contentCategoryKey) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evidences/getDigitalStrategies/' + contentCategoryKey);
  }

}

import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../configuration/custom-https.service';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AppSettings } from '../../app-settings';

@Injectable()
export class FeedbackService {

  private studentSource = new BehaviorSubject<Object>({});
  currentStudents = this.studentSource.asObservable();

  constructor(
    public http: CustomHttpsService
  ) {

  }

  updateStudents(students) {
    this.studentSource.next(students);
  }

  markComplete(studentId, pending = null) {
    let studs: any;
    this.currentStudents.subscribe(students => studs = students);
    for (const key in studs) {
      if (studs[key].student_id == studentId) {
        if (pending) {
          studs[key].is_completed = false;
        }else {
          studs[key].is_completed = true;
        }
      break;
      }
    }
    this.updateStudents(studs);
  }

  getEvaluation(id, studentId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evaluations/' + id + '?student_id=' + studentId);
  }

  getEvaluationStudents(id) {
    // return this.http.get(this.url + 'evaluations/getStudents/' + id);
    return this.http.get(AppSettings.API_ENDPOINT + 'evaluations/' + id);
  }

  getEvaluations(sectionId){
    return this.http.get(AppSettings.API_ENDPOINT + 'evaluations/getEvaluations/' + sectionId);
  }

  saveEvaluation(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'evaluations/evaluationSave', data);
  }

  updateArchievedStatus(evaluation_id) {
    return this.http.get(AppSettings.API_ENDPOINT + 'evaluations/updateArchievedStatus/' + evaluation_id);
  }

}

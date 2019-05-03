import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../../configuration/custom-https.service';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AppSettings } from '../../../app-settings';

@Injectable()
export class TeacherService {

  private studentSource = new BehaviorSubject<Object>({});
  currentStudents = this.studentSource.asObservable();

  public sectionName: any;
  public teacherName: any;

  constructor(
    public http: CustomHttpsService
  ) { }

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
        } else {
          studs[key].is_completed = true;
        }
        break;
      }
    }
    this.updateStudents(studs);
  }

  getTeachers() {
    return this.http.get(AppSettings.API_ENDPOINT + 'teachers');
  }

  getReportStudents(sectionId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teachers/getSectionStudents/' + sectionId);
  }

  getStudentReportSettings(sectionId, studentId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teachers/studentReportSettings/' + sectionId + '/' + studentId);
  }

  saveStudentReport(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'teachers/saveReportFeedback', data);
  }

  /* Add student and teacher reflections */
  saveReportReflections(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'report_template_student_comments', data);
  }

  /* Get student and teacher reflections */
  getReportReflections(studentId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'report_template_student_comments/getStudentRecord/' + studentId);
  }

  /* Edit student and teacher reflections */
  editReportReflections(id, data) {
    return this.http.put(AppSettings.API_ENDPOINT + 'report_template_student_comments/' + id, data);
  }

  /* Add student special service */
  saveReportSpecialServices(data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'report_template_student_services', data);
  }

  /* DeleteSpecialServices */
  deleteSpecialService(studentId, data) {
    return this.http.post(AppSettings.API_ENDPOINT + 'report_template_student_services/removeStudentService/' + studentId, data);
  }

  /* Get special services */
  getReportSpecialServices(studentId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'report_template_student_services/getStudentService/' + studentId);
  }

  /* Course links on report template */
  getCourseLinks(studentId, sectionId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'teachers/getCourseLinks/' + sectionId + '/' + studentId);
  }
}

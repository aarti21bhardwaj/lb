import { Injectable } from '@angular/core';
import { CustomHttpsService } from '../configuration/custom-https.service';
import { AppSettings } from '../../app-settings';

@Injectable()
export class AnalyticsService {
  studentList: any = [];
  selectedTermId: any = [];
  selectedCampusId: any = [];
  selectedDivisionId: any = [];
  selectedCourseId: any = [];
  selectedSectionId: any = [];
  termData: any = [];

  constructor(public http: CustomHttpsService) { }

  getCourseStrandsDistribution(CourseId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'analytics/courseMap/' + CourseId);
  }

  getStandardUsage(CourseId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'analytics/standardCalculation/' + CourseId);
  }

  getStandardsByStrand(courseId, strandId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'analytics/getData/' + courseId + '/' + strandId);
  }

  /* In selectStudent (shared) , used to get sectionId by selecting course, section, term and division */
  getSectionId() {
    return this.http.get(AppSettings.API_ENDPOINT + 'analytics/listData');
  }

  /* GetsparklineChartData */
  getChartData(sectionId, studentId) {
    return this.http.get(AppSettings.API_ENDPOINT + 'analytics/performanceEvaluate/' + sectionId + '/' + studentId);
  }

  /* Get circumplex data */
  getCircumplexData(termId, studentId, type) {
    const data = {
      term_id: termId,
      type: type
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'analytics/studentEvaluation/' + studentId, data);
  }

  /* Get circumplex data for single course */
  getCourseCircumplex(termId, studentId, courseId) {
    const data = {
      term_id: termId,
      course_id: courseId
    }
    return this.http.post(AppSettings.API_ENDPOINT + 'analytics/studentStrandScore/' + studentId, data);
  }
}

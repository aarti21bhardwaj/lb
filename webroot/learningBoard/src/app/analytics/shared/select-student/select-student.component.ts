import { Component, OnInit, Input } from '@angular/core';
import { AnalyticsService } from '../../../services/analytics/analytics.service';
import { CoursesService } from './../../../services/foundation/courses/courses.service';

@Component({
  selector: 'app-select-student',
  templateUrl: './select-student.component.html',
  styleUrls: ['./select-student.component.scss']
})
export class SelectStudentComponent implements OnInit {
  selectedCourseId: any = null;
  selectedTermId: any = null;
  selectedDivisionId: any = null;
  selectedSectionId: any = null;
  selectedCampusId: any = null;
  sectionId: any;
  campuses: any = [];
  divisions: any = [];
  terms: any = [];
  courses: any = [];
  getCourses: any;
  sections: any = [];
  campusId: any;
  divisionId: any;
  termId: any;
  courseId: any;
  allSections: any;
  students: any;
  noSections: boolean;
  
  constructor(public analyticsService: AnalyticsService, private courseService: CoursesService) {
   }

  ngOnInit() {
    this.selectedCampusId = this.analyticsService.selectedCampusId;
    this.selectedDivisionId = this.analyticsService.selectedDivisionId;
    this.selectedTermId = this.analyticsService.selectedTermId;
    this.selectedCourseId = this.analyticsService.selectedCourseId;
    this.selectedSectionId = this.analyticsService.selectedSectionId;

    console.log( this.selectedCampusId);
    console.log('selected selectedDivisionId' + this.selectedDivisionId.id);
    console.log('selected selectedTermId' + this.selectedTermId.id);
    console.log('selected selectedCourseId' + this.selectedCourseId.id);
    console.log('selected selectedSectionId' + this.selectedSectionId.id);


    this.analyticsService.getSectionId().subscribe((res) => {
      const data: any = res;
      console.log('get student list Data' + data);
      if (data != null) {
        // this.analyticsService.studentList = [];
        this.getCourses = data.courseData;
        this.campuses = data.campuses;
        this.allSections = data.sections;
        console.log('All Sections');
        console.log(this.allSections);

        if(this.selectedCampusId && this.selectedCampusId.length > 0) {
          console.log('If selected campus');
          this.campuses.forEach(element => {
            if (element.id === this.selectedCampusId[0].id) {
              this.divisions = element.divisions;
            }
          });
          console.log(this.divisions);
        }
        if (this.selectedDivisionId && this.selectedDivisionId.length > 0) {
          console.log('If selected division');
          this.divisions.forEach(element => {
            if (element.id === this.selectedDivisionId[0].id) {
              this.terms = element.terms;
             
            }
          });
          console.log(this.terms);
        }
        if (this.selectedTermId && this.selectedTermId.length > 0) {
          console.log('If terms');
          this.courses = this.getCourses[this.selectedCampusId[0].id][this.selectedDivisionId[0].id][0][this.selectedTermId[0].id][0];
          console.log(this.courses);
        }
        if (this.selectedCourseId && this.selectedCourseId.length > 0) {
          this.sections = this.allSections[this.selectedTermId[0].id][this.selectedCourseId[0].id];
        }

      }
    }, (error) => console.warn('Error in getting standard usage data' + error)
    );
  }

  public selected(value: any, field: any): void {
    console.log('Selected value is: ', value);
  }
  public removed(value: any, field: any): void {
    console.log('removed value is' + value);
  }
  public refreshValue(value: any, field: any): void {
    if (field === 'campuses') {
      this.campusId = value.id;
      this.analyticsService.selectedCampusId = []
      this.analyticsService.selectedCampusId.push(value);
      console.log('refresh campus id' + this.campusId);
      if (this.campusId != null) {
        this.divisions = [];
        this.courses = [];
        this.terms = [];
        this.sections = [];
        this.divisionId = null;
        
        // this.analyticsService.selectedTermId = null;
        this.analyticsService.studentList = [];
        this.campuses.forEach(element => {
          if (element.id === this.campusId) {
            this.divisions = element.divisions;
          }
        });
      }
    }
    if (field === 'divisions') {
      this.divisionId = value.id;
      if(!this.campusId) {
        this.campusId = this.selectedCampusId[0].id;
      }
    
      this.analyticsService.selectedDivisionId = [];
      this.analyticsService.selectedDivisionId.push(value);
      if (this.divisionId != null) {
        console.log('refresh division id' + this.divisionId);
        // this.courses = [];
        this.terms = [];
        this.courses = [];
        this.sections = [];
        this.termId = null;
        
        this.analyticsService.studentList = [];
        this.divisions.forEach(element => {
          if (element.id === this.divisionId) {
            this.terms = element.terms;
            // this.courses = this.getCourses;
            this.analyticsService.termData = this.terms;
          }
        });
      }
      console.log(this.divisions);
    }
    if (field === 'terms') {
      this.termId =  value.id;
      if(!this.campusId) {
        this.campusId = this.selectedCampusId[0].id;
      }
      if(!this.divisionId) {
        this.divisionId = this.selectedDivisionId[0].id
      }
  
      this.analyticsService.selectedTermId = [];
      this.analyticsService.selectedTermId.push(value);
      console.log('refreshed term id' + this.termId);
      if (this.termId != null) {
        console.log('campus id' + this.campusId);
        console.log('division id' + this.divisionId);
        console.log(this.getCourses);
        this.courses = this.getCourses[this.campusId][this.divisionId][0][this.termId][0];
        console.log(this.courses);
        this.sections = [];
        this.courseId = null;
        
        this.analyticsService.studentList = [];
        // this.analyticsService.selectedTermId = this.termId;
        // this.allSections[this.termId][this.co]
      }
    }
    if (field === 'courses') {
      this.courseId = value.id
      this.sections = [];
      this.sectionId = null;
      this.selectedSectionId = [];
      console.log('refresh course id' + this.courseId);
      if (!this.campusId) {
        this.campusId = this.selectedCampusId[0].id;
      }
      if (!this.divisionId) {
        this.divisionId = this.selectedDivisionId[0].id
      }
      if (!this.termId) {
        this.termId = this.selectedTermId[0].id
      }
     
      
      this.analyticsService.selectedCourseId = [];
      this.analyticsService.selectedCourseId.push(value);
      if (this.courseId != null && typeof this.termId != 'undefined') {
        
        this.analyticsService.studentList = [];
        // this.analyticsService.selectedTermId = this.termId;

        if (this.allSections[this.termId] && this.allSections[this.termId][this.courseId]){
          this.sections = this.allSections[this.termId][this.courseId];
          this.noSections = false;
        }else{
          this.noSections = true;
        }
      }
      // this.courses = value;
    }
    if (field === 'sections') {
      this.sectionId = value.id
      console.log('refresh section id' + this.sectionId);
      // this.selectedSectionId = [];
      console.log('refresh course id' + this.courseId);
      if (!this.campusId) {
        this.campusId = this.selectedCampusId[0].id;
      }
      if (!this.divisionId) {
        this.divisionId = this.selectedDivisionId[0].id
      }
      if (!this.termId) {
        this.termId = this.selectedTermId[0].id
      }
      if (!this.courseId) {
        this.courseId = this.selectedCourseId[0].id;
      }
      
      this.analyticsService.selectedSectionId = [];
      this.analyticsService.selectedSectionId.push(value);
      this.analyticsService.studentList = [];
      if (this.sectionId != null && typeof this.sectionId != 'undefined') {
        this.courseService.getSectionStudents(this.sectionId)
          .subscribe((response) => {
            this.students = response;
            this.students = this.students.data;
            if (this.students != null && typeof this.students != 'undefined') {
              this.analyticsService.studentList = this.students;
            }
          }, (error) => {
            console.warn(error);
          });
        // this.sections.forEach(element => {
        //   if (this.sectionId === element.id) {
        //     this.analyticsService.studentList = element.students;
        //   }
        // });
      }
    }
  }

}

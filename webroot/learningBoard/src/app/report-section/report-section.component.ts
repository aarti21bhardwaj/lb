import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import { TeacherService } from '../services/foundation/teachers/teacher.service';

@Component({
  selector: 'app-report-section',
  templateUrl: './report-section.component.html',
  styleUrls: ['./report-section.component.scss']
})
export class ReportSectionComponent implements OnInit {

  filter_name: any;
  studentId: any
  studentSelected: boolean = false;
  students: any;
  sectionId: any;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private teachers: TeacherService,
  ) { }

  ngOnInit() {

    this.checkBanner();

    this.router.events.filter(event => event instanceof NavigationEnd)
    .subscribe(event => {
      this.checkBanner();
    });

    this.sectionId = this.route.snapshot.paramMap.get('section_id');
    this.getStudents();

  }

  checkBanner() {

    if (this.route.snapshot.data && this.route.snapshot.data['componentName'] == 'report-section') {

      if (this.route.snapshot.firstChild && this.route.snapshot.firstChild.data['componentName'] == 'reports') {
        this.studentId = this.route.snapshot.firstChild.paramMap.get('student_id');
        this.studentSelected = true;
      } else {
        this.studentSelected = false;
      }
    }
  }

  getStudents(){
    this.teachers.getReportStudents(this.sectionId)
    .subscribe( (res) => {
      this.students = res;
      this.teachers.sectionName = this.students.sectionsStudents.name;
      this.teachers.teacherName = this.students.sectionsStudents.teacher.full_name;
      this.students = this.students.sectionsStudents.section_students;
      this.teachers.updateStudents(this.students);
    });
  }

}

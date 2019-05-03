import { Component, OnInit } from '@angular/core';
import { UsersService } from '../services/users/users.service';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras, NavigationEnd } from '@angular/router';

@Component({
  selector: 'app-parent-dashboard',
  templateUrl: './parent-dashboard.component.html',
  styleUrls: ['./parent-dashboard.component.scss']
})
export class ParentDashboardComponent implements OnInit {

  selectedSection: any;
  guardians: any;
  isStudentSelected: boolean;
  filter_name: any = '';

  constructor(private userService: UsersService, private router: Router,) {
    this.isStudentSelected = false;
    this.userService.getUserDetails().subscribe(res => {
      let response;
      response = res;
      response = response.data;
      this.guardians = response.guardians;
    });
   }

  ngOnInit() {
  }

  sectionSelected(section) {
    console.log('student selected');
    console.log(section);
    this.selectedSection = section;
    this.isStudentSelected = true;
  }

  gotToRoute(type) {
    if(type == 'circumplex') {
      this.router.navigateByUrl('/student-circumplex/' + this.selectedSection.student_id + '/' + this.selectedSection.section_id + '/' + this.selectedSection.section.term_id + '/' + this.selectedSection.section.course_id);
    }
    if(type == 'report') {
      this.router.navigateByUrl('/student-report/' + this.selectedSection.student_id + '/' + this.selectedSection.section_id + '/' + this.selectedSection.section.term_id + '/' + this.selectedSection.section.course_id);
    }
    if(type == 'evidence') {
      this.router.navigateByUrl('/student-evidence/' + this.selectedSection.student_id + '/' + this.selectedSection.section_id + '/' + this.selectedSection.section.term_id + '/' + this.selectedSection.section.course_id);
    }
  }

}

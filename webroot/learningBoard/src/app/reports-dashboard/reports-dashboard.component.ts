import { Component, OnInit } from '@angular/core';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras } from '@angular/router';
import { UsersService } from '../services/users/users.service';

@Component({
  selector: 'app-reports-dashboard',
  templateUrl: './reports-dashboard.component.html',
  styleUrls: ['./reports-dashboard.component.scss']
})
export class ReportsDashboardComponent implements OnInit {

  courses: any;
  filter_name: any;
  selected_section_id: any;

  constructor(
    private router: Router,
    private userService: UsersService,
  ) {
    this.userService.getUserDetails().subscribe(res => {
      this.courses = res;
      this.courses = this.courses.data.courseData
    });
   }

  ngOnInit() {
  }

  sectionSelected(sectionId) {
    this.selected_section_id = sectionId;
    this.router.navigateByUrl('/reports/section/' + sectionId);
  }

}

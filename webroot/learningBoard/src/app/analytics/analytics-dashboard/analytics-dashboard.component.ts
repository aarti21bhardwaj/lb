import { Component, OnInit } from '@angular/core';
import { UsersService } from '../../services/users/users.service';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationStart, Event as NavigationEvent } from '@angular/router';

@Component({
  selector: 'app-analytics-dashboard',
  templateUrl: './analytics-dashboard.component.html',
  styleUrls: ['./analytics-dashboard.component.css']
})
export class AnalyticsDashboardComponent implements OnInit {
  public courses: any;
  showBanner = true;
  constructor(private userService: UsersService, private route: ActivatedRoute) {
    console.log('In analytics dashboard');
  }

  ngOnInit() {
    if (this.route.snapshot.paramMap.get('course_id')) {
      this.showBanner = false;
    }
    this.userService.getUserDetails().subscribe(res => {
      let courses: any;
      courses = res;
      if (courses.data != null && typeof courses.data !== 'undefined') {
        this.courses = courses.data.courseData
      }
    });
  }
  changeBanner() {
    this.showBanner = false;
  }

}

import { Component, OnInit } from '@angular/core';
import { CoursesService } from '../services/foundation/courses/courses.service';
import { UsersService } from '../services/users/users.service';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras, NavigationEnd } from '@angular/router';

@Component({
  selector: 'app-unit-browser',
  templateUrl: './unit-browser.component.html',
  styleUrls: ['./unit-browser.component.scss']
})
export class UnitBrowserComponent implements OnInit {
  public courses: any;
  public newUnit;
  public newCourse;
  isCourseSelected: boolean;
  filter_name: any = '';

  constructor(private courseService: CoursesService,
    private router: Router,
    private userService: UsersService,
    private activatedRoute: ActivatedRoute,) { }

  ngOnInit() {
    this.getCourses();
    this.toggleBanner();

    this.router.events.filter(event => event instanceof NavigationEnd)
      .subscribe(event => {
        this.toggleBanner();
      });
  }

  getCourses() {
    this.courseService.getAllCourses().subscribe(res => {
      this.courses = res;
      this.courses = this.courses.data
    });
  }

  toggleBanner() {

    if (this.activatedRoute &&
      this.activatedRoute.firstChild &&
      this.activatedRoute.firstChild.snapshot.params['course_id']) {
      this.isCourseSelected = true;
    } else {
      this.isCourseSelected = false;
    }
  }

  courseSelected() {
    this.isCourseSelected = true;
  }

}

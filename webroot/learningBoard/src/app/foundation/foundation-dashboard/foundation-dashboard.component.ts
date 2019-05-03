import { Component, OnInit, Directive, AfterViewInit, ElementRef } from '@angular/core';
import { CoursesService } from '../../services/foundation/courses/courses.service';
import { UsersService } from '../../services/users/users.service';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras, NavigationEnd } from '@angular/router';
import {Course} from '../../course';
// import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';


@Component({
  selector: 'app-foundation-dashboard',
  templateUrl: './foundation-dashboard.component.html',
  styleUrls: ['./foundation-dashboard.component.css']
})


export class FoundationDashboardComponent implements OnInit {

  public courses: any;
  public newUnit;
  public newCourse;
  isCourseSelected: boolean;
  filter_name: any = '';

  constructor(
    private courseService: CoursesService,
    private router: Router,
    private userService: UsersService,
    private activatedRoute: ActivatedRoute,
  ) {
    this.userService.getUserDetails().subscribe(res => {
      this.courses = res;
      this.courses = this.courses.data.courseData
    });
  }

  ngOnInit() {

    this.toggleBanner();

    this.router.events.filter(event => event instanceof NavigationEnd)
    .subscribe(event => {
      this.toggleBanner();
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
    // router.navigate(['team', 33, 'user', 11], { relativeTo: route });
    // this.router.navigateByUrl('/foundation/courses/' + course.id).then( () => { this.isCourseSelected = true; });
  }

}

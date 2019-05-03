import { Component, OnInit } from '@angular/core';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationExtras, NavigationEnd } from '@angular/router';
import { UsersService } from '../../services/users/users.service';
@Component({
  selector: 'app-teaching-hub-dashboard',
  templateUrl: './teaching-hub-dashboard.component.html',
  styleUrls: ['./teaching-hub-dashboard.component.css']
})
export class TeachingHubDashboardComponent implements OnInit {

  courses: any;
  filter_name: any;
  isCourseSelected: boolean = false;

  constructor(
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

  courseSelected(course) {

    // router.navigate(['team', 33, 'user', 11], { relativeTo: route });
    this.router.navigateByUrl('/foundation/courses/' + course.id);
  }

  toggleBanner() {

    if (this.activatedRoute &&
      this.activatedRoute.snapshot.firstChild &&
      this.activatedRoute.snapshot.firstChild.params['section_id']) {
      this.isCourseSelected = true;
    } else {
      this.isCourseSelected = false;
    }
  }

}

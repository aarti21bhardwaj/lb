import { Component, OnInit, DoCheck } from '@angular/core';
import { Chart } from 'angular-highcharts';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationStart, Event as NavigationEvent } from '@angular/router';


@Component({
  selector: 'app-course-map',
  templateUrl: './course-map.component.html',
  styleUrls: ['./course-map.component.scss']
})
export class CourseMapComponent implements OnInit {
  courseId: any;
  spinnerEnabled: boolean;
/* Bar chart */
 

  constructor(private route: ActivatedRoute) {
    console.log('In course map');
  }

  ngOnInit() {
  }

  ngDoCheck() {
    const cId = this.route.snapshot.paramMap.get('course_id');
    if (cId && this.courseId !== cId) {
      this.courseId = cId;
      this.spinnerEnabled = true;
      setTimeout(() => { this.spinnerEnabled = false }, 100);
      // show spinner

      // hide spinner
      console.log('In ngdo check courseId' + this.courseId);
    }
  }

}

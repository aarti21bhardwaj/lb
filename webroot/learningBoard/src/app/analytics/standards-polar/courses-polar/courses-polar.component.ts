import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-courses-polar',
  templateUrl: './courses-polar.component.html',
  styleUrls: ['./courses-polar.component.scss']
})
export class CoursesPolarComponent implements OnInit {
  termId: any;
  studentId: any;
  sectionId: any;
  courseId: any;

  constructor(private acivatedRoute: ActivatedRoute) {
    console.log('In courses Polar');
   }

  ngOnInit() {
    console.log('In courses of polar');
    this.acivatedRoute.params.subscribe(res => {
      console.log('route response' + res);
      this.sectionId = res.section_id;
      this.studentId = res.student_id;
      this.termId = res.term_id;
      this.courseId = res.course_id;
    });
  }

}

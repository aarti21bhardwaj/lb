import { OnInit, Component, AfterViewInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PypComponent } from '../../../pyp/pyp.component';

@Component({
  selector: 'app-we-learn',
  templateUrl: './we-learn.component.html',
  styleUrls: ['./we-learn.component.scss']
})
export class WeLearnComponent implements OnInit {
  unitId: any;
  courseId: any;


  constructor(private acivatedRoute: ActivatedRoute, private parent: PypComponent) {
    this.acivatedRoute.parent.params.subscribe(res => {
      console.log(res);
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
      console.log('This is the unit id in assessments ' + this.unitId);
      console.log('This is the course id in assessments ' + this.courseId);
    });
    parent.isMetaUnitActive = false;
  }

  ngOnInit() {
  }
}

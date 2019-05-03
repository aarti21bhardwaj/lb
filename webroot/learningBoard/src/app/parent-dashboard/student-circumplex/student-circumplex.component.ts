import { Component, OnInit, DoCheck } from '@angular/core';
import { AnalyticsService } from '../../services/analytics/analytics.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-student-circumplex',
  templateUrl: './student-circumplex.component.html',
  styleUrls: ['./student-circumplex.component.scss']
})
export class StudentCircumplexComponent implements OnInit {
  students: any = [];
  sectionId: any;
  studentId: any;
  termId: any = null;
  spinnerEnabled = false;
  selectedTermId: any;
  terms: any = [];
  activeTerm: any;
  showBanner = true;

  constructor(public analyticsService: AnalyticsService, private acivatedRoute: ActivatedRoute) { }

  ngOnInit() {
    this.acivatedRoute.params.subscribe(res => {
      this.sectionId = res.section_id;
      this.studentId = res.student_id;
      this.termId = res.term_id;
      this.spinnerEnabled = true;
      setTimeout(() => { this.spinnerEnabled = false }, 1000);
      
    });
  }

}

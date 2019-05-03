import { Component, OnInit, DoCheck } from '@angular/core';
import { AnalyticsService } from '../../services/analytics/analytics.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-standards-polar',
  templateUrl: './standards-polar.component.html',
  styleUrls: ['./standards-polar.component.scss']
})
export class StandardsPolarComponent implements OnInit {
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
    // this.analyticsService.studentList = [];
  }

  ngDoCheck() {
    this.acivatedRoute.params.subscribe(res => {
      console.log('route response' + res);
      const sectId = res.section_id;
      const stId = res.student_id;
      

      if (stId != null && typeof stId !== 'undefined') {
        this.showBanner = false;
      }
      if (stId && this.studentId !== stId) {
        console.log('In if routes matched' + sectId);
        this.sectionId = sectId;
        this.studentId = stId;
        this.termId = res.term_id;
        this.spinnerEnabled = true;
        setTimeout(() => { this.spinnerEnabled = false }, 1000);
      }
    });
    this.students = this.analyticsService.studentList;
    this.terms = this.analyticsService.termData;
  }


  public refreshValue(value: any): void {
    this.selectedTermId = value.id;
    this.termId = value.id
    // this.analyticsService.selectedTermId = this.selectedTermId;
    this.spinnerEnabled = true;
    setTimeout(() => { this.spinnerEnabled = false }, 1000);
    console.log('selected term Id' + this.selectedTermId);
  }
}

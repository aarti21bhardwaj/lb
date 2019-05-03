import { Component, OnInit, OnChanges, DoCheck } from '@angular/core';
import { Units } from '../../../../../units';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsDatepickerModule } from 'ngx-bootstrap';
import { BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { ActivatedRoute, UrlSegment } from '@angular/router';
import { CoursesService } from '../../../../../services/foundation/courses/courses.service';
import { UnitsService } from '../../../../../services/foundation/units/units.service';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-tubd',
  templateUrl: './tubd.component.html',
  styleUrls: ['./tubd.component.scss']
})
export class TubdComponent implements OnInit {
  essentialQuestionsId: any;
  enduringUnderstandingsId: any;
  hideSummary = false;
  summaryExpanded:boolean = false;
  summaryToggleButtonText:any = "Expand All";
  public spinnerEnabled: boolean = false;
  public units: Units[];
  public newUnit;
  public bsRangeValue;
  public unit;
  public unitId;
  public courseId;
  public stylesheet;
  courseDetails: any;
  colorTheme = 'theme-blue';
  bsConfig: Partial<BsDatepickerConfig>;
  public isMetaUnitActive = false;
  unitData: any;
  isOverlayVisible: boolean = false;
  cssUrl: string = './tubd.component.scss';
  constructor(private acivatedRoute: ActivatedRoute, private courseService: CoursesService,
    private unitService: UnitsService, public sanitizer: DomSanitizer)  {
    this.bsConfig = Object.assign({}, { containerClass: this.colorTheme });
    this.acivatedRoute.params.subscribe(res => {
      console.log(res);
      console.log('in tubd component');
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
  }

  ngOnInit() {
    this.acivatedRoute.params.subscribe(res => {
      console.log('res');
      console.log(res);
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
      this.getUnitDetail();
    });
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        console.log(this.courseDetails);
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'enduring_undertandings') {
            console.log('In if when both types are equal');
            let understandings: any;
            understandings = element;
            this.enduringUnderstandingsId = understandings.id;
            console.log('these are the enduring understandings' + understandings);

          }
          if (element.type === 'essential_questions') {
            console.log('In if when both types are equal');
            let questions: any;
            questions = element;
            this.essentialQuestionsId = questions.id;
            console.log('these are the essential questions' + questions);
          }
        })
      }, (error) => {
        console.warn(error);
      });
      this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
        this.unitData = res;
        this.unitData = this.unitData.unit;
        
      });

  }

  ngDoCheck() {
    if (this.courseService.refreshSummary === true) {
      this.hideSummary = true;
      // this.spinnerEnabled = false;
      setTimeout(() => {
      this.hideSummary = false;
        this.courseService.refreshSummary = false;
       }, 100);
    }
  }

  getUnitDetail() {
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
      this.unitData = this.unitData.unit;
      // this.unitService.unitData = this.unitData;
      if (this.unitData.is_archived) {
        this.isOverlayVisible = true
        this.cssUrl = 'learningBoard/src/assets/custom.scss'
        console.log('unit archived')
      } else {
        console.log('unit not archived');
      }
    })  
  }

  toggleSummary() {
    if(this.summaryExpanded){
      this.summaryToggleButtonText = "Expand All";
      this.summaryExpanded = false;
      return;
    }

    this.summaryToggleButtonText = "Collapse All";
    this.summaryExpanded = true;

  }

}

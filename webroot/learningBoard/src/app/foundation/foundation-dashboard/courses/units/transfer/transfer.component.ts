import { Component, OnInit, DoCheck } from '@angular/core';
import { Units } from '../../../../../units';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsDatepickerModule } from 'ngx-bootstrap';
import { BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { ActivatedRoute } from '@angular/router';
import { CoursesService } from '../../../../../services/foundation/courses/courses.service';
import { UnitsService } from '../../../../../services/foundation/units/units.service';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-transfer',
  templateUrl: './transfer.component.html',
  styleUrls: ['./transfer.component.scss']
})
export class TransferComponent implements OnInit {
  commonSkills: any;
  commonQuestions: any;
  commonUnderstandings: any;
  skillId: any;
  questionId: any;
  understandingId: any;
  hideSummary = false;
  summaryExpanded:boolean = false;
  summaryToggleButtonText:any = "Expand All";
  public unitData:any=[];
  public spinnerEnabled: boolean = false;
  public units: Units[];
  public newUnit;
  public bsRangeValue;
  public unit;
  public unitId;
  public courseId;
  courseDetails: any;
  cssUrl: string = './transfer.component.scss';
  colorTheme = 'theme-blue';
  bsConfig: Partial<BsDatepickerConfig>;
  public isMetaUnitActive = false;
  constructor(private acivatedRoute: ActivatedRoute, private courseService: CoursesService, private unitService: UnitsService, public sanitizer: DomSanitizer) {
    this.bsConfig = Object.assign({}, { containerClass: this.colorTheme });
    this.acivatedRoute.params.subscribe(res => {
      console.log(res);
      console.log('in tubd component');
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
      this.unitData = this.unitData.unit;
      
    });
  }

  ngOnInit() {
    this.acivatedRoute.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
      this.getUnitDetail();
    });
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'common_understandings') {
            // let commonUnderstandings: any;
            this.commonUnderstandings = element;
            this.understandingId = this.commonUnderstandings.id;
            console.log('these are the common understandings' + this.commonUnderstandings);
          }
          if (element.type === 'common_questions') {
            // let commonQuestions: any;
            this.commonQuestions = element;
            this.questionId = this.commonQuestions.id;
            console.log('these are the common questions' + this.commonQuestions);
          }
          if (element.type === 'common_technology_skills') {
            // let commonSkills: any;
            this.commonSkills = element;
            this.skillId = this.commonSkills.id;
            console.log('common commonSkillId is' + this.commonSkills);
          }
        })
      }, (error) => {
        console.warn(error);
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
  toggleSummary() {
    if(this.summaryExpanded){
      this.summaryToggleButtonText = "Expand All";
      this.summaryExpanded = false;
      return;
    }

    this.summaryToggleButtonText = "Collapse All";
    this.summaryExpanded = true;

  }
  getUnitDetail() {
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
      this.unitData = this.unitData.unit;
      // this.unitService.unitData = this.unitData;
      if (this.unitData.is_archived) {
        this.cssUrl = 'learningBoard/src/assets/custom.scss'
        console.log('unit archived')
      } else {
        console.log('unit not archived');
      }
    })
  }

}

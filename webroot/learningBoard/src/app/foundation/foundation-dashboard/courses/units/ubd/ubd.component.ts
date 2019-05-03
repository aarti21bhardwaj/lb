import { Component, OnInit, DoCheck } from '@angular/core';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsDatepickerModule } from 'ngx-bootstrap';
import { BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { ActivatedRoute } from '@angular/router';
import { CoursesService } from '../../../../../services/foundation/courses/courses.service';
import { Units } from '../../../../../units';
import { LabelSettings } from '../../../../../label-settings'
import { UnitsService } from '../../../../../services/foundation/units/units.service';
import { DomSanitizer } from '@angular/platform-browser';

declare var tinymce: any;
@Component({
  selector: 'app-ubd',
  templateUrl: './ubd.component.html',
  styleUrls: ['./ubd.component.css']
})
export class UbdComponent implements OnInit {
  transferGoalId: any;
  skillId: any;
  knowledgeId: any;
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
  public impactLabel;
  public unitData:any=[];
  cssUrl: string = './ubd.component.css';
  courseDetails: any;
  colorTheme = 'theme-blue';
  bsConfig: Partial<BsDatepickerConfig>;
  public isMetaUnitActive = false;
  constructor(private acivatedRoute: ActivatedRoute, private courseService: CoursesService, 
    private unitService: UnitsService, public sanitizer: DomSanitizer) {
    this.bsConfig = Object.assign({}, { containerClass: this.colorTheme });
    this.acivatedRoute.params.subscribe(res => {
      console.log(res);
      console.log('in ubd component');
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
  }

  ngOnInit() {
    this.impactLabel = LabelSettings.UNIT_SUMMARY_IMPACTS;
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
          if (element.type === 'enduring_understandings') {
            let understandings: any;
            understandings = element;
            this.enduringUnderstandingsId = understandings.id;
          }
          if (element.type === 'essential_questions') {
            let questions: any;
            questions = element;
            this.essentialQuestionsId = questions.id;
          }
          if (element.type === 'knowledge') {
            let knowledge: any;
            knowledge = element;
            this.knowledgeId = knowledge.id;
          }
          if (element.type === 'skills') {
            let skills: any;
            skills = element;
            this.skillId = skills.id;
          }
          if (element.type === 'common_transfer_goals') {
            let goals: any
            goals = element;
            this.transferGoalId = goals.id;
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

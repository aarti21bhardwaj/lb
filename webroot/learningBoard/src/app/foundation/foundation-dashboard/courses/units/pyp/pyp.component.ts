import { Component, OnInit, DoCheck } from '@angular/core';
import { ModalModule } from 'ngx-bootstrap/modal';
import { ActivatedRoute } from '@angular/router';
import { CoursesService } from '../../../../../services/foundation/courses/courses.service';
import { UnitsService } from '../../../../../services/foundation/units/units.service';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-pyp',
  templateUrl: './pyp.component.html',
  styleUrls: ['./pyp.component.scss']
})
export class PypComponent implements OnInit {
  learnerProfiles: any;
  transdisciplinarySkills: any;
  keyConcepts: any;
  transdisciplinaryId: any;
  lineOfInqId: any;
  learnerProfileId: any;
  relatedConceptId: any;
  keyConceptId: any;
  centralIdeaId: any;
  public spinnerEnabled: boolean = false;
  summaryExpanded:boolean = false;
  summaryToggleButtonText:any = "Expand All";
  hideSummary = false;
  public newUnit;
  public bsRangeValue;
  public unit;
  public unitId;
  public courseId;
  public unitData:any=[];
  courseDetails: any;
  colorTheme = 'theme-blue';
  public isMetaUnitActive = false;
  cssUrl: string = './pyp.component.scss';
  constructor(private acivatedRoute: ActivatedRoute, private courseService: CoursesService, private unitService: UnitsService, public sanitizer: DomSanitizer) {
    this.acivatedRoute.params.subscribe(res => {
      console.log(res);
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
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
          if (element.type === 'central_idea') {
            let idea: any;
            idea = element;
            this.centralIdeaId = idea.id;
          }
          if (element.type === 'key_concepts') {
            // let keyConcepts: any;
            this.keyConcepts = element;
            this.keyConceptId = this.keyConcepts.id;
          }
          if (element.type === 'related_concepts') {
            console.log('In if when both types are equal related concept');
            let concept: any;
            concept = element;
            this.relatedConceptId = concept.id;
          }
          if (element.type === 'learner_profile') {
            console.log('In if when both types are equal learner profile');
            // let learnerProfiles: any;
            this.learnerProfiles = element;
            this.learnerProfileId = this.learnerProfiles.id;
          }
          if (element.type === 'lines_of_inquiry') {
            console.log('In if when both types are equal line of inquiry');
            let commonLineOfInq: any;
            commonLineOfInq = element;
            this.lineOfInqId = commonLineOfInq.id;
          }
          if (element.type === 'transdisciplinary_skills') {
            console.log('In if when both types are equal trans skills');
            // let transdisciplinarySkills: any;
            this.transdisciplinarySkills = element;
            this.transdisciplinaryId = this.transdisciplinarySkills.id;
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

import { Component, OnInit } from '@angular/core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ActivatedRoute } from '@angular/router';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { TransferComponent } from '../../../transfer/transfer.component';

@Component({
  selector: 'app-elements-of-success',
  templateUrl: './elements-of-success.component.html',
  styleUrls: ['./elements-of-success.component.scss']
})
export class ElementsOfSuccessComponent implements OnInit {
  spinnerEnabled: boolean = false;
  QuestionCatId: any;
  understandingCatId: any;
  commonSkillId: any;
  courseDetails;
  unitData: any;
  commonQuestions: any;
  commonUnderstandings: any = false;
  commonSkills: any;
  modalRef: BsModalRef;
  courseId: any;
  unitId: any;
  checkedUnderstandings: any;
  checkedQuestions: any;
  public unitUnderstandings: any= [];
  public unitUnderstanding: boolean;

  public unitQuestions: any = [];
  public unitQuestion: boolean;

  public unitSkills: any = [];
  public unitskill: boolean;

  public approaches = [];
  public approach: boolean;
  constructor(
    private acivatedRoute: ActivatedRoute,
    private courseService: CoursesService,
    private unitService: UnitsService,
    private parent: TransferComponent
  ) {
    this.unitUnderstanding = false;
    this.unitQuestion = false;
    this.approach = false;
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
  }

  ngOnInit() {
    /* Get unitData */
    this.spinnerEnabled = true;
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
    }, (error) => console.warn('Error in getting course' + error)
    );

    /* Get Common Contents */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        // this.courseService.contentCategories = this.courseDetails.content_categories;
        console.log('these are the course details')
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'common_understandings') {
            this.commonUnderstandings = element;
            this.understandingCatId = this.commonUnderstandings.id;
            // this.checkedUnderstandings = this.unitData.unit.unit_contents[this.understandingCatId].content_categories.content_values;
            console.log('these are the common understandings' + this.commonUnderstandings);
            console.log('common understanding id' + this.understandingCatId);
            this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.understandingCatId).subscribe((res) => {
              this.unitUnderstandings = res;
              this.unitUnderstandings = this.unitUnderstandings.response.data.unit_specific_contents;
              console.log('unt understanding data' + this.unitUnderstandings);
            }, (error) => {
              console.warn('Error in adding unit content' + error);
            });
          }
          if (element.type === 'common_questions') {
            this.commonQuestions = element;
            this.QuestionCatId = this.commonQuestions.id;
            // this.checkedQuestions = this.unitData.unit.unit_contents[this.QuestionCatId].content_categories.content_values;
            console.log('these are the common questions' + this.commonQuestions);
            console.log('common QuestionCatId id' + this.QuestionCatId);
            this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.QuestionCatId).subscribe((res) => {
              this.unitQuestions = res;
              this.unitQuestions = this.unitQuestions.response.data.unit_specific_contents;
              console.log('these are the unit specific questions' + this.unitQuestions);
            }, (error) => {
              console.warn('Error in adding unit content' + error);
            });
          }
          if (element.type === 'common_technology_skills') {
            this.commonSkills = element;
            this.commonSkillId = this.commonSkills.id;
            console.log('common commonSkillId is' + this.commonSkillId);
            this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.commonSkillId).subscribe((res) => {
              this.unitSkills = res;
              this.unitSkills = this.unitSkills.response.data.unit_specific_contents;
              console.log('these are the unit specific skills' + this.unitSkills);
            }, (error) => {
              console.warn('Error in adding unit specific skills' + error);
            });
          }
        });
        this.spinnerEnabled = false;
      }, (error) => {
        console.warn(error);
      });

  } /* Init ends here */

  addUnitApproach() {
    this.approach = false;
  }

  newApproach() {
    this.approach = true;
  }


}

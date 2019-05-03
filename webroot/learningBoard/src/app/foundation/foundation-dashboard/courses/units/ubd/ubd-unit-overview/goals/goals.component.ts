import { Component, OnInit, Input, OnChanges, ViewContainerRef } from '@angular/core';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { ActivatedRoute } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { UbdComponent } from '../../../ubd/ubd.component';
import { LabelSettings } from '../../../../../../../label-settings';

declare var tinymce: any;

@Component({
  selector: 'app-goals',
  templateUrl: './goals.component.html',
  styleUrls: ['./goals.component.scss']
})
export class GoalsComponent implements OnInit {
  courseId: any;
  unitId: any;
  courseDetails;

  /* Category Ids */
  enduringUnderstandingsId: any;
  essentialQuestionsId: any;
  transferGoalId: any;
  knowledgeId: any;
  skillId: any;

  /* Common Contents */
  goals: any;
  knowledge: any;
  understandings: any;
  questions: any;
  skills: any;
  understandingText: any;
  impactTreeTitle: any;

  /* specific contents */
  transferGoals: any = [];
  enduringUnderstandings: any = [];
  essentialQuestions: any = [];
  specificKnowledge: any = [];
  specificSkills: any = [];

  constructor(private courseService: CoursesService,
    private unitService: UnitsService,
    private acivatedRoute: ActivatedRoute, public toastr: ToastsManager,
    private parent: UbdComponent,
    vcr: ViewContainerRef) {
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
    this.impactTreeTitle = LabelSettings.IMPACTS;
  }

  ngOnInit() {
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'enduring_understandings') {
            console.log('In if when both types are equal');
            this.understandings = element;
            this.enduringUnderstandingsId = this.understandings.id;
            console.log('these are the enduring understandings' + this.understandings);

            /* find Checked common content */
            // this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.understandings.id).subscribe((res) => {
            //   this.enduringUnderstandings = res;
            //   this.enduringUnderstandings = this.enduringUnderstandings.response.data.unit_specific_contents;
            //   console.log('unt specific data 1' + this.enduringUnderstandings);
            // }, (error) => {
            //   console.warn('Error in adding unit content' + error);
            // });
          }
          if (element.type === 'common_transfer_goals') {
            console.log('In if when both types are equal');
            this.goals = element;
            this.transferGoalId = this.goals.id;
            console.log('these are the transfer goals' + this.goals);

            /* find Checked common content */
            // this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.transferGoalId).subscribe((res) => {
            //   this.transferGoals = res;
            //   this.transferGoals = this.transferGoals.response.data.unit_specific_contents;
            //   console.log('unt specific data 2' + this.transferGoals);
            // }, (error) => {
            //   console.warn('Error in adding unit content' + error);
            // });
          }
          if (element.type === 'knowledge') {
            console.log('In if when both types are equal');
            this.knowledge = element;
            this.knowledgeId = this.knowledge.id;
            console.log('these are the common knowledge' + this.knowledge);

            // /* find Checked common content */
            // this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.knowledgeId).subscribe((res) => {
            //   this.specificKnowledge = res;
            //   this.specificKnowledge = this.specificKnowledge.response.data.unit_specific_contents;
            //   console.log('unt specific data 3' + this.specificKnowledge);
            // }, (error) => {
            //   console.warn('Error in adding unit content' + error);
            // });
          }
          if (element.type === 'skills') {
            console.log('In if when both types are equal');
            this.skills = element;
            this.skillId = this.skills.id;
            console.log('these are the common knowledge' + this.skills);

            // /* find Checked common content */
            // this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.skillId).subscribe((res) => {
            //   this.specificSkills = res;
            //   this.specificSkills = this.specificSkills.response.data.unit_specific_contents;
            //   console.log('unt specific data 4' + this.specificSkills);
            // }, (error) => {
            //   console.warn('Error in adding unit content' + error);
            // });
          }
          if (element.type === 'essential_questions') {
            console.log('In if when both types are equal');
            this.questions = element;
            this.essentialQuestionsId = this.questions.id;
            console.log('these are the essential questions' + this.questions);

            // /* find Checked common content */
            // this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.questions.id).subscribe((res) => {
            //   this.essentialQuestions = res;
            //   this.essentialQuestions = this.essentialQuestions.response.data.unit_specific_contents;
            //   console.log('unt specific data' + this.essentialQuestions);
            // }, (error) => {
            //   console.warn('Error in adding unit content' + error);
            // });
          }
        })
      }, (error) => {
        console.warn(error);
      });
  } /* End Init */
}

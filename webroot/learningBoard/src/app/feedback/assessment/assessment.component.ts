import { Component, OnInit, ViewContainerRef } from '@angular/core';
import { FeedbackService } from '../../services/feedback/feedback.service';
import { ActivatedRoute } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { LabelSettings } from '../../label-settings';
declare var $: any;

@Component({
  selector: 'app-assessment',
  templateUrl: './assessment.component.html',
  styleUrls: ['./assessment.component.scss']
})

export class AssessmentComponent implements OnInit {
  impactTitle: any;

  spinnerEnabled: boolean;
  student: any;
  assessmentId: any;
  studentId: any;
  selectedStudent: any;
  currentPercentage: String;
  assessment: any;
  impacts: any;
  impactCategories: any;
  strands: any;
  standards: any;
  impactScale: any;
  standardScale: any;
  impactModel: any = {};
  standardModel: any = {};
  comments: any;
  isCompleted: any;
  defaultImpactScaleValue: any;
  defaultStandardScaleValue: any;
  impactScaleValueNameMap: any = {};
  standardScaleValueNameMap: any = {};
  impactScaleValueCount: number;
  standardScaleValueCount: number;

  constructor(
    private route: ActivatedRoute,
    private feedbackService: FeedbackService,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) {
    this.toastr.setRootViewContainerRef(vcr);
    this.impactTitle = LabelSettings.IMPACTS;
    this.currentPercentage = '60';
    this.route.parent.params.subscribe(res => {
      this.assessmentId = res.assessment_id;
    });
  }

  ngDoCheck() {

    const studId = this.route.snapshot.paramMap.get('student_id');
    if (studId && this.studentId !== studId) {
      this.studentId = studId;
      this.getEvaluations();
    }
  }

  getEvaluations(){
    this.spinnerEnabled = true;
    this.feedbackService.getEvaluation(this.assessmentId, this.studentId)
      .subscribe(res => {
        this.spinnerEnabled = false;
        this.assessment = res;
        this.assessment = this.assessment.data;
        this.impacts = this.assessment.assessment.impacts;
        this.impactCategories = this.assessment.assessment.impact_categories;
        this.strands = this.assessment.assessment.strands;
        this.standards = this.assessment.assessment.standards;
        this.student = this.assessment.section.section_students[0].student;
        this.impactScale = this.assessment.impacts_scale;
        this.standardScale = this.assessment.standards_scale;
        this.findingDefaultScaleValue(this.impactScale.scale_values, this.standardScale.scale_values);
        this.standardScaleValueCount = 0;
        this.impactScaleValueCount = 0;
        this.scaleValueNameMapping(this.impactScale, 'impact');
        this.scaleValueNameMapping(this.standardScale, 'standard');

        this.mappingImpactData(this.assessment.evaluation_impact_scores);

        this.mappingStandardData(this.assessment.evaluation_standard_scores);

        if (this.assessment.evaluation_feedbacks.length && this.assessment.evaluation_feedbacks[0].comment) {
          this.comments = this.assessment.evaluation_feedbacks[0].comment;
        }else {
          this.comments = '';
        }

        if (this.assessment.evaluation_feedbacks.length && this.assessment.evaluation_feedbacks[0].is_completed) {
          this.isCompleted = this.assessment.evaluation_feedbacks[0].is_completed
        }else {
          this.isCompleted = false;
        }

      });
  }

  scaleValueNameMapping(scale, scaleName) {

    if (scaleName == 'impact') {
      scale.scale_values.forEach(element => {
        this.impactScaleValueNameMap[element.id] = {};
        this.impactScaleValueNameMap[element.id].name = element.name;
        this.impactScaleValueNameMap[element.id].color = element.color;
        this.impactScaleValueCount ++;
      });
    }

    if (scaleName == 'standard') {
      scale.scale_values.forEach(element => {
        this.standardScaleValueNameMap[element.id] = {};
        this.standardScaleValueNameMap[element.id].name = element.name;
        this.standardScaleValueNameMap[element.id].color = element.color;
        this.standardScaleValueCount ++;
      });
    }
  }

  findingDefaultScaleValue(impact_scale_values, standard_scale_values) {

    impact_scale_values.forEach(element => {
      if (element.is_default) {
        this.defaultImpactScaleValue = element.id;
      }
    });

    standard_scale_values.forEach(element => {
      if (element.is_default) {
        this.defaultStandardScaleValue = element.id;
      }
    });
  }

  mappingImpactData(preSetValues) {

    for (const key in this.impacts) {
      if (this.impacts.hasOwnProperty(key)) {

        this.impacts[key].forEach(element => {

          this.impactModel[element.id] = {};

          if (preSetValues && preSetValues[element.id]) {
            this.impactModel[element.id] = {
              impact_id: element.id,
              scale_value_id: preSetValues[element.id].scale_value_id
            }
          } else {
            this.impactModel[element.id] = {
              impact_id: element.id,
              scale_value_id: this.defaultImpactScaleValue
            }
          }

          setTimeout(() => {
            this.slider(
              'impact',
              element.id,
              this.impactScale.scale_values[2].id,
              this.impactScale.scale_values[this.impactScale.scale_values.length - 1].id,
              this.impactModel[element.id].scale_value_id,
              this.impactScaleValueCount - 3,
            );
          }, 500);

        });
      }
    }
  }

  mappingStandardData(preSetValues) {

    for (const key in this.standards) {
      if (this.standards.hasOwnProperty(key)) {

        this.standards[key].forEach(element => {

          this.standardModel[element.id] = {};

          if (preSetValues && preSetValues[element.id]) {
            this.standardModel[element.id] = {
              standard_id: element.id,
              scale_value_id: preSetValues[element.id].scale_value_id
            }
          } else {
            this.standardModel[element.id] = {
              standard_id: element.id,
              scale_value_id: this.defaultStandardScaleValue
            }
          }

          setTimeout(() => {
            this.slider(
              'standard',
              element.id,
              this.standardScale.scale_values[2].id,
              this.standardScale.scale_values[this.standardScale.scale_values.length - 1].id,
              this.standardModel[element.id].scale_value_id,
              this.standardScaleValueCount - 3
            );
          }, 500);

        });
      }
    }
  }

  ngOnInit() {
  }

  setDefaultImpact(impact_id) {
    this.impactModel[impact_id] = this.defaultImpactScaleValue;
  }

  setDefaultStandard(standard_id) {
    this.standardModel[standard_id] = this.defaultStandardScaleValue;
  }

  beforeSave(markingComplete) {

    this.spinnerEnabled = true;

    const saveData = {
      evaluation_id: this.assessmentId,
      student_id: this.studentId,
      evaluation_standard_scores: this.standardModel,
      evaluation_impact_scores: this.impactModel,
      evaluation_feedbacks: {
        comment: this.comments,
        is_completed: markingComplete ? 1 : (this.isCompleted ? 1 : 0)
      }
    }

    if (markingComplete || this.isCompleted) {
      this.saveEvaluation(saveData, 1);
    } else {
      this.saveEvaluation(saveData);
    }
  }

  // markComplete() {
  //   const saveData = {
  //     evaluation_id: this.assessmentId,
  //     student_id: this.studentId,
  //     evaluation_feedbacks: {
  //       is_completed: 1,
  //       comment: this.comments
  //     }
  //   }
  //   this.saveEvaluation(saveData, true);
  // }

  saveEvaluation(data, markingComplete = null) {
    this.feedbackService.saveEvaluation(data)
      .subscribe(res => {
        this.getEvaluations();
        if (markingComplete) {
          this.feedbackService.markComplete(data.student_id);
        }else {
          this.feedbackService.markComplete(data.student_id, 1);
        }
        this.spinnerEnabled = false;
        this.toastr.success('Evaluation saved successfully', 'Success!');
      }, err => {
        this.spinnerEnabled = false;
        this.toastr.error('Evaluation could not be saved!', 'Error!');
      });
  }

  slider(selectorName, entityId, min, max, preSetValue, gridnum) {

    const selector = '#ion-slider-' + selectorName + entityId;

    $(selector).ionRangeSlider({
      type: 'single',
      min: min,
      max: max,
      from: preSetValue,
      step: 1,
      grid: true,
      grid_num: gridnum,
      prettify: (data) => {

        if (selectorName == 'impact') {
          if (this.impactScaleValueNameMap[data].label == null) {
            return this.impactScaleValueNameMap[data].name;
          }else{
            return this.impactScaleValueNameMap[data].label;
          }
        } else {
          if (this.standardScaleValueNameMap[data].label == null){
            return this.standardScaleValueNameMap[data].name;
          }else{
            return this.standardScaleValueNameMap[data].label;
          }
        }
      },
      onStart: () => {
        setTimeout(() => {
          $('.irs-grid-pol.small').css('height', '0px');
        }, 300);
      },
      onFinish: (data) => {

        if (selectorName == 'impact') {
          this.impactModel[entityId].scale_value_id = data.from;
        } else if (selectorName == 'standard') {
          this.standardModel[entityId].scale_value_id = data.from;
        }

        setTimeout(() => {
          this.beforeSave(0);
        }, 500);
      },
    });
  }


}

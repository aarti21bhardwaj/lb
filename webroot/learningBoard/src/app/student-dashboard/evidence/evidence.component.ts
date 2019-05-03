import { Component, OnInit, ViewContainerRef, TemplateRef } from '@angular/core';
import { EvidencesService } from '../../services/evidences/evidences.service';
import { ActivatedRoute } from '@angular/router';
import { AppSettings } from '../../app-settings';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
declare var $: any;

@Component({
  selector: 'app-evidence',
  templateUrl: './evidence.component.html',
  styleUrls: ['./evidence.component.scss']
})
export class EvidenceComponent implements OnInit {
  evidenceId: any;
  isCompleted: any;
  spinnerEnabled: boolean;
  impactModel: any = {};
  impactScaleValueNameMap: any = {};
  impactScaleValueCount: number;
  defaultImpactScaleValue: any;
  impactScale: any;
  impactCategories: any;
  impacts: any;
  evidence: any;
  addActive = false;
  evidences: any;
  evnEndpoint: any;
  filter_elment: any;
  modalRef: BsModalRef;
  impactTitle: any;
  listView : boolean = false;

  constructor(
    private evidenceService: EvidencesService,
    private route: ActivatedRoute,
    public toastr: ToastsManager,
    vcr: ViewContainerRef,
    private modalService: BsModalService,
  ) {
    this.toastr.setRootViewContainerRef(vcr);
  }

  ngOnInit() {
    this.impactTitle = "Impacts";
    this.evnEndpoint = AppSettings.ENVIRONMENT;
    this.addActive = false;
    this.listEvidences();
  }

  listEvidences() {
    this.evidenceService.listEvidences()
      .subscribe((response) => {
        this.evidences = response;
        this.evidences = this.evidences.data;
      }, (error) => {
        console.log(error);
      });
  }

  viewEvidence(id) {
    this.evidenceService.getEvidence(id)
      .subscribe((response : any) => {
        this.evidence = response.data;
      }, (error) => {
        console.log(error);
      });
  }

  deleteEvidence(){
    this.evidenceService.deleteEvidence(this.evidenceId)
      .subscribe((response) => {
        this.toastr.success('Deleted!', 'Evidence deleted successfully!');
        this.listEvidences();
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.log(error);
      });
  }

  evidenceFeedback(id) {
    this.evidenceService.getSelfAssessmentScores(id)
      .subscribe((res) => {
        let response;
        response = res;
        this.evidence = response.data;
        this.impacts = this.evidence.impacts;
        this.impactCategories = this.evidence.impact_categories;
        this.impactScale = this.evidence.impacts_scale;
        this.findingDefaultScaleValue(this.impactScale.scale_values);
        this.impactScaleValueCount = 0;
        this.scaleValueNameMapping(this.impactScale);
        this.mappingImpactData(this.evidence.evidence_impact_scores);
      }, (error) => {
        console.log(error);
      });

  }

  findingDefaultScaleValue(impact_scale_values) {
    impact_scale_values.forEach(element => {
      if (element.is_default) {
        this.defaultImpactScaleValue = element.id;
      }
    });
  }

  scaleValueNameMapping(scale) {
    scale.scale_values.forEach(element => {
      this.impactScaleValueNameMap[element.id] = {};
      this.impactScaleValueNameMap[element.id].name = element.name;
      this.impactScaleValueNameMap[element.id].color = element.color;
      this.impactScaleValueCount++;
    });
  }

  mappingImpactData(preSetValues) {

    for (const key in this.impacts) {
      if (this.impacts.hasOwnProperty(key)) {
        this.impacts[key].forEach(element => {
          console.log('print element of impact' + element.impact_id);
          console.log('print preset values' + preSetValues);
          this.impactModel[element.impact_id] = {};
          if (preSetValues && preSetValues[element.impact_id]) {
            console.log('impact model if condition');
            this.impactModel[element.impact_id] = {
              evidence_impact_id: element.id,
              scale_value_id: preSetValues[element.impact_id].scale_value_id
            }
          } else {
            console.log('impact model else condition' );
            this.impactModel[element.impact_id] = {
              evidence_impact_id: element.id,
              scale_value_id: this.defaultImpactScaleValue
            }
          }

          setTimeout(() => {
            this.slider(
              'impact',
              element.impact_id,
              this.impactScale.scale_values[2].id,
              this.impactScale.scale_values[this.impactScale.scale_values.length - 1].id,
              this.impactModel[element.impact_id].scale_value_id,
              this.impactScaleValueCount - 3,
            );
            console.log('After set time out data');
            console.log(this.impactModel);
          }, 500);

        });
      }
    }
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
          return this.impactScaleValueNameMap[data].name;
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
          console.log('on finish data');
          console.log(this.impactModel);
        }

        setTimeout(() => {
          this.beforeSave(0);
        }, 500);
      },
    });
  }


  beforeSave(markingComplete) {

    this.spinnerEnabled = true;

    console.log('data that will be save');
    console.log(this.impactModel);
    const saveData = {
      evidence_impact_scores: this.impactModel,
      // evaluation_feedbacks: {
      //   comment: this.comments,
      //   is_completed: markingComplete ? 1 : (this.isCompleted ? 1 : 0)
      // }
    }

    if (markingComplete || this.isCompleted) {
      this.saveEvaluation(saveData, 1);
    } else {
      this.saveEvaluation(saveData);
    }
  }

  saveEvaluation(data, markingComplete = null) {
    this.evidenceService.addSelfAssessmentScores(data)
      .subscribe(res => {
        this.spinnerEnabled = false;
        this.toastr.success('Evaluation saved successfully', 'Success!');
      }, err => {
        this.spinnerEnabled = false;
        this.toastr.error('Evaluation could not be saved!', 'Error!');
      });
  }

  ngDoCheck() {

    if (this.route.snapshot.children.length) {
        this.addActive = true;
    }else {
      if (this.addActive) {
        this.addActive = false;
        this.listEvidences();
      }
    }
  }

  openModal(template: TemplateRef<any>, evidenceId) {
    this.evidenceFeedback(evidenceId);
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });

  }

  openDeleteModal(template: TemplateRef<any>, evidenceId) {
    this.evidenceId = evidenceId;
    this.modalRef = this.modalService.show(template, { class: 'modal-sm' });
  }

  openViewModal(template: TemplateRef<any>, evidenceId) {
    this.viewEvidence(evidenceId);
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });

  }

  changeView(view){
    console.log('hey');
    if(view == 'list'){
      this.listView = true;
    }else{
      this.listView = false;
    }
  }

}

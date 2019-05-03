import { Component, OnInit, Input, ViewContainerRef, EventEmitter, Output } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { EvidencesService } from './../../../../../../services/evidences/evidences.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';

@Component({
  selector: 'app-digital-strategies',
  templateUrl: './digital-strategies.component.html',
  styleUrls: ['./digital-strategies.component.scss']
})
export class DigitalStrategiesComponent implements OnInit {
  @Input() commonContent: any;
  @Input() courseId: any;
  @Input() unitId: any;
  @Input() categoryId: any;
  @Input() assessmentId: any;
  @Input() assessments: any;
  @Input() type: any;
  @Input() isDigitalToolUsed: any
  @Input() objectName: any;
  @Input() evidenceId: any;
  evidenceContentsChecked: any;
  description: any = null;
  taskList: any;
  isCollaped: boolean = true
  tree: any;

  @Output() checkIsAccessible = new EventEmitter<any>();
  

  constructor(private unitService: UnitsService, public toastr: ToastsManager, private performanceService: PerformanceTaskService,
    vcr: ViewContainerRef, private evidenceService: EvidencesService) { }

  ngOnInit() {
    console.log('all assesssment list');
    console.log(this.assessments);
  }

  ngOnChanges() {
    if(this.objectName == 'evidence' && this.evidenceId) {
      this.evidenceService.getEvidence(this.evidenceId).
        subscribe((res: any) => {
          
          if(res && res.status == true) {
            this.evidenceContentsChecked = res.data.evidence_contents
          }
          
        }, (error) => {
          
          console.warn(error);
        });
    }
    if (this.objectName == 'assessment' && this.assessmentId) {
      this.checkCommonContent
    }

  }

  checkCommonContent(node) {
    if(this.objectName == 'assessment') {
      let assessmentIndex: any;
      if (node) {
        if (this.assessments) {
          assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
          if (this.assessments[assessmentIndex].assessment_contents) {
            for (let x in this.assessments[assessmentIndex].assessment_contents) {
              if (this.assessments[assessmentIndex].assessment_contents[x].content_value_id == node.id) {
                return true;
              }
            }
          }
        }
      }
    }
    if(this.objectName == 'evidence') {
    
      if (node.is_selectable && this.evidenceContentsChecked && this.evidenceContentsChecked != null) {
        for (let x in this.evidenceContentsChecked) {
          if (this.evidenceContentsChecked[x].content_value_id == node.id) {
            return true;
          }
        }
      }
    }
    
  }

  /* for commmon content */
  checkedCommonContent(e, commonContent, type) {
    if(this.objectName == 'assessment') {
      if (e.target.checked) {
        this.unitService.addAssessmentContents(this.courseId, this.unitId, this.categoryId, commonContent.id, this.assessmentId, type).
          subscribe((res) => {
            this.toastr.success('Saved!', 'Success!');
            let assessmentIndex: any;
            assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
            console.log('assessment index of checked' + assessmentIndex)
            let index: any;
            index = this.assessments[assessmentIndex].assessment_contents.
              map(function (x) { return x.content_value_id; }).indexOf(commonContent.id);
            if (index) {
              let content: any;
              content = res;
              content = content.response.data;
              this.assessments[assessmentIndex].assessment_contents.push(content);
            }
          }, (error) => {
            this.toastr.error(error, 'Error!');
            console.warn(error);
          });
      }
      if (!e.target.checked) {
        this.unitService.deleteAssessmentContents(this.courseId, this.unitId, this.categoryId, commonContent.id, this.assessmentId, type)
          .subscribe((res) => {
            let assessmentIndex: any;
            assessmentIndex = this.assessments.map(function (x) { return x.id; }).indexOf(this.assessmentId);
            console.log('assessment index of checked' + assessmentIndex)
            let index: any;
            index = this.assessments[assessmentIndex].assessment_contents.
              map(function (x) { return x.content_value_id; }).indexOf(commonContent.id);
            this.assessments[assessmentIndex].assessment_contents.splice(index, 1);
            commonContent.checked = false;
            this.toastr.success('Deleted Successfully', 'Success!');
          }, (error) => {
            this.toastr.error(error, 'Error!');
            console.warn(error);
          });
      }
    }

    if(this.objectName == 'evidence') {
      if (e.target.checked) {
        let data = {
          evidence_id : this.evidenceId,
          content_category_id: this.categoryId,
          content_value_id: commonContent.id
        }
        this.evidenceService.addEvidenceContent(data).subscribe((res) => {
          console.log(res);
          this.toastr.success('Saved!', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      } if (!e.target.checked) {
        let data = {
          evidence_id : this.evidenceId,
          content_value_id: commonContent.id
        }
        this.evidenceService.deleteEvidenceContent(data).subscribe((res) => {
          console.log(res);
          this.toastr.success('Deleted Successfully', 'Success!');
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn(error);
        });
      }
    }
   
  }

  IsStrategyUsed(event) {
    console.log('Is digital strategy used.')
    console.log(event);
    this.isDigitalToolUsed = event
    this.checkIsAccessible.emit(event);
  }

  expandAll(tree) {
    this.isCollaped = false;
    tree.expandAll();
  }

  collapseAll(tree) {
    this.isCollaped = true;
    tree.collapseAll();
  }



}

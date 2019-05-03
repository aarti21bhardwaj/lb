import { Component, OnInit, Input, ViewChild, Output, ViewContainerRef, TemplateRef } from '@angular/core';
import { PerformanceTaskService } from '../../../../../../services/foundation/units/performance-task/performance-task.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-assessment-specific-content',
  templateUrl: './assessment-specific-content.component.html',
  styleUrls: ['./assessment-specific-content.component.scss']
})
export class AssessmentSpecificContentComponent implements OnInit {
  @Input() courseId;
  @Input() unitId;
  @Input() assessmentId;
  @Input() contentCategoryId;
  description: any;
  descriptionId: any;
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      // 'inlineStyle', 'paragraphStyle',
      // 'paragraphFormat',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      // 'insertFile',
      'insertTable', '|',
      // 'emoticons', 
      'specialCharacters',
      // 'insertHR',
      'selectAll', 'clearFormatting', '|',
      // 'print', 
      'spellChecker',
      // 'help', 
      // 'html', '|', 
      'undo', 'redo']
  }

  constructor(private performanceService: PerformanceTaskService, public toastr: ToastsManager,
    vcr: ViewContainerRef) { }

  ngOnInit() {
    if (this.unitId && this.courseId) {
      this.getSpecificAssessment();
    }
  }

  getSpecificAssessment() {
    this.performanceService.getSpecificContent(
      this.courseId, this.unitId, this.assessmentId, this.contentCategoryId)
      .subscribe((response) => {
        let descriptionData: any;
        descriptionData = response;
        descriptionData = descriptionData.response.data;
        if (descriptionData != null && typeof descriptionData !== 'undefined') {
          this.description = descriptionData.description;
          this.descriptionId = this.description.id;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  addAssessmentSpecific(id = null) {
    if (this.descriptionId != null && typeof this.descriptionId !== 'undefined') {
      this.performanceService.editspecificContent(this.courseId, this.unitId, this.assessmentId, this.contentCategoryId,
         this.descriptionId, this.description)
        .subscribe((response) => {
          this.toastr.success('Assessment category saved!', 'Success!');
        }, (error) => {
          this.toastr.error('Unable to save category. Error Message:' + error.message, 'Error!');
          console.warn(error);
        });
    } else {
      this.performanceService.addSpecificAssessment(this.courseId, this.unitId, this.assessmentId, this.contentCategoryId, this.description)
      .subscribe((response) => {
        this.toastr.success('Assessment category saved!', 'Success!');
      }, (error) => {
        this.toastr.error('Unable to save category. Error Message:' + error.message, 'Error!');
        console.warn(error);
      });
    }
  }
}

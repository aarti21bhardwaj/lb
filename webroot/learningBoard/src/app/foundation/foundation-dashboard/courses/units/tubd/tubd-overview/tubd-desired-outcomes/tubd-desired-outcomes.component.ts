import { Component, OnInit, Input, OnChanges, ViewContainerRef} from '@angular/core';
import { CoursesService } from './../../../../../../../services/foundation/courses/courses.service';
import { UnitsService } from './../../../../../../../services/foundation/units/units.service';
import { ActivatedRoute } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import {TubdComponent} from '../../tubd.component';
import { AppSettings } from '../../../../../../../app-settings';

@Component({
  selector: 'app-tubd-desired-outcomes',
  templateUrl: './tubd-desired-outcomes.component.html',
  styleUrls: ['./tubd-desired-outcomes.component.scss']
})
export class TubdDesiredOutcomesComponent implements OnInit {
  courseId: any;
  unitId: any;
  courseDetails;
  understandings: any;
  questions: any;
  understandingText: any;
  enduringUnderstandings: any = [];
  enduringUnderstandingsId: any;
  essentialQuestions: any = [];
  essentialQuestionsId: any;
  showColumnTable = false;
  
  specificUnderstandingId: any;
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

  constructor(private courseService: CoursesService,
    private unitService: UnitsService,
    private acivatedRoute: ActivatedRoute, public toastr: ToastsManager,
    vcr: ViewContainerRef,
    private parent: TubdComponent,
  ) {
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
    }

  ngOnInit() {
    this.showColumnTable = AppSettings.SHOW_COLUMN_TABLE;
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'enduring_undertandings') {
            console.log('In if when both types are equal');
             this.understandings = element;
            this.enduringUnderstandingsId = this.understandings.id;
            console.log('these are the enduring understandings' + this.understandings);

            /* find Checked commin content */
            this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.understandings.id).subscribe((res) => {
              this.enduringUnderstandings = res;
              this.enduringUnderstandings = this.enduringUnderstandings.response.data.unit_specific_contents;
              console.log('unt specific data' + this.enduringUnderstandings);
            }, (error) => {
              console.warn('Error in adding unit content' + error);
            });
          }
          if (element.type === 'essential_questions') {
            console.log('In if when both types are equal');
             this.questions = element;
            this.essentialQuestionsId = this.questions.id;
            console.log('these are the essential questions' + this.questions);

            /* find Checked commin content */
            this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.questions.id).subscribe((res) => {
              this.essentialQuestions = res;
              this.essentialQuestions = this.essentialQuestions.response.data.unit_specific_contents;
              console.log('unt specific data' + this.essentialQuestions);
            }, (error) => {
              console.warn('Error in adding unit content' + error);
            });
          }
        })
      }, (error) => {
        console.warn(error);
      });
  } /* End Init */

}

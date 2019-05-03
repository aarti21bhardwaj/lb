import { Component, OnInit, Input, OnChanges, ViewContainerRef } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
declare var $: any;

@Component({
  selector: 'app-add-unit-specific-component',
  templateUrl: './add-unit-specific-component.component.html',
  styleUrls: ['./add-unit-specific-component.component.scss']
})
export class AddUnitSpecificComponentComponent implements OnInit {
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() categoryId: number;
  @Input() commonContents: any;
  @Input() unitSpecificContents: any = [];
  @Input() showColumnTable = false;
  unitSpecificValue: any;
  specificContentId: any;
  showAddSpecificContent = false;
  showeditSpecificValue = false;
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough',
      'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color',
      'align',
      'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
      'insertLink', 'insertImage', 'insertVideo', 'embedly',
      'insertTable', '|',
      'specialCharacters',
      'selectAll', 'clearFormatting', '|',
      'spellChecker',
      'undo', 'redo',
      'insert'
    ]
  }
  
  constructor(private unitService: UnitsService, public toastr: ToastsManager,
    vcr: ViewContainerRef, public courseService: CoursesService) {
    this.toastr.setRootViewContainerRef(vcr);
     }

  ngOnInit() {
    this.getUnitSpecificContents();
    if(this.showColumnTable) {
      $.FroalaEditor.DefineIcon('insert', {NAME: 'plus'});
      $.FroalaEditor.RegisterCommand('insert', {
        title: 'Add 3 column table',
        focus: true,
        undo: true,
        refreshAfterCallback: true,
  
        callback: function () {
          this.html.insert('<table style="width: 100%;"><thead><tr><th style="text-align: center;">Adjusted</th><th style="text-align: center;">Core</th><th style="text-align: center;">Extended</th></tr></thead><tbody><tr><td style="width: 33.3333%;"><br></td><td style="width: 33.3333%;"><br></td><td style="width: 33.3333%;"><br></td></tr></tbody></table>');
        }
      });
    }
  }
  ngOnChanges() {
  }

  editSpecificContent(id) {
    this.showAddSpecificContent = true;
    this.unitSpecificContents.forEach(element => {
      if (element.id === id) {
        this.unitSpecificValue = element.text;
        this.specificContentId = id;
        this.showeditSpecificValue = true;
      }
    });
  }

  deleteSpecificContents(id) {
    this.unitService.deleteUnitSpecificContent(this.courseId, this.unitId, this.categoryId, id).subscribe((res) => {
      this.toastr.success('Deleted Successfully', 'Success!');
      this.courseService.refreshUnitSummary();
      this.getUnitSpecificContents();
    }, (error) => {
      console.warn('Error in deleting unit content' + error);
    });
  }
  addUnitSpecificContent() {
    this.showAddSpecificContent = true;
  }
  addUnitContent() {
    if (this.showeditSpecificValue === true) {
      this.unitService.editUnitSpecificContent(this.courseId, this.unitId, this.categoryId, this.unitSpecificValue, this.specificContentId)
        .subscribe((res) => {
          this.toastr.success('Saved!', 'Success!');
          this.getUnitSpecificContents();
          // window.location.reload();
          this.specificContentId = '';
          this.unitSpecificValue = '';
          this.showeditSpecificValue = false;
          this.courseService.refreshUnitSummary();
        }, (error) => {
          // this.toastr.error(error, 'Error!');
          console.warn('Error in editing unit content' + error);
        });
    } else {
      this.unitService.addUnitSpecificContent(this.courseId, this.unitId, this.categoryId, this.unitSpecificValue).subscribe((res) => {
        // window.location.reload();
        this.getUnitSpecificContents();
        this.toastr.success('Saved!', 'Success!');
        this.courseService.refreshUnitSummary();
        this.unitSpecificValue = '';
        this.showeditSpecificValue = false;
      }, (error) => {
        // this.toastr.error(error, 'Error!');
        console.warn('Error in adding unit content' + error);
      });
    }
    this.showAddSpecificContent = false;

  }

  getUnitSpecificContents() {
    this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
      const specificData: any = res;
      if (specificData.response.data != null) {
        this.unitSpecificContents = specificData.response.data.unit_specific_contents;
      }
    }, (error) => {
      console.warn('Error in adding unit content' + error);
    });
  }

}

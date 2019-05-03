import { Component, OnInit, OnChanges, Input, AfterViewInit, ViewContainerRef } from '@angular/core';
import { CoursesService } from '../../../../../../services/foundation/courses/courses.service';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-unitcontent',
  templateUrl: './unitcontent.component.html',
  styleUrls: ['./unitcontent.component.scss']
})
export class UnitcontentComponent implements OnInit {
  specificContentId: any = '';
  showeditSpecificValue = false;
  newSpecificValue: any;
  spinnerEnabled: boolean;
  @Input() commonContents: any;
  @Input() specificContentType: any;
  @Input() unitSpecificContents: any = [];
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() categoryId: number;
  unitCheckedData: any;
  showAddSpecificContent = false;
  unitSpecificValue: any;
  contentValue: any;
  courseDetails: any;
  unitData: any;
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
      'undo', 'redo']
  }
  constructor(
    private courseService: CoursesService,
    private unitService: UnitsService,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) { }

  ngOnChanges() {
    this.getCheckedData();
    this.getUnitSpecificContents();
  }
  ngOnInit() {
    // this.addUnitSpecificContent();
  }
  /* Show textfield to add unit specific goal */
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
        this.showeditSpecificValue = false;
        this.unitSpecificValue = '';
        this.courseService.refreshUnitSummary();
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn('Error in editing unit content' + error);
      });
    } else {
      this.unitService.addUnitSpecificContent(this.courseId, this.unitId, this.categoryId, this.unitSpecificValue).subscribe((res) => {
        // window.location.reload();
        this.getUnitSpecificContents();
        this.toastr.success('Saved!', 'Success!');
        this.showeditSpecificValue = false;
        this.unitSpecificValue = '';
        this.courseService.refreshUnitSummary();
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn('Error in adding unit content' + error);
      });
    }
    this.showAddSpecificContent = false;

  }

  checked(e, id) {
    if (e.target.checked) {
      this.unitService.addUnitcontent(this.courseId, this.unitId, this.categoryId, id).subscribe((res) => {
        this.toastr.success('Saved!', 'Success!');
        this.courseService.refreshUnitSummary();
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
    if (!e.target.checked) {
      this.unitService.deleteUnitContent(this.courseId, this.unitId, this.categoryId, id).subscribe((res) => {
        this.toastr.success('Deleted Successfully', 'Success!');
        this.courseService.refreshUnitSummary();
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
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
      // window.location.reload();
      this.toastr.success('Deleted Successfully', 'Success!');
      this.courseService.refreshUnitSummary();
      this.getUnitSpecificContents();
    }, (error) => {
      this.toastr.error(error, 'Error!');
      console.warn('Error in deleting unit content' + error);
    });
  }

  checkSelectedNode(node){
    for (let x in this.unitCheckedData) {
      if (this.unitCheckedData[x].content_value_id == node.id) {
        return true;
      }
    }
  }

  getCheckedData() {
    this.unitService.getUnitContent(
      this.courseId, this.unitId, this.categoryId)
      .subscribe((res) => {
        this.unitCheckedData = res;
        if (this.unitCheckedData.response.data != null && typeof this.unitCheckedData.response.data != 'undefined') {
          this.unitCheckedData = this.unitCheckedData.response.data.unit_contents;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  getUnitSpecificContents() {
    this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
      this.unitSpecificContents = res;
      if (this.unitSpecificContents.response.data != null && typeof this.unitSpecificContents.response.data != 'undefined') {
        this.unitSpecificContents = this.unitSpecificContents.response.data.unit_specific_contents;
      }
    }, (error) => {
      console.warn('Error in adding unit content' + error);
    });
  }
}

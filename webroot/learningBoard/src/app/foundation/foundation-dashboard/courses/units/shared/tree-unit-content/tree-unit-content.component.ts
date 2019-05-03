import { Component, OnInit, Input, OnChanges, ViewContainerRef } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
@Component({
  selector: 'app-tree-unit-content',
  templateUrl: './tree-unit-content.component.html',
  styleUrls: ['./tree-unit-content.component.scss']
})
export class TreeUnitContentComponent implements OnInit {
  @Input() commonContents: any;
  @Input() unitSpecificContents: any = [];
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() categoryId: number;
  @Input() showTableflag: boolean;
  @Input() title: any;

  unitCheckedData: any;
  unitSpecificValue: any;
  showAddSpecificContent: boolean;
  specificContentId: any;
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
      'undo', 'redo']
  }
  constructor(private unitService: UnitsService,
    public toastr: ToastsManager, public courseService: CoursesService,
    vcr: ViewContainerRef) { }

  ngOnInit() {
    
  }
  ngOnChanges() {
    console.log('In ngOn changes');
    this.getCheckedData();
    this.getUnitSpecificContents();
  }

  getCheckedData() {
    this.unitService.getUnitContent(
      this.courseId, this.unitId, this.categoryId)
      .subscribe((res) => {
        let response
        response = res;
        if (response && response.response.data != null) {
          this.unitCheckedData = response.response.data.unit_contents;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  getUnitSpecificContents() {
    this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
      let data
      data = res;
      if (data.response.data && data.response.data != null) {
        this.unitSpecificContents = data.response.data.unit_specific_contents;
      }
      console.log('unt specific data' + this.unitSpecificContents);
    }, (error) => {
      console.warn('Error in adding unit content' + error);
    });
  }

  public check(node, checked, e) {
    console.log(node);
    console.log('is checked');
    console.log(checked);

    if (e.target.checked) {
      node.data.checked = checked;
      this.unitService.addUnitcontent(this.courseId, this.unitId, this.categoryId, node.id).subscribe((res) => {
        console.log(res);
        this.courseService.refreshUnitSummary();
        this.toastr.success('Saved!', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    } if (!e.target.checked) {
      node.data.checked = null;
      this.unitService.deleteUnitContent(this.courseId, this.unitId, this.categoryId, node.id).subscribe((res) => {
        console.log(res);
        this.courseService.refreshUnitSummary();
        this.toastr.success('Deleted Successfully', 'Success!');
      }, (error) => {
        this.toastr.error(error, 'Error!');
        console.warn(error);
      });
    }
  }
  checkSelectedNode(node) {
    console.log(this.unitCheckedData);
    console.log('here is node info');
    console.log(node);
    console.log('node info ends');
    if (node.is_selectable) {
      for (let x in this.unitCheckedData) {
        if (this.unitCheckedData[x].content_value_id == node.id) {
          return true;
        }
      }
    }
  }
  expandAll(tree) {
    tree.expandAll();
  }

  addUnitContent() {
    console.log('this is the unit content id');
    if (this.showeditSpecificValue === true) {
      console.log('In case of edit content');
      this.unitService.editUnitSpecificContent(this.courseId, this.unitId, this.categoryId, this.unitSpecificValue, this.specificContentId)
        .subscribe((res) => {
          console.log('Response after editing unit specific content' + res);
          this.toastr.success('Saved!', 'Success!');
          this.getUnitSpecificContents();
          // window.location.reload();
          this.courseService.refreshUnitSummary();
          this.specificContentId = '';
          this.unitSpecificValue = '';
          this.showeditSpecificValue = false;
        }, (error) => {
          this.toastr.error(error, 'Error!');
          console.warn('Error in editing unit content' + error);
        });
    } else {
      console.log('In case of add content');
      console.log('In adding unit transfer goal' + this.unitSpecificValue);
      this.unitService.addUnitSpecificContent(this.courseId, this.unitId, this.categoryId, this.unitSpecificValue).subscribe((res) => {
        console.log('Response after adding unit specific content' + res);
        // window.location.reload();
        this.toastr.success('Deleted Successfully', 'Success!');
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

  addUnitSpecificContent() {
    this.showAddSpecificContent = true;
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
    console.log('In edit unit specific content');
  }

  deleteSpecificContents(id) {
    console.log('In delete unit specific content');
    this.unitService.deleteUnitSpecificContent(this.courseId, this.unitId, this.categoryId, id).subscribe((res) => {
      console.log('Response after deleting unit specific content' + res);
      // window.location.reload();
      this.toastr.success('Deleted Successfully', 'Success!');
      this.courseService.refreshUnitSummary();
      this.getUnitSpecificContents();
    }, (error) => {
      this.toastr.error(error, 'Error!');
      console.warn('Error in deleting unit content' + error);
    });
  }

}

import { ActivatedRoute } from '@angular/router';
import { ResourcesReflectionsService } from '../../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'
import { OnInit, TemplateRef, Component, ViewContainerRef } from '@angular/core';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { PypComponent } from '../../../pyp/pyp.component';

@Component({
  selector: 'app-reflections',
  templateUrl: './reflections.component.html',
  styleUrls: ['./reflections.component.scss']
})
export class ReflectionsComponent implements  OnInit {
  initiatedActionDes: any;
  initiatedInqDes: any;
  initiatedAction: any;
  initiatedInq: any;
  unitId: any;
  courseId: any;
  showInquiryEdit = false;
  showActionEdit = false;
  showInquiryAdd = true;
  showactionAdd = true;
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
  };


  constructor(private rnRService: ResourcesReflectionsService, private acivatedRoute: ActivatedRoute,
    public toastr: ToastsManager,
    vcr: ViewContainerRef, private parent: PypComponent) { 
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      console.log(res);
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
      console.log('This is the unit id in assessments ' + this.unitId);
      console.log('This is the course id in assessments ' + this.courseId);
    });
    parent.isMetaUnitActive = false;
  }

  ngOnInit() {
    this.rnRService.getUnitReflections(this.courseId, this.unitId, '')
      .subscribe((response) => {
        let reflectionData: any;
        reflectionData = response;
        reflectionData = reflectionData.data;
        if (reflectionData != null && typeof reflectionData != 'undefined' && reflectionData.length == 0) {
          reflectionData.forEach(element => {
            if (element.reflection_subcategory_id == 7) {
              this.showInquiryEdit = true;
              this.showInquiryAdd = false;
              this.initiatedInq = element;
              this.initiatedInqDes = element.description;
            }
            if (element.reflection_subcategory_id == 8) {
              this.showActionEdit = true;
              this.showactionAdd = false;
              this.initiatedAction = element;
              this.initiatedActionDes = element.description;
            }
          });
        }
      }, error => {
        console.log(error);
      });
  }

  saveReflection(type) {
    let data: any;
    if (type == 'inquiries') {
      data = {
        id: null,
        description: this.initiatedInqDes,
        reflection_category_id: 1,
        object_name: null,
        object_identifier: null,
        reflection_subcategory_id: 7
      }
    }
    if (type == 'actions') {
      data = {
        id: null,
        description: this.initiatedActionDes,
        reflection_category_id: 1,
        object_name: null,
        object_identifier: null,
        reflection_subcategory_id: 8
      }
    }
    this.rnRService.addUnitReflection(this.courseId, this.unitId, data)
      .subscribe(res => {
        this.toastr.success('Reflection Saved!', 'Success!');
        if (type == 'inquiries') {
          this.showInquiryEdit = true;
          this.showInquiryAdd = false;
        }
        if (type == 'actions') {
          this.showActionEdit = true;
          this.showactionAdd = false;
        }
      }, err => {
        this.toastr.error(err, 'Error!');
        console.log(err);
      });
  }

  editReflection(type) {
    let data: any;
    let catId: any;
    if (type == 'inquiries') {
      catId = this.initiatedInq.id;
      data = {
        id: this.initiatedInq.id,
        description: this.initiatedInqDes,
        reflection_category_id: 1,
        object_name: null,
        object_identifier: null,
        reflection_subcategory_id: 7
      }
    }
    if (type == 'actions') {
      catId = this.initiatedAction.id;
      data = {
        id: null,
        description: this.initiatedInq,
        reflection_category_id: 1,
        object_name: null,
        object_identifier: null,
        reflection_subcategory_id: 8
      }
    }
    this.rnRService.editReflection(this.courseId, this.unitId, catId, data)
      .subscribe(res => {
        this.toastr.success('Reflection Saved!', 'Success!');
        if (type == 'inquiries') {
          this.showInquiryEdit = true;
          this.showInquiryAdd = false;
        }
        if (type == 'actions') {
          this.showActionEdit = true;
          this.showactionAdd = false;
        }
      }, err => {
        this.toastr.error(err, 'Error!');
        console.log(err);
      });
  }
 
}

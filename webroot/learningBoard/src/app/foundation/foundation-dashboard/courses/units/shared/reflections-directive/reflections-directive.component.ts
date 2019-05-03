import { Component, OnInit, Input } from '@angular/core';
import { ResourcesReflectionsService } from '../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'

@Component({
  selector: 'app-reflections-directive',
  templateUrl: './reflections-directive.component.html',
  styleUrls: ['./reflections-directive.component.scss']
})
export class ReflectionsDirectiveComponent implements OnInit {

  @Input() reflections: any;
  @Input() courseId: number;
  @Input() unitId: number;
  @Input() reflection_category_id: number;
  @Input() object_name: any = null;
  @Input() object_identifier: any = null;
  @Input() reflectionSubcategoryId: any= false;
  @Input() title: string;

  reflection: boolean; // Controls the display of the inline form for reflection
  reflection_text: any;
  editingReflection: boolean = false;
  editReflectionId: number;
  collapseBtn : boolean = false;
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
  private JSObject: Object = Object;

  constructor(
    private rnRService: ResourcesReflectionsService,
  ) {
    this.reflection = false; // initialize the reflection form. keeps it hidden
  }

  ngOnInit() {
    // if (!this.reflectionSubcategoryId || typeof this.reflectionSubcategoryId ==='undefined') {
    //   this.reflectionSubcategoryId = null;
    // }
    if (!this.title || typeof this.title === 'undefined')  {
      this.title = 'REFLECTIONS';
    }
    this.getReflections();
  }

  showReflection() {
    this.reflection = true;
    this.collapseBtn = true;
  }

  hideReflection() {
    this.reflection = false;
    this.collapseBtn = false;
  }

  getReflections() {
    this.reflections = [];
    let urlQueryString = '';
    if (this.object_name && this.object_identifier) {
      urlQueryString = '?object_name=' + this.object_name + '&object_identifier=' + this.object_identifier;
    }

    this.rnRService.getUnitReflections(this.courseId, this.unitId, urlQueryString)
      .subscribe((response) => {
        let reflectionData: any;
        reflectionData = response;
        reflectionData = reflectionData.data;
        this.reflections = reflectionData;
      }, error => {
        console.log(error);
      });
  }

  saveReflection() {
    this.reflection = false;
    this.collapseBtn = false;
    let data: any;
    if (this.reflectionSubcategoryId) {
       data = {
        id: null,
        description: this.reflection_text,
        reflection_category_id: 1,
        object_name: this.object_name,
        object_identifier: this.object_identifier,
        reflection_subcategory_id : this.reflectionSubcategoryId
      }
    } else {
      data = {
        id: null,
        description: this.reflection_text,
        reflection_category_id: 1,
        object_name: this.object_name,
        object_identifier: this.object_identifier
      }
    }

    if (this.courseId || this.unitId) {

      // Checking if the data is edited or saved for the first time;
      if (!this.editingReflection) {

        this.rnRService.addUnitReflection(this.courseId, this.unitId, data)
          .subscribe(res => {
            this.refershVariables();
            this.getReflections();
          }, err => {
            console.log(err);
          });
      }else {
        data.id = this.editReflectionId;
        this.rnRService.editReflection(this.courseId, this.unitId, this.editReflectionId, data)
          .subscribe(res => {
            this.refershVariables();
            this.getReflections();
          }, err => {
            console.log(err);
          });
      }
    } else {
      alert('Either course id or unit id is missing.');
    }
  }

  deleteReflection(id) {
    this.rnRService.deleteReflection(this.courseId, this.unitId, id)
    .subscribe(response => {
      this.getReflections();
    }, error => {
      console.log(error);
    });
  }

  openEdit(category, id) {

    this.reflection = true;
    this.editReflectionId = id;
    this.editingReflection = true;
    this.reflections[category].forEach(reflection => {
      if (reflection.id === id) {
        this.reflection_text = reflection.description;
      }
    });
  }

  refershVariables() {

    this.reflection = false;
    this.reflection_text = null;
    this.reflection_text = null;
    this.editingReflection = false;
    this.editReflectionId = null;
    this.object_identifier = null;
    this.object_name = null;
  }

}

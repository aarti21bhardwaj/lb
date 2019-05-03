import {
  Component,
  AfterViewInit,
  OnInit,
  EventEmitter,
  OnDestroy,
  Input,
  Output,
  ViewContainerRef
} from '@angular/core';
import 'tinymce';
import 'tinymce/themes/modern';
import 'tinymce/plugins/table';
import 'tinymce/plugins/link';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

import { TreeModule } from 'angular-tree-component';
import { ITreeOptions, IActionMapping } from 'angular-tree-component';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';
import { TeacherService } from 'app/services/foundation/teachers/teacher.service';
import { UnitcontentComponent } from '../../shared/unitcontent/unitcontent.component';
import { TubdComponent } from '../../tubd/tubd.component';
import { ActivatedRoute } from '@angular/router';


declare var tinymce: any;
@Component({
  selector: 'app-tubd-overview',
  templateUrl: './tubd-overview.component.html',
  styleUrls: ['./tubd-overview.component.scss']
})
export class TubdOverviewComponent implements OnInit {

  spinnerEnabled: boolean = true;
  areAllServicesLoaded: boolean = false;
  categoryId: any;
  unitData: any;
  teachers: any;
  courses: any;
  categories: any = {};
  allCourses: any = [];
  allTeachers: any = [];
  selectedTeachers: any = [];
  selectedCourses: any = [];
  dateRange: any = [];
  bsRangeValue: any = [];
  private value: any = ['Unit Of Inquiry'];
  private _disabledV = '0';
  private disabled = false;
  public isDisabled = false;
  showAddSpecificGoals = false;
  isOverlayVisible: boolean = false;
 
  courseId: any;
  unitId: any;
  checked: any;
  courseDetails;
  editoroptions: any = {
    key: 'DLAHYKAJOEc1HQDUH==',
    imageUpload: false,
    videoUpload: false,
    toolbarButtons: [ 'fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 
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
  private get disabledV() {
    return this._disabledV;
  }
  public status: { isopen: boolean } = { isopen: false };
  public commonConcepts: any;

  private set disabledV(value: string) {
    this._disabledV = value;
    this.disabled = this._disabledV === '1';
  }
  public selected(value: any, field: any): void {
    console.log('Selected value is: ', value);
    this.unitData.teachers = value;
  }
  public removed(value: any, field: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any, field: any): void {
    if (field === 'teachers') {
      this.selectedTeachers = value;
      // this.unitData.unit.teachers = value;
      console.log(this.selectedTeachers);
    }

    if (field === 'courses') {
      this.selectedCourses = value;
      // this.unitData.unit.courses = value;
      console.log(this.selectedCourses);
    }
    this.value = value;
  }

  // tslint:disable-next-line:use-life-cycle-interface
  ngAfterViewInit() {
  }



  // tslint:disable-next-line:use-life-cycle-interface
  ngOnDestroy() {
  }

  // public disabled = false;
  public toggled(open: boolean): void {
    console.log('Dropdown is now: ', open);
  }

  constructor(
      private unitService: UnitsService,
      private courseService: CoursesService,
      private teacherService: TeacherService,
      private acivatedRoute: ActivatedRoute,
      private parent: TubdComponent,
      public toastr: ToastsManager,
      vcr: ViewContainerRef
    ) {
    this.toastr.setRootViewContainerRef(vcr);
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = true;
  }

  ngOnInit() {
    this.spinnerEnabled = true;
    this.areAllServicesLoaded  = false;
    
    /* Unit service data */
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      this.unitData = res;
      this.unitData = this.unitData.unit;
      // this.unitService.unitData = this.unitData;
      if (this.unitData.is_archived) {
        this.isOverlayVisible = true
      }
    
      console.log('this is the unit data response' + this.unitData);
      if(this.unitData.start_date && this.unitData.end_date ){
        this.bsRangeValue = [new Date(this.unitData.start_date), new Date(this.unitData.end_date)];
      }
      // this.unitData.unit.start_date = this.bsRangeValue[0];
      // this.unitData.unit.end_date = this.bsRangeValue[1];
      this.unitData.courses.forEach(element => {
        this.selectedCourses.push({ id: element.id, text: element.name });
      });
      console.log('these are the selected courses' + this.selectedCourses);
      this.unitData.teachers.forEach(element => {
        this.selectedTeachers.push({ id: element.id, text: element.first_name });
      });
      console.log('these are the selected teachers' + this.selectedTeachers);
      this.spinnerEnabled = false;
    }, (error) => console.warn('Error in getting course' + error)
    );

    /* Course service data  */
    this.courseService.getCourses().subscribe((res) => {
      this.courses = res;
      console.log('these are the courses' + this.courses);
      this.courses.data.forEach(element => {
        this.allCourses.push({ id: element.id, text: element.text });
      });
      console.log('these are the allCourses' + this.allCourses);
      // this.allCourses = this.courses.data;
    }, (error) => console.warn('Error in getting course' + error)
    );

    /* Teacher service data */
    this.teacherService.getTeachers().subscribe((res) => {
      this.teachers = res;
      this.teachers.data.forEach(element => {
        this.allTeachers.push({ id: element.id, text: element.first_name });
      });
      // this.allTeachers = this.teachers.data
      console.log('these are the teachers' + this.allTeachers);
    }, (error) => console.warn('Error in getting course' + error)
    );

    /* Getting course by course Id */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          if (element.type === 'common_concepts') {
            console.log('In getting common concept data' + this.commonConcepts);
            this.commonConcepts = element;
            this.categoryId = this.commonConcepts.id;
          }
        })
      }, (error) => {
        console.warn(error);
      });

  } /* Init function ends here. */

  editUnit() {
    console.log('without format' + this.bsRangeValue[0]);
    console.log('after format' + (new Date(this.bsRangeValue[0])).toDateString());
    const data = {
      'course_id': this.selectedCourses,
      'teacher_id': this.selectedTeachers,
      'name': this.unitData.name,
      'start_date': this.bsRangeValue[0]?((new Date(this.bsRangeValue[0])).toDateString()):'',
      'end_date': this.bsRangeValue[1]?((new Date(this.bsRangeValue[1])).toDateString()):'',
      'description': this.unitData.description

    }
    console.log('this is the data that is going to save' + data);
    this.unitService.editUnits(this.courseId, this.unitId, data).subscribe((res) => {
      console.log(res);
      this.toastr.success('Unit saved!', 'Success!');
    }, (error) => {
      console.warn(error);
      this.toastr.error('Unit could not be saved!', 'Error!');
    });
  }

}

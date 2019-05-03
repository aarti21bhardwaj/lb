import { Component, AfterViewInit, OnInit, Input, Output, ViewContainerRef } from '@angular/core';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';
import { TeacherService } from 'app/services/foundation/teachers/teacher.service';
import { UnitcontentComponent } from '../../shared/unitcontent/unitcontent.component';
import { PypComponent } from '../../pyp/pyp.component';
import { ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-pyp-unit-overview',
  templateUrl: './pyp-unit-overview.component.html',
  styleUrls: ['./pyp-unit-overview.component.scss']
})
export class PypUnitOverviewComponent implements OnInit {
  theme: any;
  spinnerEnabled = true;
  areAllServicesLoaded = false;
  categoryId: any;
  unitData: any;
  teachers: any;
  courses: any;
  categories: any = {};
  allCourses: any = [];
  allTeachers: any = [];
  allTransThemes: any = [];
  allUnitTypes: any = [];
  selectedTeachers: any = [];
  selectedCourses: any = [];
  selectedTransTheme: any = [];
  selectedUnitType: any = [];
  dateRange: any = [];
  bsRangeValue: any = [];
  private _disabledV = '0';
  private disabled = false;
  public isDisabled = false;
  showAddSpecificGoals = false;
  unitSpecificContents: any = [];
  courseId: any;
  unitId: any;
  checked: any;
  courseDetails;
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
  private get disabledV() {
    return this._disabledV;
  }
  public status: { isopen: boolean } = { isopen: false };


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

    if (field === 'unitType') {
      // this.selectedUnitType = value;
      // this.unitData.unit.courses = value;
      this.selectedUnitType = [];
      this.selectedUnitType.push(value);
      console.log('selected unit types' + this.selectedUnitType);
    }

    if (field === 'transTheme') {
      this.selectedTransTheme = [];
      this.selectedTransTheme.push(value);
      this.theme.data.forEach(element => {
        if (value.id === element.id) {
          this.unitData.trans_disciplinary_theme.description = element.description;
        }
      });
      // this.selectedTransTheme = value;
      // this.unitData.unit.courses = value;
      console.log('selected theme data' + this.selectedTransTheme);
    }
  }

  // tslint:disable-next-line:use-life-cycle-interface
  ngAfterViewInit() {
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
    private parent: PypComponent,
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
    this.areAllServicesLoaded = false;

    /* Unit service data */
    this.unitService.getUnit(this.courseId, this.unitId).subscribe((res) => {
      let response;
      response = res;
      this.unitData = response.unit;
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
      this.categories = this.unitData.unit_contents.content_value_id;

      if (this.unitData.trans_disciplinary_theme) {
        this.unitData.trans_disciplinary_theme.text = this.unitData.trans_disciplinary_theme.name;
        this.selectedTransTheme.push(this.unitData.trans_disciplinary_theme);
        // this.selectedTransTheme = this.unitData.trans_disciplinary_theme;
      } else {
        this.unitData.trans_disciplinary_theme.description = this.unitData.description;
      }
      if (this.unitData.unit_types.length > 0) {
        this.unitData.unit_types.forEach(element => {
          this.selectedUnitType.push({ id: element.type.id, text: element.type.name });
        });
      }
 
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

    /* Getting unit types */
    this.unitService.getTypes().subscribe((res) => {
      let types: any;
      types = res;
      types.data.forEach(element => {
        this.allUnitTypes.push({ id: element.id, text: element.name });
      });
      console.log('these are the unit types' + this.allUnitTypes);
    }, (error) => console.warn('Error in getting types' + error)
    );

    /* Getting trans themes */
    this.unitService.getTransTheme().subscribe((res) => {
      // let theme: any;
      this.theme = res;
      this.theme.data.forEach(element => {
        this.allTransThemes.push({ id: element.id, text: element.name });
      });
      console.log('these are the transthemes' + this.allTransThemes);
    }, (error) => console.warn('Error in getting themes' + error)
    );

    /* Getting course by course Id */
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        this.courseDetails = this.courseDetails.data;
        this.courseDetails.content_categories.forEach(element => {
          // if (element.type === 'common_transfer_goals') {
          //   console.log('In if when both types are equal');
          //   this.commonTransferGoals = element;
          //   this.categoryId = this.commonTransferGoals.id;
          //   console.log('these are the common understandings' + this.commonTransferGoals);

          //   /* find Checked commin content */
          //   this.checked = this.unitData.unit_contents[this.categoryId].content_categories.content_values;
          //   this.unitService.getUnitSpecificContent(this.courseId, this.unitId, this.categoryId).subscribe((res) => {
          //     this.unitSpecificContents = res;
          //     this.unitSpecificContents = this.unitSpecificContents.response.data.unit_specific_contents;
          //     console.log('unt specific data' + this.unitSpecificContents);
          //   }, (error) => {
          //     console.warn('Error in adding unit content' + error);
          //   });
          // }
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
      'description': this.unitData.trans_disciplinary_theme.description,
      'unit_type_id': this.selectedUnitType,
      'trans_disciplinary_theme_id': this.selectedTransTheme[0].id
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

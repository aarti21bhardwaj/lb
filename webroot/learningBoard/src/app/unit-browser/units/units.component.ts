import { Component, OnInit, ViewContainerRef, TemplateRef, DoCheck } from '@angular/core';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationStart, Event as NavigationEvent } from '@angular/router';
import { CoursesService } from '../../services/foundation/courses/courses.service';
import { UnitsService } from '../../services/foundation/units/units.service';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { AppSettings } from '../../app-settings';
import { LabelSettings } from '../../label-settings';

@Component({
  selector: 'app-units',
  templateUrl: './units.component.html',
  styleUrls: ['./units.component.scss']
})
export class UnitsComponent implements OnInit {
  sections: any = [];
  labelSummative: any;
  labelFormative: string;
  courseDetails: any;
  courseId: any;
  unitId: any;
  modalRef: BsModalRef;
  unitName: any;
  unitDescription: any;
  unitDateRange: any;
  templateId: any;
  savedUnit: any;
  selectedUnit: any;
  disableButton: boolean = false;
  publishedHistory: any;
  copiedUnitName: any;
  copiedUnit: any;
  startDate: any = '';
  endDate: any = '';
  academicYears: any;
  academicYearId: any;
  terms: any;
  termId: any;
  selectTerm: boolean = false;
  units: any = [];
  spinnerEnabled: boolean = false;

  constructor(private route: ActivatedRoute,
    private courseService: CoursesService,
    private unitService: UnitsService,
    private modalService: BsModalService,
    private router: Router,
    public toastr: ToastsManager,
    vcr: ViewContainerRef) { 
    this.toastr.setRootViewContainerRef(vcr);
    this.labelFormative = LabelSettings.FORMATIVE_ASSESSMENT;
    this.labelSummative = LabelSettings.SUMMATIVE_ASSESSMENT;
    }

  ngDoCheck() {
    const cId = this.route.snapshot.paramMap.get('course_id');
    if (cId && this.courseId !== cId) {
      this.courseId = cId;
      this.getUnits();
    }
  }

  ngOnInit() {
    
  }

  unitDateSelected(val) {
    console.log('event');
    console.log(val);
  }


  saveUnit() {
    if (this.unitDateRange) {
      this.startDate = (new Date(this.unitDateRange[0])).toDateString();
      this.endDate = (new Date(this.unitDateRange[1])).toDateString();
    }
    this.unitService.saveUnit(
      this.unitName,
      this.unitDescription,
      this.startDate,
      this.endDate,
      this.templateId,
      this.courseId
    )
      .subscribe((response) => {
        this.savedUnit = response;
        this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/' + this.courseDetails.template.template.slug + '/' + this.savedUnit.response.data.id);
      }, (error) => {
        console.warn(error);
      });
  }

  openModel(template: TemplateRef<any>, type, task = null) {
  
    if (type == 'create') {
      this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
    }
    if (type == 'copy') {
      this.unitId = task.id;
      this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
    }

    if (type == 'delete') {
      this.unitId = task.id;
      this.modalRef = this.modalService.show(template, { class: 'modal-sm' });
    }
  }

  // getAcademicYearsAndTerms() {
  //   this.unitService.getAcademicAndTerms().subscribe((res: any) => {
  //     console.log('academic year');
  //     console.log(res);
  //     this.academicYears = res.academic_years;
  //     this.terms = res.terms;
  //   }, (error) => {
  //     console.warn(error);
  //   });
  // }

  

  copyUnit() {
    this.courseService.copyOldUnit(
      this.courseId, this.unitId, this.copiedUnitName
    )
      .subscribe((response) => {
        this.copiedUnit = response;
        this.copiedUnit = this.copiedUnit.response;
        this.getUnits();
        this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/' +
          this.courseDetails.template.template.slug + '/' + this.copiedUnit.data.id);
      }, (error) => {
        console.warn(error);
      });
  }

  exportAsPdf(unit) {
    let template: any;
    if (unit.template.slug == 'tUbd') {
      template = 'tubd';
    }
    if (unit.template.slug == 'ubd') {
      template = 'ubd';
    }
    if (unit.template.slug == 'pyp') {
      template = 'pyp';
    }
    if (unit.template.slug == 'transfer') {
      template = 'transfer';
    }

    let url = AppSettings.ENVIRONMENT + 'pdf/' + template + 'Template/' + unit.id;
    window.open(url, 'blank');
    console.log('In export Function' + unit.template.name);

  }

  deleteUnit() {
    this.courseService.deleteUnit(
      this.courseId, this.unitId)
      .subscribe((response) => {
        this.toastr.success('Unit deleted!', 'Success!');
        this.getUnits();
      }, (error) => {
        this.toastr.error('Unable to delete unit. Error Message:' + error.message, 'Error!');
        console.warn(error);
      });
  }

  public selected(value: any, field: any): void {
    console.log('Selected value is: ', value);
  }
  public removed(value: any, field: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any, field: any): void {
    if (field === 'teaching') {
      this.router.navigateByUrl('/teaching-hub/' + value.id + '/' + this.courseDetails.id);
    }

    if (field === 'feedback') {
      this.router.navigateByUrl('/feedback/' + value.id);
    }

    if (field === 'reports') {
      this.router.navigateByUrl('/reports/section/' + value.id);
    }
  }

  filterTermsForAcademicYear(value) {
    this.selectTerm = true;
    this.academicYearId = value;
  }

  selectedTerm(termId) {
    this.termId = termId;
  }
  
  getUnits() {
    this.spinnerEnabled = true;
    this.unitService.getAllUnits(
      this.courseId)
      .subscribe((response: any) => {
        this.courseDetails = response.data;
        this.templateId = this.courseDetails.template.id;
        this.spinnerEnabled = false
      }, (error) => {
        this.spinnerEnabled = false;
        console.warn(error);
      });
  }


}

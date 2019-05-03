import { Component, OnInit, TemplateRef, ViewContainerRef } from '@angular/core';
import { UnitsService } from './../../../../../../services/foundation/units/units.service';
import { CoursesService } from './../../../../../../services/foundation/courses/courses.service';
import { ActivatedRoute, RouterModule, Routes, Router } from '@angular/router';
import { AppSettings } from '../../../../../../app-settings';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';

@Component({
  selector: 'app-archived-units-component',
  templateUrl: './archived-units-component.component.html',
  styleUrls: ['./archived-units-component.component.scss']
})
export class ArchivedUnitsComponentComponent implements OnInit {
  courseId: any;
  termId: any;
  courseDetails: any
  modalRef: BsModalRef;
  unitId: any;
  copiedUnitName: any;
  copiedUnit: any;
  sections: any = []
  templateId: any

  constructor(private unitService: UnitsService,
    public courseService: CoursesService, private acivatedRoute: ActivatedRoute,
    private router: Router, private modalService: BsModalService, public toastr: ToastsManager,
    vcr: ViewContainerRef) { }

  ngOnInit() {
    this.acivatedRoute.params.subscribe(res => {
      this.courseId = res.course_id
      this.termId = res.term_id;

        this.courseService.archiveUnits(
        this.courseId, this.termId).subscribe((response : any) => {
        console.log('response');
        console.log(response);
        this.courseDetails = response.data;
        
      }, (error) => {
              console.warn(error);
      });
    });

  }

  exportAsPdf(unit) {
    // this.courseDetails.units.forEach(element => {
    //   if (unit.id == element.id) {
    //     window.open(element., 'blank');
    //   }
    // });

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

  openModel(template: TemplateRef<any>, type, task = null) {
    // if (task != null) {
    //   this.unitId = task.id;
    // }
    if (type == 'copy') {
      this.unitId = task.id;
      this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
    }

  }

  copyUnit() {
    this.courseService.copyOldUnit(
      this.courseId, this.unitId, this.copiedUnitName
    )
      .subscribe((response) => {
        this.getCourseDetails();
        this.copiedUnit = response;
        this.copiedUnit = this.copiedUnit.response;
        this.toastr.success('Unit Copied!', 'Success!');
      }, (error) => {
        this.toastr.error('Unable to copy unit. Error Message:' + error.message, 'Error!');
        console.warn(error);
      });
  }

  getCourseDetails() {

    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        if (this.courseDetails != null) {
          this.courseDetails = this.courseDetails.data;
          this.sections = [];
          this.courseDetails.sections.forEach(element => {
            this.sections.push({ id: element.id, text: element.name });
          });
          console.log('section');
          console.log(this.sections);
          this.courseService.contentCategories = this.courseDetails.content_categories;

          // setting templateId from the data we get above. For creaing new unit.
          this.templateId = this.courseDetails.template.id;
          this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/' +
            this.courseDetails.template.template.slug + '/' + this.copiedUnit.data.id);
        }
      }, (error) => {
        console.warn(error);
      });
  }

}

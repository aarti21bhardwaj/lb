import { Component, OnInit,ViewContainerRef, TemplateRef} from '@angular/core';
import { RouterModule, Routes, ActivatedRoute, Router, NavigationStart, Event as NavigationEvent } from '@angular/router';
import { ViewChild, ElementRef, AfterViewInit } from '@angular/core'
import {Course} from '../../../course';
import { ModalModule } from 'ngx-bootstrap/modal';
import { CoursesService } from '../../../services/foundation/courses/courses.service';
import { UnitsService } from '../../../services/foundation/units/units.service';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { AppSettings } from '../../../app-settings';
import { LabelSettings } from '../../../label-settings';
@Component({
  selector: 'app-courses',
  templateUrl: './courses.component.html',
  styleUrls: ['./courses.component.css']
})
export class CoursesComponent implements OnInit {

  sections: any = [];
  labelSummative: any;
  labelFormative: string;
  courseDetails: any;
  courseId: any;
  unitId: any;
  modalRef: BsModalRef;
  unitName: any;
  unitDescription: any;
  unitDateRange:any;
  templateId:any;
  savedUnit:any;
  selectedUnit:any;
  disableButton: boolean = false;
  publishedHistory:any;
  copiedUnitName: any;
  copiedUnit: any;
  startDate:any='';
  endDate:any='';
  academicYears : any;
  academicYearId : any;
  terms : any;
  termId : any;
  selectTerm : boolean = false;
  constructor(
    private route: ActivatedRoute,
    private courseService: CoursesService,
    private unitService: UnitsService,
    private modalService: BsModalService,
    private router: Router,
    public toastr: ToastsManager,
    vcr: ViewContainerRef
  ) {
    this.toastr.setRootViewContainerRef(vcr);
    this.labelFormative = LabelSettings.FORMATIVE_ASSESSMENT;
    this.labelSummative = LabelSettings.SUMMATIVE_ASSESSMENT;
    // this.courseService.getUserDetail().subscribe(res => this.userDetail);
  }

  ngDoCheck(){
    const cId = this.route.snapshot.paramMap.get('course_id');
    if (cId && this.courseId !== cId) {
      this.courseId = cId;
      this.getCourseDetails();
    }
  }

  getCourseDetails() {
    
    this.courseService.getCourse(this.courseId)
      .subscribe((response) => {
        this.courseDetails = response;
        if(this.courseDetails != null){
          this.courseDetails = this.courseDetails.data;
          this.sections=[];
          this.courseDetails.sections.forEach(element => {
            this.sections.push({id: element.id, text: element.name});
          });
          console.log('section');
          console.log(this.sections);
          this.courseService.contentCategories = this.courseDetails.content_categories;
  
          // setting templateId from the data we get above. For creaing new unit.
          this.templateId = this.courseDetails.template.id;
        }
      }, (error) => {
        console.warn(error);
      });
  }

  unitDateSelected(val) {
    console.log('event');
    console.log(val);
  }


  saveUnit() {
    if(this.unitDateRange){
      this.startDate=(new Date(this.unitDateRange[0])).toDateString();
      this.endDate=(new Date(this.unitDateRange[1])).toDateString();
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

    ngOnInit() {
    }

    openModel(template: TemplateRef<any>, type ,task = null){
      // if (task != null) {
      //   this.unitId = task.id;
      // }
      if (type == 'create') {
        this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
      }
      if (type == 'publish'){
        this.selectedUnit = task;
        this.unitId = task.id;
        this.getPublishHistory(task.id,'unit');
        this.modalRef = this.modalService.show(template, { class: 'modal-lg' }); 
      }
      if (type == 'copy') {
        this.unitId = task.id;
        this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
      }

      if(type == 'delete') {
        this.unitId = task.id;
        // Swal({
        //   title: "Are you sure you want to delete '"+task.name+"'?",
        //   text: "",
        //   type: "question",
        //   showCancelButton: true,
        //   confirmButtonColor: "#DD6B55",
        //   confirmButtonText: "Yes",
        //   // closeOnConfirm: true
        // }).then((result) => {
          this.modalRef = this.modalService.show(template, { class: 'modal-sm' });  
        // })
    
        
      }

      if (type == 'archive') {
        this.getAcademicYearsAndTerms(template);
      }
    }

    getAcademicYearsAndTerms(template){
      this.unitService.getAcademicAndTerms().subscribe((res : any) => {
          console.log('academic year');
          console.log(res);
          this.academicYears = res.academic_years;
          this.terms =  res.terms;
          this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
        }, (error) => {
                  console.warn(error);
        });
    }

    getPublishHistory(objectId, objectType) {
      this.publishedHistory = null;
      this.courseService.getPublishHistory(objectId, objectType)
                  .subscribe((res) => {
                          this.publishedHistory = res;
                          this.publishedHistory = this.publishedHistory.data;
                  }, (error) => {
                            console.warn(error);
                });
    }
    publishTask(courseId, sectionId, selectedDateRangeValue){
      console.log(selectedDateRangeValue);
      var selectedDateRangeValue = selectedDateRangeValue.split("-");
      selectedDateRangeValue = selectedDateRangeValue.map(function(x){
        return x.trim();
      })
      if (selectedDateRangeValue) {
        this.disableButton = true;
        this.unitService.publishSectionEvent(this.unitId,'unit',sectionId,selectedDateRangeValue[0],
        selectedDateRangeValue[1]).subscribe((res) => {
          this.toastr.success('Unit published!', 'Success!');
          this.disableButton= false;
          this.getPublishHistory(this.unitId,'unit');
        }, (error) => {
          this.toastr.error('Unable to publish unit. Error Message:'+ error.message, 'Error!');
          this.disableButton= false;
        });
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
          this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/units/' + 
            this.courseDetails.template.template.slug + '/' + this.copiedUnit.data.id);
        }, (error) => {
          console.warn(error);
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

  deleteUnit() {
    this.courseService.deleteUnit(
      this.courseId, this.unitId)
      .subscribe((response) => {
        this.getCourseDetails();
        this.toastr.success('Unit deleted!', 'Success!');

      }, (error) => {
        this.toastr.error('Unable to delete unit. Error Message:' + error.message, 'Error!');
        console.warn(error);
      });
  }

  archiveUnit(unit) {
    const data = {
      'is_archived': true
    }
    console.log('this is the data that is going to save' + data);
    this.unitService.editUnits(this.courseId, unit.id, data).subscribe((res) => {
      console.log(res);
      this.getCourseDetails();
      this.toastr.success('Unit is archived!', 'Success!');
    }, (error) => {
      console.warn(error);
      this.toastr.error('Unit could not be archived!', 'Error!');
    });
  }

  unarchiveUnit(unit) {
    const data = {
      'is_archived': false
    }
    console.log('this is the data that is going to save' + data);
    this.unitService.editUnits(this.courseId, unit.id, data).subscribe((res) => {
      console.log(res);
      this.getCourseDetails();
      this.toastr.success('Unit is unarchived!', 'Success!');
    }, (error) => {
      console.warn(error);
      this.toastr.error('Unit could not be unarchived!', 'Error!');
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
      this.router.navigateByUrl('/teaching-hub/' + value.id + '/' + this.courseDetails.id );
    }

    if (field === 'feedback') {
      this.router.navigateByUrl('/feedback/' + value.id);
    }

    if (field === 'reports') {
      this.router.navigateByUrl('/reports/section/' + value.id);
    }
  }

  filterTermsForAcademicYear(value){
    this.selectTerm = true;
    this.academicYearId = value;
  }

  selectedTerm(termId){
    this.termId = termId;
  }

  getAllArchiveUnits(){
    this.router.navigateByUrl('/foundation/courses/' + this.courseId + '/archived-units/' + this.termId);
    // this.courseId = 52;
    // this.termId = 27;
  //   this.courseService.archiveUnits(
  //     this.courseId, this.termId).subscribe((response : any) => {
  //       console.log('response');
  //       console.log(response);
  //       this.courseService.archivedUnits = response.data;
  //       console.log(this.courseService.archivedUnits);
  //       if(this.courseService.archivedUnits){
  //         this.router.navigateByUrl('/foundation/courses/'+this.courseId+'/archived-units/'+ this.termId);
  //       }
  //     }, (error) => {
  //             console.warn(error);
  //     });
  }

}

import { Component, OnInit, ViewContainerRef, TemplateRef, DoCheck } from '@angular/core';
import { Router } from '@angular/router';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { TeacherEvidencesService } from '../../services/teacher-evidences/teacher-evidences.service';
import { UsersService } from '../../services/users/users.service';

@Component({
  selector: 'app-add-teacher-evidences',
  templateUrl: './add-teacher-evidences.component.html',
  styleUrls: ['./add-teacher-evidences.component.scss']
})
export class AddTeacherEvidencesComponent implements OnInit {

  evidenceContext: any;
  fileToUpload: File = null; // Placeholder variable for the resource file
  // file_path: any;
  // file_name: any;
  fileType: any;
  generalFileSelected: number;
  modalRef: BsModalRef;
  contextData: any = [];

  reflectionFileToUpload: File = null; // Placeholder variable for the resource file
  // reflection_file_path: any;
  // reflection_file_name: any;
  reflectionFileSelected: number;
  courseIds: any;
  evidence: any = {};
  teacherInfo: any;
  savedData: any;
  spinnerEnabled = false;
  multiCourseModel: any = {};
  courses: any = {};
  courseId: any =  [];

  constructor(
    private evidenceService: TeacherEvidencesService,
    public toastr: ToastsManager,
    private modalService: BsModalService,
    vcr: ViewContainerRef,
    public user: UsersService,
    private router: Router,
  ) { }

  ngOnInit() {
    this.evidence.digital_tool_used = 0;
    this.user.getUserDetails()
      .subscribe((response) => {
        this.teacherInfo = response;
        this.teacherInfo = this.teacherInfo.data;
        this.evidence.teacher_id = this.teacherInfo.userId;
        
        if (this.teacherInfo.courseData) {
          this.courseIds = [];
          this.teacherInfo.courseData.forEach(element => {
            this.courseIds.push(element.id);
          });
        }
        console.log('course data array');
        console.log(this.courseIds);
      }, (error) => {
        console.log(error);
      });
  }

  linkchecked(type) {
    if (type == 'link') {
      this.evidence.digital_tool_used = 1;
    }
    if (type == 'file') {
      this.evidence.digital_tool_used = 0;
    }
  }
  mapSectionCourses() {
    this.teacherInfo.courseData.forEach(element => {
      element.sections.forEach( ele => {
        this.courses[ele.id] = element;
      });
    });
  }

  handleFileInput(fileType, files: FileList) {

    if (fileType == 'general') {
      this.fileToUpload = files.item(0);
      this.uploadFile('general');
    }

    if (fileType == 'reflection') {
      this.reflectionFileToUpload = files.item(0);
      this.uploadFile('reflection');
    }
  }

  uploadFile(fileType) {

    let file: any;
    if (fileType == 'reflection') {
      file = this.reflectionFileToUpload;
    } else {
      file = this.fileToUpload;
    }

    this.evidenceService.postFile(file).subscribe(data => {
      if (fileType == 'general') {
        this.evidence.file_path = data; // taking data in this.file_path temporarily;
        this.evidence.file_name = this.evidence.file_path.data.file_name;
        this.evidence.file_path = this.evidence.file_path.data.file_path;
      }

      if (fileType == 'reflection') {
        this.evidence.reflection_file_path = data; // taking data in this.file_path temporarily
        this.evidence.reflection_file_name = this.evidence.reflection_file_path.data.file_name;
        this.evidence.reflection_file_path = this.evidence.reflection_file_path.data.file_path;
      }
    }, error => {
      console.log(error);
    });
  }

  handleCourseCheckboxes(id) {

    if (this.multiCourseModel[id]) {
      delete this.multiCourseModel[id];
    } else {
      this.multiCourseModel[id] = true;
    }

  }

  addEvidence() {

    this.spinnerEnabled = true;

    let data = this.evidence;

    // data.teacher_evidence_contexts = [];
    // this.evidenceContext.forEach(element => {
    //   data.teacher_evidence_contexts.push({ context_id: element.id })
    // });
    // data.teacher_evidence_sections = [];
    // if (this.multiCourseModel && Object.keys(this.multiCourseModel).length !== 0) {
    //   for (const key in this.multiCourseModel) {
    //     if (this.multiCourseModel.hasOwnProperty(key)) {
    //       data.teacher_evidence_sections.push({ section_id: key });
    //     }
    //   }
    // } else {
    //   data.teacher_evidence_sections.push({ section_id: this.evidence.course });
    // }

    this.evidenceService.saveEvidence(data)
      .subscribe((response) => {
        this.spinnerEnabled = false;
        this.savedData = response;
        this.savedData = this.savedData.data;
        console.log('after save');
        console.log(this.courses);
        console.log(data);
        console.log(this.evidence.course);

        // if (this.evidence.course) {
        //   this.courseId = this.courses[this.evidence.course].sections[0].course_id
        // }

        // if (this.multiCourseModel) {
        //   this.courseId = this.courses[data.teacher_evidence_sections[0].section_id].sections[0].course_id
        // }

      }, (error) => {
        console.log(error);
      });
  }

  goBack() {
    this.router.navigate(['/teacher-evidences']);
  }

  openModal(template: TemplateRef<any>, quickOpen) {
    if (quickOpen) {
      this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
    } else {
      setTimeout(() => {
        this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
      }, 2000);
    }
  }

  submitDisableCheck() {

    // if (Object.keys(this.multiCourseModel).length === 0 && (!this.evidence.course || typeof this.evidence.course === 'undefined')) {
    //   return true;
    // }
    if (!this.evidence.title) {
      return true;
    }
    if (!this.evidence.description) {
      return true;
    }
    return false;
  }

  // public selected(value: any): void {
  //   console.log('Selected value is: ', value);
  //   // this.evidenceContext = value;
  // }
  // public removed(value: any): void {
  //   console.log('Removed value is: ', value);
  // }
  // public refreshValue(value: any): void {
  //   this.evidenceContext = value;
  //   console.log('evidence context value' + this.evidenceContext);
  // }
}

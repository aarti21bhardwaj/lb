import { Component, OnInit, ViewContainerRef, TemplateRef } from '@angular/core';
import { TeacherEvidencesService } from '../../services/teacher-evidences/teacher-evidences.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { NgForm } from '@angular/forms';
import { UsersService } from '../../services/users/users.service';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';

@Component({
  selector: 'app-edit-teacher-evidences',
  templateUrl: './edit-teacher-evidences.component.html',
  styleUrls: ['./edit-teacher-evidences.component.scss']
})

export class EditTeacherEvidencesComponent implements OnInit {

  courseIds: any;
  selectedContexts: any = [];
  evidenceId: any;
  courseId: any;
  evidence: any = {};
  teacherInfo: any;
  courses: any;
  fileToUpload: File = null; // Placeholder variable for the resource file
  reflectionFileToUpload: File = null; // Placeholder variable for the resource file
  modalRef: BsModalRef;
  spinnerEnabled = false;
  multiCourseModel: any = {};
  newReflectionFile = false;
  newFile = false;
  fileType: any;
  contextData: any = [];

  constructor(
    private evidenceService: TeacherEvidencesService,
    private route: ActivatedRoute,
    public toastr: ToastsManager,
    private modalService: BsModalService,
    vcr: ViewContainerRef,
    public user: UsersService,
    private router: Router,
  ) { }

  ngOnInit() {
    this.evidenceId = this.route.snapshot.paramMap.get('evidence_id');
    this.evidence.digital_tool_used = 0

    this.user.getUserDetails()
      .subscribe((response) => {
        this.teacherInfo = response;
        this.teacherInfo = this.teacherInfo.data;
        // if (this.teacherInfo.contextData) {
        //   this.teacherInfo.contextData.forEach(element => {
        //     this.contextData.push({ id: element.id, text: element.name });
        //   });
        // }
        if (this.teacherInfo.courseData) {
          this.courseIds = [];
          this.teacherInfo.courseData.forEach(element => {
            this.courseIds.push(element.id);
          });
        }
        this.evidence.teacher_id = this.teacherInfo.userId;
        this.getEvidence();

      }, (error) => {
        console.log(error);
      });
  }

  getEvidence() {
    this.evidenceService.getEvidence(this.evidenceId)
      .subscribe((response) => {
        let res: any = response;
        res = res.data;
        // this.courseId = res.teacher_evidence_sections[0].section.course_id;
        this.evidence.id = res.id
        this.evidence.title = res.title;
        this.evidence.description = res.description;
        this.evidence.url = res.url;


        // if (res.teacher_evidence_contexts) {
        //   this.evidence.contexts = [];
        //   res.teacher_evidence_contexts.forEach(element => {
        //     this.evidence.contexts.push({ id: element.context.id, text: element.context.name });
        //     console.log('selected context data');
        //     console.log(this.selectedContexts);
        //   });
        // }

        if (res.file_name) {
          this.evidence.file_name = res.file_name;
        }

        if (res.file_name != null) {
          this.fileType = 'file';
        }

        if (res.url != null) {
          this.fileType = 'url';
        }
        if (res.reflection_description) {
          this.evidence.reflection_description = res.reflection_description;
        }

        if (res.reflection_file_name) {
          this.evidence.reflection_file_name = res.reflection_file_name;
        }

        // if (res.teacher_evidence_sections) {
        //   if (this.teacherInfo.campusSettings['Multiple Courses per Evidence'].value == 1) {
        //     console.log('in if');
        //     console.log(res.teacher_evidence_sections);
        //     this.mapEvidenceCourses(res);
        //   } else {
        //     console.log('in else');
        //     console.log(res.teacher_evidence_sections);
        //     this.evidence.course = res.teacher_evidence_sections[0].section_id;
        //   }
        // }
      }, (error) => {
        console.log(error);
      });
  }

  linkchecked(type) {
    if (type == 'url') {
      this.evidence.digital_tool_used = 1;
      this.evidence.file_name = null;
      this.evidence.file_path = null;
    }
    if (type == 'file') {
      this.evidence.digital_tool_used = 0;
    }
  }

  // mapEvidenceCourses(evidence) {
  //   evidence.teacher_evidence_sections.forEach(element => {
  //     this.multiCourseModel[element.section_id] = true;
  //   });
  // }

  handleFileInput(fileType, files: FileList) {

    if (fileType == 'general') {
      this.fileToUpload = files.item(0);
      this.uploadFile('general');
      this.newFile = true;
    }

    if (fileType == 'reflection') {
      this.reflectionFileToUpload = files.item(0);
      this.uploadFile('reflection');
      this.newReflectionFile = true;
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
        this.evidence.url = null;
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

  // handleCourseCheckboxes(id) {

  //   if (this.multiCourseModel[id]) {
  //     delete this.multiCourseModel[id];
  //   } else {
  //     this.multiCourseModel[id] = true;
  //   }

  // }

  saveEvidence() {

    this.spinnerEnabled = true;

    let data = this.evidence;

    // data.evidence_contexts = [];
    // this.evidence.contexts.forEach(element => {
    //   data.evidence_contexts.push({ context_id: element.id })
    // });
    // data.evidence_sections = [];
    // if (this.multiCourseModel && Object.keys(this.multiCourseModel).length !== 0) {
    //   for (const key in this.multiCourseModel) {
    //     if (this.multiCourseModel.hasOwnProperty(key)) {
    //       data.evidence_sections.push({ section_id: key });
    //     }
    //   }
    // } else {
    //   data.evidence_sections.push({ section_id: this.evidence.course });
    // }

    this.evidenceService.editEvidence(data.id, data)
      .subscribe((response) => {
        this.spinnerEnabled = false;

      }, (error) => {
        console.log(error);
      });
  }

  openModal(template: TemplateRef<any>) {
    this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
  }

  goBack() {
    this.router.navigate(['/teacher-evidences']);
  }

  // public selected(value: any): void {
  //   console.log('Selected value is: ', value);
  //   // this.evidenceContext = value;
  // }
  // public removed(value: any): void {
  //   console.log('Removed value is: ', value);
  // }
  // public refreshValue(value: any): void {
  //   this.evidence.contexts = value;
  //   console.log('evidence context value' + this.selectedContexts);
  // }

}

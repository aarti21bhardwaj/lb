import { Component, OnInit, ViewContainerRef, TemplateRef, DoCheck } from '@angular/core';
import { EvidencesService } from '../../../services/evidences/evidences.service';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { NgForm } from '@angular/forms';
import { UsersService } from '../../../services/users/users.service';
import { Router } from '@angular/router';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';

@Component({
  selector: 'app-evidence-add',
  templateUrl: './evidence-add.component.html',
  styleUrls: ['./evidence-add.component.scss']
})
export class EvidenceAddComponent implements OnInit {

  courseIds: any;
  evidenceContext: any;
  fileToUpload: File = null; // Placeholder variable for the resource file
  // file_path: any;
  // file_name: any;
  fileType: any;
  generalFileSelected: number;
  modalRef: BsModalRef;
  contextData: any = [];
  selectedStrategies: any = [];

  reflectionFileToUpload: File = null; // Placeholder variable for the resource file
  // reflection_file_path: any;
  // reflection_file_name: any;
  reflectionFileSelected: number;

  evidence: any = {};
  studentInfo: any;
  savedData: any;
  spinnerEnabled = false;
  multiCourseModel: any = {};
  courses: any = {};
  courseId: any;
  courseDetails: any;
  digitalStrategyId: any
  digitalStrategies: any
  isDigitalToolUsed: boolean;

  constructor(
    private evidenceService: EvidencesService,
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
        this.studentInfo = response;
        this.studentInfo = this.studentInfo.data;

        this.evidence.student_id = this.studentInfo.userId;
        if (this.studentInfo.contextData) {
          this.studentInfo.contextData.forEach(element => {
            this.contextData.push({ id: element.id, text: element.name });
          });
        }
        this.mapSectionCourses();
      }, (error) => {
        console.log(error);
      });
   
    this.evidenceService.getDigitalStrategies('digital_strategies')
      .subscribe((response) => {
        this.courseDetails = response;
        this.digitalStrategies = this.courseDetails.response.data
        this.digitalStrategyId = this.courseDetails.response.data.id;

      }, (error) => {
        console.warn(error);
      });
  }

  linkchecked(type) {
    
  }
  mapSectionCourses() {
    this.studentInfo.courseData.forEach(element => {
      this.courses[element._matchingData.Sections.id] = element;
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

    console.log('In add evidence');
    this.spinnerEnabled = true;

    let data = this.evidence;

    data.evidence_contexts = [];
    this.evidenceContext.forEach(element => {
      data.evidence_contexts.push({ context_id: element.id })
    });
    data.evidence_sections = [];
    if (this.multiCourseModel && Object.keys(this.multiCourseModel).length !== 0) {
      for (const key in this.multiCourseModel) {
        if (this.multiCourseModel.hasOwnProperty(key)) {
          data.evidence_sections.push({section_id: key});
        }
      }
    } else {
      data.evidence_sections.push({ section_id: this.evidence.course });
    }

    data.evidence_contents = [];
    if (this.selectedStrategies && this.selectedStrategies.length >0) {
      this.selectedStrategies.forEach(element => {
        data.evidence_contents.push({ content_category_id: this.digitalStrategyId, content_value_id: element })
      });
    }

    this.evidenceService.saveEvidence(data)
      .subscribe((response) => {
        this.spinnerEnabled = false;
        this.savedData = response;
        this.savedData = this.savedData.data;

        this.courseIds = [];
        if (this.evidence.course) {
          this.courseId = this.courses[this.evidence.course]._matchingData.Sections.course_id;
          this.courseIds.push(this.courses[this.evidence.course]._matchingData.Sections.course_id);
        }

        if (this.multiCourseModel) {
          this.courseId = this.courses[data.evidence_sections[0].section_id]._matchingData.Sections.course_id;
          this.studentInfo.courseData.forEach(element => {
            this.courseIds.push(element.id);
          });
        }

      }, (error) => {
        console.log(error);
      });
  }

  goBack() {
    this.router.navigate(['/evidences']);
  }

  openModal(template: TemplateRef<any>, quickOpen) {
    if (quickOpen){
      this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
    } else {
      setTimeout(() => {
        this.modalRef = this.modalService.show(template, { class: 'modal-lg' });
      }, 2000);
    }
  }

  submitDisableCheck() {

    if (Object.keys(this.multiCourseModel).length === 0 && (!this.evidence.course || typeof this.evidence.course === 'undefined')) {
      return true;
    }
    if (!this.evidence.title) {
      return true;
    }
    if (!this.evidence.description) {
      return true;
    }
    if (!this.evidenceContext) {
      return true;
    }
    return false;
  }

  public selected(value: any): void {
    console.log('Selected value is: ', value);
    // this.evidenceContext = value;
  }
  public removed(value: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any): void {
    this.evidenceContext = value;
    console.log('evidence context value' + this.evidenceContext);
  }

  checkedCommonContent(e, commonContent, type) {
    console.log(' in checked common content');
    console.log(e);
    console.log(commonContent);
    this.selectedStrategies.push(commonContent.id);

  }

  IsStrategyUsed(event) {
    this.isDigitalToolUsed = event
    this.evidence.digital_tool_used = event
    console.log('In strategy function' + this.isDigitalToolUsed);
  }
}

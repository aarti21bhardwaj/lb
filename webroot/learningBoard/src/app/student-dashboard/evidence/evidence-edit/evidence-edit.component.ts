import { Component, OnInit, ViewContainerRef, TemplateRef } from '@angular/core';
import { EvidencesService } from '../../../services/evidences/evidences.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { NgForm } from '@angular/forms';
import { UsersService } from '../../../services/users/users.service';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';

@Component({
  selector: 'app-evidence-edit',
  templateUrl: './evidence-edit.component.html',
  styleUrls: ['./evidence-edit.component.scss']
})
export class EvidenceEditComponent implements OnInit {

  selectedContexts: any = [];
  courseIds: any = [];
  evidenceId: any;
  courseId: any;
  evidence: any = {};
  studentInfo: any;
  courses: any;
  fileToUpload: File = null; // Placeholder variable for the resource file
  reflectionFileToUpload: File = null; // Placeholder variable for the resource file
  modalRef: BsModalRef;
  spinnerEnabled = false;
  multiCourseModel: any = {};
  newReflectionFile = false;
  newFile = false;
  fileType : any;
  contextData: any = [];
  courseDetails: any;
  digitalStrategyId: any
  digitalStrategies: any
  isDigitalToolUsed: boolean;

  constructor(
    private evidenceService: EvidencesService,
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
        this.studentInfo = response;
        this.studentInfo = this.studentInfo.data;
        if (this.studentInfo.contextData) {
          this.studentInfo.contextData.forEach(element => {
            this.contextData.push({ id: element.id, text: element.name });
          });
        }
        this.evidence.student_id = this.studentInfo.userId;
        this.getEvidence();
        
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

  getEvidence() {
    this.evidenceService.getEvidence(this.evidenceId)
      .subscribe((response) => {
        let res: any = response;
        res = res.data;
        this.courseId = res.evidence_sections[0].section.course_id;
        this.evidence.id = res.id
        this.evidence.title = res.title;
        this.evidence.description = res.description;
        this.evidence.url = res.url;
        this.isDigitalToolUsed = res.digital_tool_used;
        this.evidence.digital_tool_used = res.digital_tool_used;

        if (res.evidence_contexts) {
          this.evidence.contexts = [];
          res.evidence_contexts.forEach(element => {
            this.evidence.contexts.push({ id: element.context.id, text: element.context.name});
            console.log('selected context data');
            console.log(this.selectedContexts);
          });
        }

        if (res.evidence_sections.length > 0) {
          this.courseIds = [];
          res.evidence_sections.forEach(element => {
            this.courseIds.push(element.section.course_id);
          });
        }
        
        if (res.file_name) {
          this.evidence.file_name = res.file_name;
        }

        if(res.file_name != null) {
          this.fileType = 'file';
        }

        if (res.url != null) {
          this.fileType = 'url';
        }
        if (res.reflection_description){
          this.evidence.reflection_description = res.reflection_description;
        }

        if (res.reflection_file_name) {
          this.evidence.reflection_file_name = res.reflection_file_name;
        }

        if (res.evidence_sections) {
          if (this.studentInfo.campusSettings['Multiple Courses per Evidence'].value == 1) {
            this.mapEvidenceCourses(res);
          }else {
            this.evidence.course = res.evidence_sections[0].section_id;
          }
        }
      }, (error) => {
        console.log(error);
      });
  }

  linkchecked(type) {
    if (type == 'url') {
      // this.evidence.digital_tool_used = 1;
      this.evidence.file_name = null;
      this.evidence.file_path = null;
    }

    if (type == 'file') {
      this.evidence.url = null;
    }
    // if (type == 'file') {
    //   this.evidence.digital_tool_used = 0;
    // }
  }

  mapEvidenceCourses(evidence) {
    evidence.evidence_sections.forEach(element => {
      this.multiCourseModel[element.section_id] = true;
    });
  }

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

  handleCourseCheckboxes(id) {

    if (this.multiCourseModel[id]) {
      delete this.multiCourseModel[id];
    } else {
      this.multiCourseModel[id] = true;
    }

  }

  saveEvidence(event: any = null) {
    console.log('In save evidence function');
    if(event && event != null) {
      this.evidence.digital_tool_used = event;
      this.isDigitalToolUsed = event;
    }
    console.log(this.evidence);

    this.spinnerEnabled = true;

    let data = this.evidence;

    data.evidence_contexts = [];
    this.evidence.contexts.forEach(element => {
      data.evidence_contexts.push({ context_id: element.id })
    });
    data.evidence_sections = [];
    if (this.multiCourseModel && Object.keys(this.multiCourseModel).length !== 0) {
      for (const key in this.multiCourseModel) {
        if (this.multiCourseModel.hasOwnProperty(key)) {
          data.evidence_sections.push({ section_id: key });
        }
      }
    } else {
      data.evidence_sections.push({ section_id: this.evidence.course });
    }

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
    this.router.navigate(['/evidences']);
  }

  public selected(value: any): void {
    console.log('Selected value is: ', value);
    // this.evidenceContext = value;
  }
  public removed(value: any): void {
    console.log('Removed value is: ', value);
  }
  public refreshValue(value: any): void {
    this.evidence.contexts = value;
    console.log('evidence context value' + this.selectedContexts);
  }

  UpdateStrategyId(event: any) {
    console.log('in update strategy function'+ event);
    this.evidence.digital_tool_used = event;
    this.isDigitalToolUsed = event;
  }

}

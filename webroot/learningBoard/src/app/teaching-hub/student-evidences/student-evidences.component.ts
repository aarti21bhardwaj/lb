import { Component, OnInit, ViewContainerRef, TemplateRef  } from '@angular/core';
import { EvidencesService } from '../../services/evidences/evidences.service';
import { ActivatedRoute } from '@angular/router';
import { AppSettings } from '../../app-settings';
import { ToastsManager } from 'ng2-toastr/ng2-toastr';
import { BsModalService } from 'ngx-bootstrap/modal';
import { BsModalRef } from 'ngx-bootstrap/modal/bs-modal-ref.service';

@Component({
  selector: 'app-student-evidences',
  templateUrl: './student-evidences.component.html',
  styleUrls: ['./student-evidences.component.scss']
})
export class StudentEvidencesComponent implements OnInit {

  evidences: any;
  evnEndpoint: any;
  filter_elment: any;
  studentId: any;
  modalRef: BsModalRef;
  listView : boolean = false;
  impactScale: any;
  impactCategories: any;
  impacts: any;
  evidence: any;
  impactScaleValueNameMap: any = {};
  impactScaleValueCount: number;
  defaultImpactScaleValue: any;


  constructor(
    private evidenceService: EvidencesService,
    private route: ActivatedRoute,
    public toastr: ToastsManager,
    vcr: ViewContainerRef,
    private modalService: BsModalService,
  ) {
    this.toastr.setRootViewContainerRef(vcr);
  }

  ngOnInit() {

    this.evnEndpoint = AppSettings.ENVIRONMENT;

    this.route.params.subscribe(res => {
      console.log(res);
      this.studentId = res.student_id;
      this.listEvidences();
    });
  }

  listEvidences() {
    this.evidenceService.listEvidences(this.studentId)
      .subscribe((response) => {
        this.evidences = response;
        this.evidences = this.evidences.data;
      }, (error) => {
        console.log(error);
      });
  }

  viewEvidence(id) {
    this.evidenceService.getEvidence(id, this.studentId)
      .subscribe((response : any) => {
        console.log('hello');
        console.log(response);
        this.evidence = response.data;
      }, (error) => {
        console.log(error);
      });
  }

  

  openViewModal(template: TemplateRef<any>, evidenceId) {
    this.viewEvidence(evidenceId);
    this.modalRef = this.modalService.show(template);

  }

  changeView(view) {
    if (view == 'list') {
      this.listView = true;
    } else {
      this.listView = false;
    }
  }

}

import { Component, OnInit } from '@angular/core';
import { EvidencesService } from '../../services/evidences/evidences.service';
import { AppSettings } from '../../app-settings';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-student-evidence',
  templateUrl: './student-evidence.component.html',
  styleUrls: ['./student-evidence.component.scss']
})
export class StudentEvidenceComponent implements OnInit {

  addActive: boolean;
  evnEndpoint: any;
  impactTitle: string;
  evidences: any;
  constructor(private evidenceService: EvidencesService, private acivatedRoute: ActivatedRoute,) { }

  ngOnInit() {
    this.impactTitle = "Impacts";
    this.evnEndpoint = AppSettings.ENVIRONMENT;
    this.addActive = false;
    this.acivatedRoute.params.subscribe(res => {
      let studentId;
      studentId = res.student_id;
      this.listEvidences(studentId);
    })
  }

  listEvidences(studentId) {
    this.evidenceService.listEvidences(studentId)
      .subscribe((response) => {
        this.evidences = response;
        this.evidences = this.evidences.data;
      }, (error) => {
        console.log(error);
      });
  }

}

import { Component, OnInit } from '@angular/core';
import { ResourcesReflectionsService } from '../../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'
import { ActivatedRoute } from '@angular/router';
import { ResourcesDirectiveComponent } from '../../../shared/resources-directive/resources-directive.component';
import { TransferComponent } from '../../../transfer/transfer.component';

@Component({
  selector: 'app-transfer-resources-reflections',
  templateUrl: './transfer-resources-reflections.component.html',
  styleUrls: ['./transfer-resources-reflections.component.scss']
})
export class TransferResourcesReflectionsComponent implements OnInit {

  courseId: number;
  unitId: number;

  constructor(
    private rnRService: ResourcesReflectionsService,
    private acivatedRoute: ActivatedRoute,
    private parent: TransferComponent
  ) {
    // this.resource = false; // initialize the resource form. keeps it hidden
    // this.reflection = false; // initialize the reflection form. keeps it hidden
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
  }

  ngOnInit() {
  }

}

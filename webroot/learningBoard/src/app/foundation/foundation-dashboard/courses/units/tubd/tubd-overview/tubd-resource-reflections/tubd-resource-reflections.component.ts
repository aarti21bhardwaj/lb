import { Component, OnInit } from '@angular/core';
import { ResourcesReflectionsService } from '../../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'
import { ActivatedRoute } from '@angular/router';
import { ResourcesDirectiveComponent } from '../../../shared/resources-directive/resources-directive.component';
import { TubdComponent } from '../../../tubd/tubd.component';


@Component({
  selector: 'app-tubd-resource-reflections',
  templateUrl: './tubd-resource-reflections.component.html',
  styleUrls: ['./tubd-resource-reflections.component.scss']
})
export class TubdResourceReflectionsComponent implements OnInit {
  courseId: number;
  unitId: number;

  constructor(
    private rnRService: ResourcesReflectionsService,
    private acivatedRoute: ActivatedRoute,
    private parent: TubdComponent
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

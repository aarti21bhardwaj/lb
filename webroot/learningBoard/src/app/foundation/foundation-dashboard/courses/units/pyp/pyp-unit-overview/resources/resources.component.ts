import { Component, OnInit } from '@angular/core';
import { ResourcesReflectionsService } from '../../../../../../../services/foundation/units/resources-reflections/resources-reflections.service'
import { ActivatedRoute } from '@angular/router';
import { ResourcesDirectiveComponent } from '../../../shared/resources-directive/resources-directive.component';
import { PypComponent } from '../../../pyp/pyp.component';

@Component({
  selector: 'app-resources',
  templateUrl: './resources.component.html',
  styleUrls: ['./resources.component.scss']
})
export class ResourcesComponent implements  OnInit {
  courseId: number;
  unitId: number;

  constructor(private rnRService: ResourcesReflectionsService,
    private acivatedRoute: ActivatedRoute, private parent: PypComponent) { 
    this.acivatedRoute.parent.params.subscribe(res => {
      this.unitId = res.unit_id;
      this.courseId = res.course_id;
    });
    parent.isMetaUnitActive = false;
  }

  ngOnInit() {
  }
 
}

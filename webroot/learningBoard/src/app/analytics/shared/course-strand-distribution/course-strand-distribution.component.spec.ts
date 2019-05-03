import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CourseStrandDistributionComponent } from './course-strand-distribution.component';

describe('CourseStrandDistributionComponent', () => {
  let component: CourseStrandDistributionComponent;
  let fixture: ComponentFixture<CourseStrandDistributionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CourseStrandDistributionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CourseStrandDistributionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

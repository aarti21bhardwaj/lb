import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CoursesPolarComponent } from './courses-polar.component';

describe('CoursesPolarComponent', () => {
  let component: CoursesPolarComponent;
  let fixture: ComponentFixture<CoursesPolarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CoursesPolarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CoursesPolarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

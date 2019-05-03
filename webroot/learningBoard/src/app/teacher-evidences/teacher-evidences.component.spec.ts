import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TeacherEvidencesComponent } from './teacher-evidences.component';

describe('TeacherEvidencesComponent', () => {
  let component: TeacherEvidencesComponent;
  let fixture: ComponentFixture<TeacherEvidencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TeacherEvidencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TeacherEvidencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

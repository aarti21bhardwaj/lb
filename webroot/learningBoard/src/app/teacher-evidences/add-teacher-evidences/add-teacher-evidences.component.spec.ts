import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddTeacherEvidencesComponent } from './add-teacher-evidences.component';

describe('AddTeacherEvidencesComponent', () => {
  let component: AddTeacherEvidencesComponent;
  let fixture: ComponentFixture<AddTeacherEvidencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddTeacherEvidencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddTeacherEvidencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

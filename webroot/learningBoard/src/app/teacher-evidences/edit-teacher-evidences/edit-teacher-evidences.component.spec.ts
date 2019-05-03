import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditTeacherEvidencesComponent } from './edit-teacher-evidences.component';

describe('EditTeacherEvidencesComponent', () => {
  let component: EditTeacherEvidencesComponent;
  let fixture: ComponentFixture<EditTeacherEvidencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditTeacherEvidencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditTeacherEvidencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

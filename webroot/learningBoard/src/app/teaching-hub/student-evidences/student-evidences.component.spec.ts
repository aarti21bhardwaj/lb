import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentEvidencesComponent } from './student-evidences.component';

describe('StudentEvidencesComponent', () => {
  let component: StudentEvidencesComponent;
  let fixture: ComponentFixture<StudentEvidencesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentEvidencesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentEvidencesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

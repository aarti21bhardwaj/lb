import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentEvidenceComponent } from './student-evidence.component';

describe('StudentEvidenceComponent', () => {
  let component: StudentEvidenceComponent;
  let fixture: ComponentFixture<StudentEvidenceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentEvidenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentEvidenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentCircumplexComponent } from './student-circumplex.component';

describe('StudentCircumplexComponent', () => {
  let component: StudentCircumplexComponent;
  let fixture: ComponentFixture<StudentCircumplexComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentCircumplexComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentCircumplexComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

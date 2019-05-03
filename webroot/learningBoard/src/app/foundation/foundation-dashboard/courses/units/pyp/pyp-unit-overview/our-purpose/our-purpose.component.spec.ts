import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OurPurposeComponent } from './our-purpose.component';

describe('OurPurposeComponent', () => {
  let component: OurPurposeComponent;
  let fixture: ComponentFixture<OurPurposeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OurPurposeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OurPurposeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

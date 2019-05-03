import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitStandardsComponent } from './unit-standards.component';

describe('UnitStandardsComponent', () => {
  let component: UnitStandardsComponent;
  let fixture: ComponentFixture<UnitStandardsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitStandardsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitStandardsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

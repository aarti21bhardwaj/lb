import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CommonContentUnitSummaryComponent } from './common-content-unit-summary.component';

describe('CommonContentUnitSummaryComponent', () => {
  let component: CommonContentUnitSummaryComponent;
  let fixture: ComponentFixture<CommonContentUnitSummaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CommonContentUnitSummaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CommonContentUnitSummaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

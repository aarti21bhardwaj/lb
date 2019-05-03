import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PypUnitOverviewComponent } from './pyp-unit-overview.component';

describe('PypUnitOverviewComponent', () => {
  let component: PypUnitOverviewComponent;
  let fixture: ComponentFixture<PypUnitOverviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PypUnitOverviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PypUnitOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

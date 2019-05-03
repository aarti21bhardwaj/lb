import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UbdUnitOverviewComponent } from './ubd-unit-overview.component';

describe('UbdUnitOverviewComponent', () => {
  let component: UbdUnitOverviewComponent;
  let fixture: ComponentFixture<UbdUnitOverviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UbdUnitOverviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UbdUnitOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});

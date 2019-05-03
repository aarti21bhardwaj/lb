import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitImpactsComponent } from './unit-impacts.component';

describe('UnitImpactsComponent', () => {
  let component: UnitImpactsComponent;
  let fixture: ComponentFixture<UnitImpactsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitImpactsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitImpactsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

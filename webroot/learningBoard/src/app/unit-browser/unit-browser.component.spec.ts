import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitBrowserComponent } from './unit-browser.component';

describe('UnitBrowserComponent', () => {
  let component: UnitBrowserComponent;
  let fixture: ComponentFixture<UnitBrowserComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitBrowserComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitBrowserComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnitcontentComponent } from './unitcontent.component';

describe('UnitcontentComponent', () => {
  let component: UnitcontentComponent;
  let fixture: ComponentFixture<UnitcontentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnitcontentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnitcontentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

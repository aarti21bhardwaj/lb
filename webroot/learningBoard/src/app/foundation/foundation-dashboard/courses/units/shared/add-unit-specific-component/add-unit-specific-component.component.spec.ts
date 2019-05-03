import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddUnitSpecificComponentComponent } from './add-unit-specific-component.component';

describe('AddUnitSpecificComponentComponent', () => {
  let component: AddUnitSpecificComponentComponent;
  let fixture: ComponentFixture<AddUnitSpecificComponentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddUnitSpecificComponentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddUnitSpecificComponentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

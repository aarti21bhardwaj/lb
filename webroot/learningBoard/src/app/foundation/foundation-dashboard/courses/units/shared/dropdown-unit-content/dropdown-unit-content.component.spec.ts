import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DropdownUnitContentComponent } from './dropdown-unit-content.component';

describe('DropdownUnitContentComponent', () => {
  let component: DropdownUnitContentComponent;
  let fixture: ComponentFixture<DropdownUnitContentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DropdownUnitContentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DropdownUnitContentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

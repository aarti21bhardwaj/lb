import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ArchivedUnitsComponentComponent } from './archived-units-component.component';

describe('ArchivedUnitsComponentComponent', () => {
  let component: ArchivedUnitsComponentComponent;
  let fixture: ComponentFixture<ArchivedUnitsComponentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ArchivedUnitsComponentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ArchivedUnitsComponentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

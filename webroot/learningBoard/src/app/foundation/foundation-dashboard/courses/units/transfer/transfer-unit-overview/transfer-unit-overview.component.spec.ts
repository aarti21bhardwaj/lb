import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TransferUnitOverviewComponent } from './transfer-unit-overview.component';

describe('TransferUnitOverviewComponent', () => {
  let component: TransferUnitOverviewComponent;
  let fixture: ComponentFixture<TransferUnitOverviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TransferUnitOverviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TransferUnitOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

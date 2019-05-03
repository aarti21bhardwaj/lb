import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StandardUsageTableComponent } from './standard-usage-table.component';

describe('StandardUsageTableComponent', () => {
  let component: StandardUsageTableComponent;
  let fixture: ComponentFixture<StandardUsageTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StandardUsageTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StandardUsageTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StrandUsageComponent } from './strand-usage.component';

describe('StrandUsageComponent', () => {
  let component: StrandUsageComponent;
  let fixture: ComponentFixture<StrandUsageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StrandUsageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StrandUsageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

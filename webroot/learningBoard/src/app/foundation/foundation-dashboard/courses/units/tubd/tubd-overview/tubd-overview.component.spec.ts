import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TubdOverviewComponent } from './tubd-overview.component';

describe('TubdOverviewComponent', () => {
  let component: TubdOverviewComponent;
  let fixture: ComponentFixture<TubdOverviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TubdOverviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TubdOverviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PypComponent } from './pyp.component';

describe('PypComponent', () => {
  let component: PypComponent;
  let fixture: ComponentFixture<PypComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PypComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PypComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

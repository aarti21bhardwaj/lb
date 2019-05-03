import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EvidenceAddComponent } from './evidence-add.component';

describe('EvidenceAddComponent', () => {
  let component: EvidenceAddComponent;
  let fixture: ComponentFixture<EvidenceAddComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EvidenceAddComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EvidenceAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

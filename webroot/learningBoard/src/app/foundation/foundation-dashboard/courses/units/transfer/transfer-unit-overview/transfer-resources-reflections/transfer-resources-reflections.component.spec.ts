import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TransferResourcesReflectionsComponent } from './transfer-resources-reflections.component';

describe('TransferResourcesReflectionsComponent', () => {
  let component: TransferResourcesReflectionsComponent;
  let fixture: ComponentFixture<TransferResourcesReflectionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TransferResourcesReflectionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TransferResourcesReflectionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { TestBed, inject } from '@angular/core/testing';

import { ResourcesReflectionsService } from './resources-reflections.service';

describe('ResourcesReflectionsService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ResourcesReflectionsService]
    });
  });

  it('should be created', inject([ResourcesReflectionsService], (service: ResourcesReflectionsService) => {
    expect(service).toBeTruthy();
  }));
});

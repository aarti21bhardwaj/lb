import { TestBed, inject } from '@angular/core/testing';

import { EvidencesService } from './evidences.service';

describe('EvidencesService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [EvidencesService]
    });
  });

  it('should be created', inject([EvidencesService], (service: EvidencesService) => {
    expect(service).toBeTruthy();
  }));
});
